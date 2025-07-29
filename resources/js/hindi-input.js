window.transliterate = async function (text, lang = 'hi') {
    const response = await fetch(`https://inputtools.google.com/request?text=${encodeURIComponent(text)}&itc=${lang}-t-i0-und&num=1&cp=0&cs=1&ie=utf-8&oe=utf-8&app=demopage`);
    const data = await response.json();
    if (data[0] === 'SUCCESS') {
        return data[1][0][1]; // Array of transliteration suggestions
    } else {
        throw new Error("Transliteration failed");
    }
}

window.hindiInput = async function (inputElement, outputElement) {
    inputElement.addEventListener('input', async function () {
        try {
            const transliteratedText = await transliterate(inputElement.value, 'hi');
            outputElement.value = transliteratedText;
        } catch (error) {
            console.error("Error during transliteration:", error);
            outputElement.value = ''; // Clear output on error
        }
    });
};