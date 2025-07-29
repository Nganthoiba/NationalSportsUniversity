@extends('layout.app')
@section('content')
    <div class="container">
        <div class="w-full max">
            <form name="create_course_form" method="POST" action="{{ route('courses.add') }}">
                @csrf
                <div class="w-full max-w-4xl bg-white p-8 rounded-2xl shadow-lg">
                    <h6 class="text-3xl font-semibold text-gray-800 mb-8">Create New Course</h5>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Course Name in English -->
                            <div>
                                <label class="block mb-1 text-gray-700 font-medium">Course Name</label>
                                <input type="text" placeholder="Enter Course Name" name="course_name" class="form-input"
                                    required />
                                <!-- Showing error message for course name if exists -->
                                @if ($errors->has('course_name'))
                                    <span class="text-red-500 text-sm">{{ $errors->first('course_name') }}</span>
                                @endif
                            </div>

                            <!-- Course Name in Hindi -->
                            <div>
                                <label class="block mb-1 text-gray-700 font-medium">पाठ्यक्रम</label>
                                <input type="text" placeholder="Enter Course Name in Hindi" name="course_in_hindi"
                                    class="form-input" required />
                                <!-- Showing error message for course name in hindi if exists -->
                                @if ($errors->has('course_in_hindi'))
                                    <span class="text-red-500 text-sm">{{ $errors->first('course_in_hindi') }}</span>
                                @endif
                            </div>

                            <!-- Course Code -->
                            <div>
                                <label class="block mb-1 text-gray-700 font-medium">Short Form</label>
                                <input type="text" placeholder="Enter Course Code" name="short_form"
                                    class="form-input" />
                                <!-- Showing error message for course code if exists -->
                                @if ($errors->has('short_form'))
                                    <span class="text-red-500 text-sm">{{ $errors->first('short_form') }}</span>
                                @endif
                            </div>

                            <!-- Choose Department -->
                            <div>
                                <label for="department" class="block mb-1 text-gray-700 font-medium">Choose
                                    Department</label>
                                <select id="department" name="department_id" class="form-input">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->_id }}">
                                            {{ $department->dept_name }} ({{ $department->dept_name_in_hindi }})
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Showing error message for department if exists -->
                                @if ($errors->has('department_id'))
                                    <span class="text-red-500 text-sm">{{ $errors->first('department') }}</span>
                                @endif
                            </div>

                        </div>
                        <div>
                            <div class="flex gap-3 mb-4">
                                <!-- Back to list -->
                                <a href="{{ route('courses.list') }}"
                                    class="inline-block mt-6 px-6 py-2 bg-gray-300 text-gray-800 rounded-xl cursor-pointer hover:bg-gray-400 transition">
                                    Back to Courses List
                                </a>
                                <!-- Submit Button -->
                                <button type="submit"
                                    class="mt-6 px-6 py-2 bg-blue-600 text-white rounded-xl cursor-pointer hover:bg-blue-700 transition">
                                    Create Course
                                </button>
                            </div>

                            @if (session('success'))
                                <div x-data="{ show: true }" x-show="show"
                                    class="flex items-center justify-between bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                    <span>{{ session('success') }}</span>
                                    <button type="button" @click="show = false"
                                        class="text-green-700 hover:text-green-900 font-bold text-xl leading-none focus:outline-none">
                                        &times;
                                    </button>
                                </div>
                            @endif

                            <!-- Error Message -->
                            @if (session('error'))
                                <div x-data="{ show: true }" x-show="show"
                                    class="flex items-center justify-between bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                    <span>{{ session('error') }}</span>
                                    <button type="button" @click="show = false"
                                        class="text-red-700 hover:text-red-900 font-bold text-xl leading-none focus:outline-none">
                                        &times;
                                    </button>
                                </div>
                            @endif

                            <!-- Validation Errors -->
                            @if ($errors->any())
                                <div x-data="{ show: true }" x-show="show"
                                    class="flex items-center justify-between bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                                    <span>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </span>
                                    <button @click="show = false"
                                        class="text-yellow-700 hover:text-yellow-900 font-bold text-xl leading-none focus:outline-none">
                                        &times;
                                    </button>
                                </div>
                            @endif
                        </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('head_javascripts')
    <script src="https://unpkg.com/alpinejs@3.14.9/dist/cdn.min.js"></script>
@endsection
