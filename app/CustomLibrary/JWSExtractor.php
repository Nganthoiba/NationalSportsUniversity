<?php
/**
 * This class is part of the custom library for extracting JWS (JSON Web Signature).
 * 1. It can extract header and payload details from a JWS string.
 * 2. It can also decode the JWS string to get the original payload.
 * 3. It provides a method to validate the JWS signature.
 */
namespace App\CustomLibrary;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\JWSLoader;


class JWSExtractor
{
    /**
     * Extracts the header and payload from a JWS string.
     *
     * @param string $jws The JWS string to extract from.
     * @return array An associative array containing 'header' and 'payload'.
     */
    public static function extract(string $jws): array
    {
        // Split the JWS into its components
        $parts = explode('.', $jws);
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException('Invalid JWS format.');
        }

        // Decode the header and payload
        $header = json_decode(base64_decode($parts[0]), true);
        $payload = json_decode(base64_decode($parts[1]), true);

        return [
            'header' => $header,
            'payload' => $payload,
        ];
    }

    /**
     * Decoades the header from a JWS string.
     * @param string $jws The JWS string to decode.
     * @return array The decoded header.
     */
    public static function decodeHeader(string $jws): array{
        // Split the JWS into its components
        $parts = explode('.', $jws);
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException('Invalid JWS format.');
        }

        // Decode the header
        $header = json_decode(base64_decode($parts[0]), true);

        return $header;
    }

    /**
     * Decodes the payload from a JWS string.
     *
     * @param string $jws The JWS string to decode.
     * @return array The decoded payload.
     */
    public static function decodePayload(string $jws): array
    {
        // Split the JWS into its components
        $parts = explode('.', $jws);
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException('Invalid JWS format.');
        }
        
        // Decode the payload
        $payload = json_decode(self::base64url_decode($parts[1]), true);
        if(is_null($payload)) {
            throw new \InvalidArgumentException('Invalid payload in JWS format.');
        }
        return $payload;
    }

    public static function verify(string $jwsString): bool
    {
        try {
            // Parse JWS
            $serializer = new CompactSerializer(); // For compact JWS format
            $jws = $serializer->unserialize($jwsString);

            $header = $jws->getSignature(0)->getProtectedHeader();

            if (!isset($header['x5c']) || !is_array($header['x5c']) || empty($header['x5c'])) {
                throw new InvalidArgumentException("x5c parameter is missing in the JWS header");
            }

            $x5cBase64 = $header['x5c'][0];
            $certPEM = self::convertBase64CertToPem($x5cBase64);

            $pubKeyResource = openssl_pkey_get_public($certPEM);
            if (!$pubKeyResource) {
                throw new RuntimeException("Failed to extract public key from certificate");
            }

            $pubKeyDetails = openssl_pkey_get_details($pubKeyResource);
            if ($pubKeyDetails === false || $pubKeyDetails['type'] !== OPENSSL_KEYTYPE_RSA) {
                throw new RuntimeException("Invalid public key format");
            }

            // Build JWK
            $jwk = new JWK([
                'kty' => 'RSA',
                'n'   => self::base64url_encode($pubKeyDetails['rsa']['n']),
                'e'   => self::base64url_encode($pubKeyDetails['rsa']['e']),
            ]);

            // Setup verifier
            $algorithmManager = new AlgorithmManager([new RS256()]);
            $jwsVerifier = new JWSVerifier($algorithmManager);

            // Verify signature
            $verified = $jwsVerifier->verifyWithKey($jws, $jwk, 0);
            /*
            if ($verified) {
                echo "✅ Signature verified!" . PHP_EOL;
                echo "📦 Payload: " . $jws->getPayload() . PHP_EOL;
            } else {
                echo "❌ Signature verification failed." . PHP_EOL;
            }
            */
            return $verified;

        } catch (\Throwable $e) {
            //dd("❗ Error: " . $e->getMessage() . PHP_EOL);
            return false;
        }
    }

    public static function convertBase64CertToPem(string $base64): string
    {
        return "-----BEGIN CERTIFICATE-----\n" .
            chunk_split($base64, 64, "\n") .
            "-----END CERTIFICATE-----\n";
    }

    public static function base64url_encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public static function base64url_decode(string $data): string
    {
        // Replace URL-safe characters back to base64 characters
        $base64 = strtr($data, '-_', '+/');

        // Add padding if necessary
        $padding = strlen($base64) % 4;
        if ($padding > 0) {
            $base64 .= str_repeat('=', 4 - $padding);
        }

        return base64_decode($base64);
    }

    /**
     * Method to verify the JWS string
     * @param string $jws The JWS string to be verified.
     * @return bool True if the signature is valid, false otherwise.
     * 
     */
    public static function isValidJWS(string $jws): bool{
        $parts = explode('.', $jws);
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException('Invalid JWS format.');
        }
        
        list($headerB64, $payloadB64, $signatureB64) = $parts;

        // Decode the header
        $header = json_decode(base64_decode($headerB64), true);

        if(!isset($header['x5c'][0])){
            throw new \InvalidArgumentException("Missing the signer's certificate");
        }

        $x5c = $header['x5c'][0]; //Getting the signer's certificate
        $publicKey = self::getPublicKeyFromX5C($x5c);

        // Reconstruct signing input
        $signingInput = "$headerB64.$payloadB64";

        // Decode signature
        //$signature = base64url_decode($signatureB64);
        $signature = self::base64url_decode($signatureB64);
        // Verify signature using OpenSSL
        $verified = openssl_verify($signingInput, $signature, $publicKey, OPENSSL_ALGO_SHA256);

        return ($verified === 1);
    }


    public static function getPublicKeyFromX5C($x5c /* Signer certificate */) {
        // Convert base64 DER-encoded cert to PEM
        $cert = "-----BEGIN CERTIFICATE-----\n" .
                chunk_split($x5c, 64, "\n") .
                "-----END CERTIFICATE-----\n";

        // Load the X.509 certificate and extract the public key
        $resource = openssl_x509_read($cert);
        $pubKey = openssl_pkey_get_public($resource);

        if (!$pubKey) {
            throw new Exception("Unable to extract public key from x5c");
        }

        return $pubKey;
    }

    /**
     * Validates the JWS signature.
     *
     * @param string $jws The JWS string to validate.
     * @param string $secret The secret key used for validation.
     * @return bool True if the signature is valid, false otherwise.
     * 
     * This validation is possible only if the secret key/PIN is known
     */
    public static function validateSignature(string $jws, string $secret): bool
    {
        // Split the JWS into its components
        $parts = explode('.', $jws);
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException('Invalid JWS format.');
        }

        // Reconstruct the signature
        $signature = hash_hmac('sha256', "$parts[0].$parts[1]", $secret, true);
        $signature = base64_encode($signature);

        // Compare the reconstructed signature with the provided signature
        return hash_equals($signature, $parts[2]);
    }

    /**
     * Extract Signer's as well as issuer's information from the JWS string if there is any x5c in the header.
     * @param string $jws The JWS string to extract from.
     * @return array the subject containing the signer's information.
     */
    public static function extractCertificateInfo(string $jws): array{
        // Split the JWS into its components
        $parts = explode('.', $jws);
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException('Invalid JWS format.');
        }

        // Decode the header
        $header = json_decode(base64_decode($parts[0]), true);

        // Check if x5c exists in the header
        if (!isset($header['x5c'][0])) {
            throw new \InvalidArgumentException("x5c certificate not found in header.");
        }
        
        $signerCertificate = $header['x5c'][0]; //Base 64 encoded
        $certPem = "-----BEGIN CERTIFICATE-----\n" .
            chunk_split($signerCertificate, 64, "\n") .
            "-----END CERTIFICATE-----\n";
        /*
        $isValid = openssl_x509_checkpurpose($certPem, X509_PURPOSE_ANY);
        if ($isValid === false) {
            throw new \InvalidArgumentException("Invalid certificate.");
        }*/

        $certInfo = openssl_x509_parse($certPem);

        if ($certInfo === false) {
            throw new \InvalidArgumentException("Failed to parse certificate.");
        }
        return $certInfo;
    }

    public static function getSubjectName(array $certInfo): string{
        if (!isset($certInfo['subject']['CN'])) {
            throw new \InvalidArgumentException("CN not found in certificate.");
        }
        return $certInfo['subject']['CN'];
    }

    public static function getIssuer(array $certInfo): string{
        if (!isset($certInfo['issuer']['CN'])) {
            throw new \InvalidArgumentException("CN not found in certificate.");
        }
        return $certInfo['issuer']['CN'];
    }

    public static function getSignerName(string $jws): string{
        try{
            $certInfo = self::extractCertificateInfo($jws);
            $signer = self::getSubjectName($certInfo);
        }
        catch(\Exception $e){
            return null;
        }
        return $signer;
    }

    public static function verifySignedData(string $jws, array $data): bool
    {
        // Decode the payload from the JWS
        $payload = self::decodePayload($jws);

        // Compare the decoded payload with the provided data
        // Assuming the data is an associative array, we can use json_encode for comparison

        return json_encode($payload) === json_encode($data);
    }
}