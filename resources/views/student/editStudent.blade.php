@extends('layout.app')
@section('content')
    <div class="w-2/3 mx-auto mb-4 bg-white p-6 shadow-md rounded-lg">

        <form action="{{ route('editStudent', $student->_id) }}" id="editStudentForm" method="POST">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->_id }}">
            <div class="mb-1">
                <div class="flex justify-between">
                    <div>You can edit record of a student for the <strong>registration number:
                            {{ $student->registration_no }}</strong>.
                    </div>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('displayStudents') }}">Back to list</a>
                </div>

                <!-- Display Success or Error Messages -->
                @if (session('success'))
                    <div
                        class="relative mt-4 mb-4 p-4 text-sm text-green-700 bg-green-100 border border-green-300 rounded-lg">
                        {{ session('success') }}
                        <button type="button" onclick="this.parentElement.remove()"
                            class="absolute top-1 right-2 text-green-700 hover:text-green-900 hover:cursor-pointer">
                            x
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="relative mb-4 p-4 text-sm text-red-700 bg-red-100 border border-red-300 rounded-lg">
                        {{ session('error') }}
                        <button type="button" onclick="this.parentElement.remove()"
                            class="absolute top-1 right-2 text-red-700 hover:text-red-900 hover:cursor-pointer">
                            &times;
                        </button>
                    </div>
                @endif

                <!-- Display all the validation errors if exist -->
                @if ($errors->any())
                    <div class="relative mb-4 p-4 text-sm text-red-700 bg-red-100 border border-red-300 rounded-lg">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" onclick="this.parentElement.remove()"
                            class="absolute top-1 right-2 text-red-700 hover:text-red-900 hover:cursor-pointer">
                            &times;
                        </button>
                    </div>
                @endif

            </div>
            <div class="mt-2 mb-4">
                <label for="name_of_students">Name of the student:</label>
                <div class="flex">
                    <div class="w-1/2 mr-2">
                        <input type="text" name="name_of_students" id="name_of_students"
                            value="{{ old('name_of_students', $student->name_of_students) }}" class="relative form-input"
                            placeholder="Student's name in english" required>
                        <div class="error">
                            @if ($errors->has('name_of_students'))
                                {{ $errors->first('name_of_students') }}
                            @endif
                        </div>
                    </div>

                    <div class="w-1/2 ml-2">
                        <input type="text" lang="hi" dir="ltr" name="name_of_students_in_hindi"
                            value="{{ old('name_of_students_in_hindi', $student->name_of_students_in_hindi) }}"
                            class="relative form-input text-hindi" placeholder="Student's name in hindi" required>
                        <div class="error">
                            @if ($errors->has('name_of_students_in_hindi'))
                                {{ $errors->first('name_of_students_in_hindi') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 mb-4">

                <div class="flex justify-between">

                    <div class="w-1/2 mr-2">
                        <label for="course">Course:</label>
                        <input type="text" name="course" id="course" value="{{ old('course', $student->course) }}"
                            class="relative form-input" placeholder="course in english" required>
                        <div class="error">
                            @if ($errors->has('course'))
                                {{ $errors->first('course') }}
                            @endif
                        </div>
                    </div>
                    <div class="w-1/2 ml-2">
                        <label for="courseInHindi">पाठ्यक्रम</label>
                        <input type="text" id="courseInHindi" name="course_in_hindi"
                            value="{{ old('course_in_hindi', $student->course_in_hindi) }}"
                            class="relative form-input text-hindi" placeholder="Course in hindi" required>
                        <div class="error">
                            @if ($errors->has('course_in_hindi'))
                                {{ $errors->first('course_in_hindi') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 mb-4">
                <div class="flex justify-between">
                    <div class="w-1/2 mr-2">
                        <label for="batch">Batch:</label>
                        <input type="text" name="batch" id="batch" value="{{ old('batch', $student->batch) }}"
                            class="relative form-input" placeholder="batch" required>
                        <div class="error">
                            @if ($errors->has('batch'))
                                {{ $errors->first('batch') }}
                            @endif
                        </div>
                    </div>
                    <div class="w-1/2 ml-2">
                        <label for="year">Year:</label>
                        <input type="number" name="year" id="year" value="{{ old('year', $student->year) }}"
                            class="relative form-input" placeholder="year" required>
                        <div class="error">
                            @if ($errors->has('year'))
                                {{ $errors->first('year') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 mb-4">
                <div class="flex justify-between">
                    <div class="w-1/2 mr-2">
                        <label for="department">Department:</label>
                        <input type="text" name="department" id="department"
                            value="{{ old('department', $student->department) }}" class="relative form-input"
                            placeholder="department in english" required>
                        <div class="error">
                            @if ($errors->has('department'))
                                {{ $errors->first('department') }}
                            @endif
                        </div>
                    </div>
                    <div class="w-1/2 ml-2">
                        <label for="DepartmentInHindi">विभाग</label>
                        <input type="text" id="DepartmentInHindi" name="department_in_hindi"
                            value="{{ old('department_in_hindi', $student->department_in_hindi) }}"
                            class="relative form-input text-hindi" placeholder="Department in hindi" required>
                        <div class="error">
                            @if ($errors->has('department_in_hindi'))
                                {{ $errors->first('department_in_hindi') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 mb-4">
                <div class="flex justify-between">
                    <div class="w-1/2 mr-2">
                        <label for="Month">Month:</label>
                        <input type="text" name="month" id="Month" value="{{ old('month', $student->month) }}"
                            class="relative form-input" placeholder="Month in english" required>
                        <div class="error">
                            @if ($errors->has('month'))
                                {{ $errors->first('month') }}
                            @endif
                        </div>
                    </div>
                    <div class="w-1/2 ml-2">
                        <label for="monthInHindi">महीना</label>
                        <input type="text" id="monthInHindi" name="month_in_hindi"
                            value="{{ old('month_in_hindi', $student->month_in_hindi) }}"
                            class="relative form-input text-hindi" placeholder="Month in hindi" required>
                        <div class="error">
                            @if ($errors->has('month_in_hindi'))
                                {{ $errors->first('month_in_hindi') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 mb-4">
                <div class="flex justify-between">
                    <div class="w-1/2 mr-2">
                        <label for="father_name">Father's Name:</label>
                        <input type="text" name="father_name" id="father_name"
                            value="{{ old('father_name', $student->father_name) }}" class="relative form-input"
                            placeholder="Father's name" required>
                        <div class="error">
                            @if ($errors->has('father_name'))
                                {{ $errors->first('father_name') }}
                            @endif
                        </div>
                    </div>
                    <div class="w-1/2 ml-2">
                        <label for="mother_name">Mother's Name:</label>
                        <input type="text" id="mother_name" name="mother_name"
                            value="{{ old('mother_name', $student->mother_name) }}" class="relative form-input"
                            placeholder="Mother's name" required>
                        <div class="error">
                            @if ($errors->has('mother_name'))
                                {{ $errors->first('mother_name') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{-- @if ($student->sport_required == true) --}}
            @if (in_array($student->course, config('sports.sport_required_courses')))
                <!-- For Sports -->
                <div class="mt-2 mb-4">
                    <div class="flex justify-between">
                        <div class="w-1/2 mr-2">
                            <label for="Sports">Sports:</label>
                            <input type="text" name="sports" id="Sports"
                                value="{{ old('sports', $student->sports) }}" class="relative form-input"
                                placeholder="Sports in english">
                            <div class="error">
                                @if ($errors->has('sports'))
                                    {{ $errors->first('sports') }}
                                @endif
                            </div>
                        </div>
                        <div class="w-1/2 ml-2">
                            <label for="sportsInHindi">खेल</label>
                            <input type="text" id="sportsInHindi" name="sports_in_hindi"
                                value="{{ old('sports_in_hindi', $student->sports_in_hindi) }}"
                                class="relative form-input text-hindi" placeholder="Sports in hindi">
                            <div class="error">
                                @if ($errors->has('sports_in_hindi'))
                                    {{ $errors->first('sports_in_hindi') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Grade -->
            <div class="mt-2 mb-4">

                <div class="flex justify-between">
                    <div class="w-1/2 mr-2">
                        <label for="Grade">Grade:</label>
                        <input type="text" name="grade" id="Grade" value="{{ old('grade', $student->grade) }}"
                            class="relative form-input" placeholder="Grade" required>
                        <div class="error">
                            @if ($errors->has('grade'))
                                {{ $errors->first('grade') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 text-center">
                <button type="submit"
                    class="text-white bg-gradient-to-r from-green-400 
                    via-green-500 to-green-600 hover:cursor-pointer hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium 
                    rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Update</button>
            </div>
        </form>
    </div>
@endsection
@section('javascripts')
    <script src="{{ asset('js/sanscript.js') }}"></script>
    <script>
        document.querySelectorAll('.text-hindi').forEach(function(element) {
            element.addEventListener('input', function(e) {
                const transliteratedText = Sanscript.t(e.target.value, 'itrans', 'devanagari');
                e.target.value = transliteratedText;
            });
        });
    </script>
@endsection
