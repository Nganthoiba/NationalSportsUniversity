@extends('layout.app')
@section('content')
    <div class="w-full lg:pr-8 mx-auto mt-2 justify-between">{{-- lg:w-3/4  --}}
        <div class="flex w-full gap-2">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 w-2/3">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Dashboard</h2>
                <p class="text-sm text-gray-600">Welcome to the dashboard! Here you can manage students and their records.
                </p>
                <div class="">
                    <h5 class="font-medium text-gray-700">Number of Registered Students</h5>
                    <ul class="text-gray-500 space-y-1 flex gap-4 justify-around">
                        <li class="flex flex-col items-center justify-center p-4">
                            <i class="fa-light fa fa-user-graduate"></i>
                            <span class="font-semibold">{{ $studentCount }}</span>
                            <span class="text-xs text-gray-500"><a href="{{ route('displayStudents') }}">Total</a></span>
                        </li>

                        <li class="flex flex-col items-center justify-center p-4">
                            <i class="fa-light fa fa-user-graduate"></i>
                            <span class="font-semibold">{{ $pendingCount }}</span>
                            <span class="text-xs text-blue-500"><a href="{{ route('displayStudents', 'pending') }}">Pending
                                    ...</a></span>
                        </li>

                        <li class="flex flex-col items-center justify-center p-4">
                            <i class="fa-light fa fa-user-graduate"></i>
                            <span class="font-semibold">{{ $approvedCount }}</span>
                            <span class="text-xs text-green-500"><a
                                    href="{{ route('displayStudents', 'signed') }}">Approved</a></span>
                        </li>

                        <li class="flex flex-col items-center justify-center p-4">
                            <i class="fa-light fa fa-user-graduate"></i>
                            <span class="font-semibold">{{ $dataChangedCount }}</span>
                            <span class="text-xs text-orange-500">
                                <a href="{{ route('displayStudents', 'Data Change Requested') }}">Data
                                    Change Request</a>
                            </span>
                        </li>
                    </ul>
                    <div>
                        <a href="{{ route('displayStudents') }}" class="text-sm text-blue-400 hover:text-blue-600">
                            <i class="fa-light fas fa-arrow-right"></i>
                            See the list of registered students</a>
                    </div>
                </div>
            </div>
            @if (Auth::user()->hasPermission('excel_file_upload') || Auth::user()->hasPermission('student_data_entry'))
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 w-1/3">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Students' Data Entry</h2>
                    @if (Auth::user()->hasPermission('excel_file_upload'))
                        <p class="text-sm text-gray-600">You can import students' records (in bulk) from an Excel file.</p>
                        <div class="mt-2">
                            <button class="text-sm btn cursor-pointer" id="importStudentsBtn" data-toggle='alert'
                                data-target="importStudentsForm" {{-- onclick="openCustomAlertBox('importStudentsForm');" --}}>
                                <i class="fa fa-light fa-file-import"></i>
                                Import Students From Excel File
                            </button>
                        </div>
                    @endif

                    @if (Auth::user()->hasPermission('student_data_entry'))
                        <p class="text-sm text-gray-600 mt-4">Or you can add new student (single record entry):</p>
                        <div class="mt-2">
                            <a href="{{ route('addNewStudent') }}" class="text-sm btn">
                                <i class="fa fa-light fa-person-circle-plus"></i>
                                Add New Student Manually
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
        @include('student.partial.importStudent')
    </div>
@endsection
