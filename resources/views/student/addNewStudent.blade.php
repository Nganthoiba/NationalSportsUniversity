@extends('layout.app')
@section('css')
    <style>
        .form-input {
            @apply w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition;
        }

        .container {
            margin: auto;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="mx-auto">
            @include('layout.server_response')
        </div>
        <div class="w-full max-w-4xl bg-white p-8 rounded-2xl shadow-lg mx-auto">
            <h5 class="text-2xl font-semibold text-gray-800 mb-8">Student Record Entry Form</h5>

            <form action="{{ route('addNewStudent') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6" method="POST">
                @csrf
                <!-- Student's Name -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Student's Name</label>
                    <input type="text" placeholder="Student's Full Name" name="name_of_students"
                        value="{{ old('name_of_students') }}" class="form-input" />
                    <div>
                        <!-- Validation Error -->
                        @if ($errors->has('name_of_students'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('name_of_students') }}</span>
                        @endif
                    </div>
                </div>
                <!-- Student's Name in Hindi -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">&nbsp;</label>
                    <input type="text" placeholder="Student's Full Name in Hindi" name="name_of_students_in_hindi"
                        value="{{ old('name_of_students_in_hindi') }}" class="form-input" />
                    <div>
                        <!-- Validation Error -->
                        @if ($errors->has('name_of_students_in_hindi'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('name_of_students_in_hindi') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Month -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Month</label>
                    <select name="month" class="form-select">
                        <option value="">Select Month</option>
                        @foreach ($months as $month)
                            @php
                                $monthValue = $month['eng'] . '~' . $month['hindi'];
                                $selected = old('month') == $monthValue ? 'selected' : '';
                            @endphp
                            <option value="{{ $monthValue }}" {{ $selected }}>{{ $month['eng'] }}
                                ({{ $month['hindi'] }})
                            </option>
                        @endforeach
                    </select>
                    <div>
                        <!-- Validation Error -->
                        @if ($errors->has('month'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('month') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Year -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Year of Passing</label>
                    <select name="year" class="form-select" required>
                        <option value="">Select Year</option>
                        @for ($i = env('BASE_YEAR'); $i <= date('Y'); $i++)
                            @php
                                $selected = old('year') == $i ? 'selected' : '';
                            @endphp
                            <option {{ $selected }}>{{ $i }}</option>
                        @endfor
                    </select>

                    <div>
                        <!-- Validation Error -->
                        @if ($errors->has('year'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('year') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Batch -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Batch</label>
                    <input type="text" placeholder="e.g., 2021-25" name="batch" value="{{ old('batch') }}"
                        class="batch-field form-input" />
                    <div>
                        <!-- Validation Error -->
                        @if ($errors->has('batch'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('batch') }}</span>
                        @endif
                    </div>
                </div>


                <!-- Gender -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Select Gender</option>
                        @foreach ($genders as $gender)
                            <option value="{{ $gender->gender_name }}"
                                @if (old('gender') == $gender->gender_name) {{ 'selected' }} @endif>
                                {{ $gender->gender_name }}
                            </option>
                        @endforeach

                    </select>
                    <div>
                        <!-- Validation Error -->
                        @if ($errors->has('gender'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('gender') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Department -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Department</label>
                    <select name="department" class="form-select">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                @if (old('department') == $department->id) {{ 'selected' }} @endif>{{ $department->dept_name }}
                                ({{ $department->dept_name_in_hindi }})
                            </option>
                        @endforeach
                    </select>
                    <div>
                        <!-- Validation Error -->
                        @if ($errors->has('department'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('department') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Course -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Course</label>
                    <select name="course" class="form-select">
                        <option value="">Select Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}"
                                @if (old('course') == $course->id) {{ 'selected' }} @endif>{{ $course->course_name }}
                                ({{ $course->course_in_hindi }})
                            </option>
                        @endforeach
                    </select>
                    <div>
                        <!-- Validation Error -->
                        @if ($errors->has('course'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('course') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Father's Name -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Father's Name</label>
                    <input type="text" placeholder="Father's Full Name" name="father_name" class="form-input" />
                    <div>
                        <!-- Validation Error -->
                        @if ($errors->has('father_name'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('father_name') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Mother's Name -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Mother's Name</label>
                    <input type="text" placeholder="Mother's Full Name" name="mother_name"
                        value="{{ old('mother_name') }}" class="form-input" />
                    <div>
                        <!-- Validation Error -->
                        @if ($errors->has('mother_name'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('mother_name') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Sports -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Sports</label>
                    <select name="sports" class="form-input">
                        <option value="">Select Sport</option>
                        @foreach ($sports as $sport)
                            <option value="{{ $sport->id }}"
                                @if (old('sports') == $sport->id) {{ 'selected' }} @endif>{{ $sport->sport_name }}
                                ({{ $sport->sport_name_in_hindi }})
                            </option>
                        @endforeach
                    </select>
                    <div>
                        <!-- Validation Error -->
                        @if ($errors->has('sports'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('sports') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Grade -->
                <div>
                    <label class="block mb-1 text-gray-700 font-medium">Grade</label>
                    {{-- 
                    <input type="text" placeholder="e.g., A, B, C" name="grade" value="{{ old('grade') }}"
                        class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl 
                        focus:outline-none focus:ring-2 focus:ring-blue-500 transition" /> --}}
                    <select name="grade" id="grade" class="form-select">
                        <option value="">Select Grade</option>
                        @foreach ($grades as $grade)
                            @php
                                $selected = old('grade') == $grade->grade ? 'selected' : '';
                            @endphp
                            <option value="{{ $grade->grade }}" {{ $selected }}>{{ $grade->grade }}</option>
                        @endforeach
                    </select>

                    <div>
                        <!-- Validation Error -->
                        @if ($errors->has('grade'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('grade') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-2 flex justify-end pt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-xl transition shadow cursor-pointer">
                        Submit
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
@section('javascripts')
    <script>
        // Add any custom JavaScript here if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Focus on the first input field when the page loads
            const engStudentName = document.querySelector('input[name="name_of_students"]');
            if (engStudentName) {
                engStudentName.focus();
                engStudentName.addEventListener('input', async () => {
                    // Convert to uppercase as the user types
                    const transliterated = await transliterate(engStudentName.value, 'hi');
                    document.querySelector('input[name="name_of_students_in_hindi"]').value =
                        transliterated[
                            0] || '';
                });
            }
        });

        // Example: apply input mask after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.querySelector('input.batch-field');
            if (element) {
                const maskOptions = {
                    mask: '0000-00' // Indian phone number format
                };
                IMask(element, maskOptions);
            }
        });
    </script>
@endsection
