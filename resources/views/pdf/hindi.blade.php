<!DOCTYPE html>
<html lang="hi">

<head>
    <meta charset="UTF-8">
    <title>Hindi PDF</title>
    <style>
        @font-face {
            font-family: 'NotoSansDevanagari';
            src: url("{{ storage_path('fonts/NotoSansDevanagari-Regular.ttf') }}") format('truetype');
        }

        body {
            font-family: 'NotoSansDevanagari', sans-serif;
        }
    </style>
</head>

<body>
    <h1>नमस्ते Laravel!</h1>
    <p>यह एक PDF फाइल है जिसमें हिंदी टेक्स्ट शामिल है।</p>
    <p>
        प्रमाणित किया जाता है कि ......शारीरिक शिक्षा...... विभाग की श्री/सुश्री ....फाल्गुन किरण महाजन..... ने इस
        विश्वविद्यालय से शारीरिक शिक्षा एवं खेल स्नातक [बी. पी. इ. एस] डिग्री प्राप्त की है, उन्होंने उक्त डिग्री के लिए
        ....... मई ......., .... 2024.... में आयोजित परीक्षा .... A+..... ग्रेड के साथ उत्तीर्ण की है।
    </p>
</body>

</html>
