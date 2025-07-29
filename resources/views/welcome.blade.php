@extends('layout.app')

@section('content')
    <div class="flex mt-3 px-4">
        <div class="w-2/3">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                Welcome to the NSU Certificate Verification Portal
            </h1>

            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Instantly verify student certificates issued by National Sports University. Powered by a secure QR-based
                digital
                verification system, this portal ensures authenticity, transparency, and trust.
            </p>

            <div class="space-y-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300">How It Works</h2>
                    <ul class="list-disc pl-6 text-gray-600 dark:text-gray-400 mt-2">
                        <li>Each certificate includes a unique QR code linked to its verification page.</li>
                        <li>Scan the QR using any mobile scanner to view verified certificate details.</li>
                        <li>All data is approved and digitally signed by NSU before issuance.</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Key Features</h2>
                    <ul class="list-disc pl-6 text-gray-600 dark:text-gray-400 mt-2">
                        <li>Secure QR code verification system</li>
                        <li>Digitally signed certificates</li>
                        <li>Centralized and searchable certificate database</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Who Is It For?</h2>
                    <ul class="list-disc pl-6 text-gray-600 dark:text-gray-400 mt-2">
                        <li><strong>Employers</strong> – Validate student information with confidence</li>
                        <li><strong>Institutions</strong> – Access trusted academic records</li>
                        <li><strong>Students</strong> – Share your verifiable certificates globally</li>
                    </ul>
                </div>

                <div>
                    <p class="text-gray-600 dark:text-gray-400 mt-6">
                        For assistance or manual verification, contact us at
                        <a href="mailto:support@nsu-verify.com"
                            class="text-blue-600 dark:text-blue-400 underline">dummy-support@nsu-verify.com</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="w-1/3">
            <form action="{{ route('student.verifyStudentInfo') }}" method="POST"
                class="bg-white rounded-lg shadow-lg p-4">
                <h4 class="font-bold text-gray-600 border-b border-gray-300 text-2xl">
                    Verify student's information
                </h4>
                @csrf
                <div class="mt-3">
                    <label for="#">Registration No:</label>
                    <input type="text" name="registration_no" class="form-input">
                </div>
                <div class="mt-3">
                    <label for="#">Batch:</label>
                    <input type="text" name="batch" class="form-input">
                </div>
                <div class="mt-3">
                    <label for="#">Course:</label>
                    <select name="course" id="course" class="form-select">
                        <option value="">Select Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->course_name }}">{{ $course->course_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4 text-center">
                    <button class="btn btn-success">Check Info</button>
                </div>
            </form>
        </div>
    </div>
@endsection
