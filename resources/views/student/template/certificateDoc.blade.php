<html>

<head>
    <meta charset="UTF-8">
    <title>Certificate</title>

    <style>
        @font-face {
            font-family: 'Kokila';
            src: url('file://{{ storage_path('fonts/Kokila.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'OldEnglishTextMT';
            src: url('file://{{ storage_path('fonts/oldenglishtextmt.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'LohitDevanagari';
            src: url('file://{{ storage_path('fonts/Lohit-Devanagari.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'TiroDevanagariHindiRegular';
            src: url('file://{{ storage_path('fonts/TiroDevanagariHindi-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'TiroDevanagariHindiItalic';
            src: url('file://{{ storage_path('fonts/TiroDevanagariHindi-Italic.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: Arial, sans-serif, 'Times New Roman', Times, serif;
            color: #555557;
        }

        .container {
            width: 580px;
            margin: 0 auto;
            /* border: 1px solid #000; */
            padding: 20px 45px;
        }

        .text-center {
            text-align: center;
        }

        .text-justify {
            text-align: justify;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .strong {
            font-weight: bold;
        }

        .old-english-text-MT {
            font-family: 'OldEnglishTextMT', serif;
        }

        .hindi-heading {
            font-family: 'LohitDevanagari', sans-serif;
            font-weight: bold;
        }

        .hindi-text {
            font-family: 'Kokila', 'LohitDevanagari', 'TiroDevanagariHindiRegular', sans-serif;
            font-size: 1.2rem;
            line-height: 1.8;
        }

        .hindi-text strong {
            font-family: 'TiroDevanagariHindiItalic', sans-serif;
        }

        .english-heading {
            font-family: 'OldEnglishTextMT', serif;
        }

        .tiro-devanagari-hindi-italic {
            font-family: 'TiroDevanagariHindiItalic', serif;
        }

        .w-100 {
            width: 100%;
        }

        .d-flex {
            display: flex;
        }

        .d-flex:first-child {
            margin-right: 20px;
        }

        .d-flex div {
            flex: 1;
        }


        .justify-content-center {
            justify-content: center;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .text-grey {
            color: #4c4c4d !important;
        }

        p {
            line-height: 1.2;
        }

        /* strong {
            text-decoration: underline;
            text-decoration-style: dotted;
        } */

        section {
            margin-top: 30px;
        }

        .qr-code {
            z-index: 9;
            margin-top: -70px
        }

        .qr-code svg {
            z-index: 9;
        }
    </style>
</head>

<body>
    <div class="container text-grey">
        @if ($status == 'success')
            <div class="d-flex justify-content-between">
                <div><span class="strong old-english-text-MT">Regd. No:</span> {{ $student->registration_no }}</div>
                <div class="text-right"><span class="strong old-english-text-MT">Sl. No.</span> {{ $student->Sl_No }}
                </div>
            </div>

            <div class="text-center" style="margin-top: 25px;z-index:-1">
                {{-- <h1 class="hindi-heading">राष्ट्रीय खेल विश्वविद्यालय मणिपुर, भारत</h1> --}}
                <img src="{{ public_path('images/headingHindi.PNG') }}" alt="" height="80px" width="440px">
            </div>

            <div class="text-center" style="margin-top: -22px; z-index:10;">
                <img src="{{ public_path('images/National_Sports_University_logo.png') }}" alt="" height="100"
                    width="100" />
                <h1 class="english-heading" style="font-family: 'OldEnglishTextMT', serif;">
                    National Sports University, Manipur, <br />
                    India
                </h1>
                {{-- <p>{{ storage_path('fonts/Lohit-Devanagari.ttf') }}</p> --}}
            </div>
            <section>
                <div class="text-center">
                    <h2 style="font-family: 'LohitDevanagari', sans-serif;">
                        खेल कोचिंग में विज्ञान का स्नातक
                    </h2>
                </div>
                <p class="text-justify hindi-text">
                    प्रमाणित किया जाता है कि ... <strong
                        class="tiro-devanagari-hindi-italic">{{ $student->department_in_hindi }}</strong> ... विभाग के
                    श्री/सुश्री
                    ...<strong
                        class="tiro-devanagari-hindi-italic">{{ $student->name_of_students_in_hindi }}</strong>... ने
                    इस
                    विश्वविद्यालय से
                    ...<strong class="tiro-devanagari-hindi-italic">{{ $student->sports_in_hindi }}</strong>... में खेल
                    कोचिंग में
                    विज्ञान के स्नातक डिग्री प्राप्त की है, उन्होंने उक्त डिग्री के लिए ...
                    <strong class="tiro-devanagari-hindi-italic">{{ $student->month_in_hindi }}</strong> ... 20
                    ...<strong>{{ substr($student->year, -2) }}</strong>...
                    में आयोजित परीक्षा ...<strong>{{ $student->grade }}</strong>...ग्रेड के साथ उत्तीर्ण की है।
                </p>
            </section>

            <section>
                <div class="text-center">
                    <h2 class="old-english-text-MT">Bachelor of Science in Sports Coaching</h2>
                </div>

                <p class="text-justify" style="font-family: 'Times New Roman', Times, serif; font-size:1.1rem;">
                    This is to certify that Mr/Ms ... <strong>{{ $student->name_of_students }}</strong> ... of the
                    Department of ....<strong>{{ $student->department }}</strong> ..., has obtained the degree
                    in Bachelor of Science in Sports Coaching in ...<strong>{{ $student->sports }}</strong>
                    ... of this University, having passed the examination for the said degree held in ....
                    <strong>{{ $student->month }}</strong> ...., 20
                    ...<strong>{{ substr($student->year, -2) }}</strong>...
                    with ...<strong>{{ $student->grade }}</strong>... grade.
                </p>
            </section>
        @else
            <div class="text-center">
                <h4>Sorry, {{ $message }}</h4>
            </div>
        @endif

    </div>

</body>

</html>
