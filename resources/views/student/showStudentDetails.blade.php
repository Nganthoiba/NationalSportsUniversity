@extends('layout.app')
@section('content')
    <div class="max-w-3xl mx-auto bg-white p-6 shadow-md rounded-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Student Details</h2>
        @if ($status == 'success')
            <table class="w-full border-collapse border border-gray-300">
                <tr>
                    <td class="p-2 font-semibold border">Sl. No</td>
                    <td class="p-2 border">{{ $student->sl_no }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Name</td>
                    <td class="p-2 border">{{ $student->name_of_students }} ({{ $student->name_of_students_in_hindi }})</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Registration No</td>
                    <td class="p-2 border">{{ $student->registration_no }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Course</td>
                    <td class="p-2 border">
                        <div>{{ $student->course }}</div>
                        <div>{{ $student->course_in_hindi }}</div>
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Batch</td>
                    <td class="p-2 border">{{ $student->batch }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Department</td>
                    <td class="p-2 border">{{ $student->department }} ({{ $student->department_in_hindi }})</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Month</td>
                    <td class="p-2 border">{{ $student->month }} ({{ $student->month_in_hindi }})</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Year</td>
                    <td class="p-2 border">{{ $student->year }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Father's Name</td>
                    <td class="p-2 border">{{ $student->father_name }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Mother's Name</td>
                    <td class="p-2 border">{{ $student->mother_name }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Gender</td>
                    <td class="p-2 border">{{ $student->gender }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Grade</td>
                    <td class="p-2 border">{{ $student->grade }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Sports</td>
                    <td class="p-2 border">{{ $student->sports ?? 'N/A' }} ({{ $student->sports_in_hindi ?? 'N/A' }})</td>
                </tr>
            </table>
            <div class="mx-auto w-full text-center mt-2">
                <button class="btn btn-info" onclick="window.history.back();"><i class="fa fa-arrow-left"></i> Back</button>
            </div>
        @endif

        <div class="mt-2">
            @isset($message)
                @php
                    $textClass = $status == 'error' || $status == 'warning' ? 'text-red-500' : 'text-green-500';
                @endphp
                <p class="{{ $textClass }}">{{ $message }}</p>
            @endisset
        </div>
    </div>
@endsection
