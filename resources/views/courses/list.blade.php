@extends('layout.app')
@section('content')
    <!-- Displaying courses ($courses) in a table -->
    <div class="container mx-auto px-4 py-6">
        <div class="mb-2 flex justify-between">
            <h1 class="text-3xl font-semibold mb-6">Courses List</h1>
            <div>
                <a href="{{ route('courses.add') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    + Create New Course
                </a>
            </div>
        </div>
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-auto">
            <thead>
                <tr class="bg-blue-200 text-gray-700 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">#</th>
                    <th class="py-3 px-6 text-left">Course Name</th>
                    <th class="py-3 px-6 text-left">Short Form</th>
                    <th class="py-3 px-6 text-left">Department</th>
                    <th class="py-3 px-6 text-center">Enabled</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($courses as $key => $course)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ $key + 1 }}.</td>
                        <td class="py-3 px-6">
                            <div>{{ $course->course_name }}</div>
                            <div>{{ $course->course_in_hindi }}</div>
                        </td>
                        <td class="py-3 px-6">{{ $course->short_form }}</td>
                        <td class="py-3 px-6">
                            <div>{{ $course->department->dept_name ?? 'N/A' }}</div>
                            <div>{{ $course->department->dept_name_in_hindi ?? '' }}</div>
                        </td>
                        <td class="py-3 px-6">
                            {{-- <a href="{{ route('courses.edit', $course->id) }}" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-edit"></i>

                            </a>| --}}
                            @php
                                $checked = $course->enabled ? 'checked' : '';
                            @endphp
                            <div class="flex w-full text-right justify-between">
                                <input type="checkbox" name="enableOrDisableCourse"
                                    class="cursor-pointer enableOrDisableCourse" value="{{ $course->id }}"
                                    id="enableOrDisableCourse{{ $course->id }}" {{ $checked }} />
                                <div id="spinner_{{ $course->id }}"
                                    class="hidden w-5 h-5 border-4 border-blue-500 border-t-transparent rounded-full animate-spin">
                                </div>
                            </div>

                            {{-- |
                            <form action="{{ route('courses.delete', $course->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('POST')
                                <button type="button" class="text-red-500 hover:text-red-700 cursor-pointer"
                                    onclick="if(confirm('Are you sure you want to delete this course?')) { this.form.submit(); }">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
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
        </div>
    </div>
@endsection
@section('javascripts')
    <script>
        const enableOrDisableUrl = "{{ route('course.EnableOrDisable') }}";
        document.addEventListener('DOMContentLoaded', function() {
            if (window.$) {

                //list of courses will be displayed in a table

            } else {
                console.error('$ is not defined yet.');
            }
        });

        document.querySelectorAll("input[type=checkbox].enableOrDisableCourse").forEach((checkBox) => {
            checkBox.addEventListener('change', (e) => {
                console.log("changing");

                var flag = e.target.checked;
                var course_id = e.target.value;

                var question = flag ? "Are you sure to enable this course?" :
                    "Are you sure to disable this course?";

                if (!confirm(question)) {
                    e.target.checked = !e.target.checked;
                    return;
                }

                document.querySelector("#spinner_" + course_id).classList.remove("hidden");
                fetch(enableOrDisableUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            id: course_id,
                            flag: flag,
                            _token: "{{ csrf_token() }}"
                        })
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.message) {
                            alert(data.message);
                        }
                        console.log(data);
                        document.querySelector("#spinner_" + course_id).classList.add("hidden");
                    })
                    .catch((error) => {
                        console.error("Error: ", error);
                    });
            });
        });
    </script>
@endsection
