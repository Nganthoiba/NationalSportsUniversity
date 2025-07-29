@extends('layout.app')
@section('css')
    <style>
        table.data-change-table {}

        table.data-change-table thead th {
            background-color: #f3f4f6;
            /* Tailwind's gray-100 */
            color: #374151;
            /* Tailwind's gray-700 */
            vertical-align: top !important;
        }

        table.data-change-table tbody tr:hover {
            background-color: #e8eaeb;
            /* Tailwind's gray-50 */
        }

        table.data-change-table td {
            vertical-align: top !important;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="max-w-5xl p-8 border-gray-300 bg-white shadow-lg rounded-lg overflow-x-auto mx-auto">
            <!--  max-w-4xl bg-white p-8 rounded-2xl shadow-lg -->
            <div class="mt-2 mb-2">
                <h6 class="font-semibold text-gray-800 mb-8">Request for data change of the registration no.
                    <strong>"{{ $student->registration_no }}"</strong></h5>
                    <p>You can select the fields that you wish to change.</p>
            </div>
            <form action="{{ route('student.datachange') }}" name="requestForChangeForm" method="POST">
                @csrf
                <input type="hidden" name="registration_no" value="{{ $student->registration_no }}" />
                <table class="min-w-full divide-y data-change-table divide-gray-200 text-sm text-left text-gray-700">
                    <thead class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Fields</th>
                            <th class="px-4 py-3">Current data</th>
                            <th class="px-4 py-3">New Data to be updated</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($fields as $field => $fieldData)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox"
                                            class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring focus:ring-blue-300"
                                            data-target-input-field="{{ $field }}" name="records_to_be_changed[]"
                                            id="checkbox_{{ $field }}" value="{{ $field }}">
                                        <span class="text-gray-700"><strong>{{ $fieldData['DisplayAs'] }}</strong></span>
                                    </label>
                                </td>
                                <td class="px-4 py-3">
                                    <div>{{ $student->{$field} }}</div>
                                    @if (in_array($field, ['course', 'department', 'month', 'sports', 'name_of_students']))
                                        <div class="text-xs text-gray-500">
                                            ({{ $student->{$field . '_in_hindi'} }})
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div id="{{ $field }}" style="display:none;">
                                        @switch($field)
                                            @case('course')
                                                <select name="course" class="new_data form-select">
                                                    <option value="">Select Course</option>
                                                    @foreach ($courses as $course)
                                                        <option value="{{ $course->id }}">
                                                            {{ $course->course_name }}
                                                            ({{ $course->course_in_hindi }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @break

                                            @case('department')
                                                <select name="department" class="new_data form-select">
                                                    <option value="">Select Department</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}">
                                                            {{ $department->dept_name }}
                                                            ({{ $department->dept_name_in_hindi }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @break

                                            @case('month')
                                                <select name="month" class="new_data form-select">
                                                    <option value="">Select Month</option>
                                                    @foreach ($months as $month)
                                                        <option value="{{ $month['eng'] }}~{{ $month['hindi'] }}">
                                                            {{ $month['eng'] }} ({{ $month['hindi'] }})</option>
                                                    @endforeach
                                                </select>
                                            @break

                                            @case('sports')
                                                <select name="sports" class="new_data form-select">
                                                    <option value="">Select Sports</option>
                                                    @foreach ($sports as $sport)
                                                        <option value="{{ $sport->_id }}">
                                                            {{ $sport->sport_name }}
                                                            ({{ $sport->sport_name_in_hindi }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @break

                                            @case('grade')
                                                <select name="grade" class="new_data form-select">
                                                    <option value="">Select Grade</option>
                                                    @foreach ($grades as $item)
                                                        <option value="{{ $item->grade }}">
                                                            {{ $item->grade }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @break

                                            @case('gender')
                                                <select name="gender" class="new_data form-select">
                                                    <option value="">Select Gender</option>
                                                    @foreach ($genders as $item)
                                                        <option value="{{ $item->gender_name }}">
                                                            {{ $item->gender_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @break

                                            @case('name_of_students')
                                                <div>
                                                    <input type="text" class="new_data form-input" id="studentNameEnglish"
                                                        name="{{ $field }}" placeholder="Students Name">
                                                </div>
                                                <div class="mt-2">
                                                    <input type="text" class="new_data form-input" id="studentNameHindi"
                                                        name="{{ $field . '_in_hindi' }}" placeholder="छात्रों_का_नाम">
                                                </div>
                                            @break

                                            @case('batch')
                                                <input type="text" class="new_data form-input batch-field"
                                                    name="{{ $field }}" placeholder="{{ $fieldData['DisplayAs'] }}">
                                            @break

                                            @case('year')
                                                <select name="{{ $field }}" class="new_data form-select">
                                                    <option value="">Select {{ $fieldData['DisplayAs'] }}</option>
                                                    @for ($i = env('BASE_YEAR'); $i <= date('Y'); $i++)
                                                        <option>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            @break

                                            @default
                                                <input type="text" class="new_data form-input" name="{{ $field }}"
                                                    placeholder="{{ $fieldData['DisplayAs'] }}">
                                        @endswitch
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2 mb-2">
                    <textarea class="form-input w-full" name="reason_of_change" id="reason_of_change"
                        placeholder="Please mention the reason for changing student's record." rows="6" required></textarea>
                </div>
                <div class="text-center">
                    <button class="btn btn-primary">
                        <div class="flex gap-2">
                            <div id="spinner_submit"
                                class="hidden w-5 h-5 border-2 border-white-500 border-t-transparent rounded-full animate-spin">
                            </div> <span>Submit Request</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('javascripts')
    <script>
        var redirectTo = "{{ route('displayStudents', 'Data Changed') }}";
        document.querySelectorAll("input[type='checkbox'][name='records_to_be_changed[]']").forEach(checkbox => {
            checkbox.addEventListener("change", (event) => {
                let inputFieldContainer = document.querySelector("#" + event.target.getAttribute(
                    "data-target-input-field"));
                if (event.target.checked) {
                    inputFieldContainer.style.display = "block";
                    inputFieldContainer.querySelectorAll("input, select").forEach((inputFiled) => {
                        inputFiled.setAttribute("required", true);
                    });
                } else {
                    inputFieldContainer.style.display = "none";
                    inputFieldContainer.querySelectorAll("input, select").forEach((inputFiled) => {
                        inputFiled.removeAttribute("required");
                    });
                }
            });
        });

        document.forms['requestForChangeForm'].onsubmit = function(event) {
            event.preventDefault();

            //validation if at least a checkbox is checked
            if (document.querySelectorAll("input[name='records_to_be_changed[]']:checked").length ==
                0) {
                alert("You haven't chosen any field to change student's data ");
                document.querySelectorAll("input[name='records_to_be_changed[]']")[0].focus();
                return;
            }


            if (!confirm("Are you sure to submit the changes in the student's data?")) {
                return;
            }

            //this.submit();
            const form = this;
            const formData = new FormData(form);
            document.querySelector("#spinner_submit").classList.remove("hidden");
            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(async response => {
                    /* if (!response.ok) throw new Error("Failed to submit.");
                    return response.json(); // Adjust based on your backend's response format */
                    const contentType = response.headers.get('content-type');

                    let responseData;
                    if (contentType && contentType.includes('application/json')) {
                        responseData = await response.json();
                    } else {
                        responseData = await response.text(); // fallback
                    }

                    if (!response.ok) {
                        // Throw the error message returned by the server
                        throw new Error(responseData.message || 'An unknown error occurred.');
                    }

                    return responseData;
                })
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        form.reset();
                        document.querySelectorAll(".new_data").forEach(el => {
                            el.style.display = "none";
                            el.removeAttribute("required");
                        });
                        //window.location.assign(redirectTo);
                    }
                    console.log(data);
                    document.querySelector("#spinner_submit").classList.add("hidden");
                })
                .catch(err => {
                    console.error("Error:", err);
                    alert("Something went wrong. " + err);
                    document.querySelector("#spinner_submit").classList.add("hidden");
                });

        }



        //await hindiInput(studentNameEnglish, studentNameEnglish);
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Focus on the first input field when the page loads
            const studentNameEnglish = document.querySelector("#studentNameEnglish");
            const studentNameHindi = document.querySelector("#studentNameHindi");
            if (studentNameEnglish) {
                studentNameEnglish.addEventListener('input', async () => {
                    // Convert to uppercase as the user types
                    const transliterated = await transliterate(studentNameEnglish.value, 'hi');
                    studentNameHindi.value =
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
