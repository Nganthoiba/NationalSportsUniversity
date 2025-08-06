@extends('layout.app')
@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 shadow-md rounded-lg">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Student Details</h2>
        <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-700 mt-2">
            <tbody class="divide-y divide-gray-200">


                <tr>
                    <td class="p-2 font-semibold ">Sl. No</td>
                    <td class="p-2 ">
                        <div>{{ $student->sl_no ?? 'N/A' }}</div>
                        @if ($isSigned && !$student->isSameAsSignedData('sl_no'))
                            <div class="signed-info">Previous Signed Data: {{ $student->getSignedData('sl_no') }}</div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold ">Name</td>
                    <td class="p-2 ">
                        <div>{{ $student->name_of_students }} ({{ $student->name_of_students_in_hindi }})</div>
                        @if ($isSigned)
                            @if (!$student->isSameAsSignedData('name_of_students') || !$student->isSameAsSignedData('name_of_students_in_hindi'))
                                <div class="signed-info">Previous Signed Data:
                                    {{ $student->getSignedData('name_of_students') }}
                                    ({{ $student->getSignedData('name_of_students_in_hindi') }})</div>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold ">Registration No</td>
                    <td class="p-2 ">
                        <div>{{ $student->registration_no }}</div>
                        @if ($isSigned)
                            @if (!$student->isSameAsSignedData('registration_no'))
                                <div class="signed-info">Previous Signed Data:
                                    {{ $student->getSignedData('registration_no') }}
                                </div>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold ">Course</td>
                    <td class="p-2 ">
                        <div>
                            <div>{{ $student->course }}</div>
                            <div>({{ $student->course_in_hindi }})</div>
                        </div>
                        @if ($isSigned)
                            @if (!$student->isSameAsSignedData('course') || !$student->isSameAsSignedData('course_in_hindi'))
                                <div class="signed-info">
                                    <div>Previous Signed Data:</div>
                                    <div>{{ $student->getSignedData('course') }}</div>
                                    <div>{{ $student->getSignedData('course_in_hindi') }}</div>
                                </div>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold ">Batch</td>
                    <td class="p-2 ">
                        <div>{{ $student->batch }}</div>
                        @if ($isSigned)
                            @if (!$student->isSameAsSignedData('batch'))
                                <div class="signed-info">
                                    Previous Signed Data: {{ $student->getSignedData('batch') }}
                                </div>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold ">Department</td>
                    <td class="p-2 ">
                        <div>{{ $student->department }} ({{ $student->department_in_hindi }})</div>
                        @if ($isSigned)
                            @if (!$student->isSameAsSignedData('department') || !$student->isSameAsSignedData('department_in_hindi'))
                                <div class="signed-info">
                                    <div>Previous Signed Data:</div>
                                    <div>
                                        {{ $student->getSignedData('department') }}
                                        ({{ $student->getSignedData('department_in_hindi') }})
                                    </div>
                                </div>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold ">Month</td>
                    <td class="p-2 ">
                        <div>{{ $student->month }} ({{ $student->month_in_hindi }})</div>
                        @if ($isSigned)
                            @if (!$student->isSameAsSignedData('month') || !$student->isSameAsSignedData('month_in_hindi'))
                                <div class="signed-info">
                                    Previous Signed Data:
                                    <div>
                                        {{ $student->getSignedData('month') }}
                                        ({{ $student->getSignedData('month_in_hindi') }})
                                    </div>
                                </div>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold ">Year</td>
                    <td class="p-2 ">
                        <div>{{ $student->year }}</div>
                        @if ($isSigned)
                            @if (!$student->isSameAsSignedData('year'))
                                <div class="signed-info">
                                    Previous Signed Data:
                                    <div>
                                        {{ $student->getSignedData('year') }}
                                    </div>
                                </div>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold ">Father's Name</td>
                    <td class="p-2 ">
                        <div>{{ $student->father_name }}</div>
                        @if ($isSigned)
                            @if (!$student->isSameAsSignedData('father_name'))
                                <div class="signed-info">
                                    Previous Signed Data:
                                    <div>
                                        {{ $student->getSignedData('father_name') }}
                                    </div>
                                </div>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold ">Mother's Name</td>
                    <td class="p-2 ">
                        <div>{{ $student->mother_name }}</div>
                        @if ($isSigned)
                            @if (!$student->isSameAsSignedData('mother_name'))
                                <div class="signed-info">
                                    Previous Signed Data:
                                    <div>
                                        {{ $student->getSignedData('mother_name') }}
                                    </div>
                                </div>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold ">Gender</td>
                    <td class="p-2 ">
                        <div>{{ $student->gender }}</div>
                        @if ($isSigned)
                            @if (!$student->isSameAsSignedData('gender'))
                                <div class="signed-info">
                                    Previous Signed Data:
                                    <div>
                                        {{ $student->getSignedData('gender') }}
                                    </div>
                                </div>
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold ">Grade</td>
                    <td class="p-2 ">
                        <div>{{ $student->grade }}</div>
                        @if ($isSigned)
                            @if (!$student->isSameAsSignedData('grade'))
                                <div class="signed-info">
                                    Previous Signed Data:
                                    <div>
                                        {{ $student->getSignedData('grade') }}
                                    </div>
                                </div>
                            @endif
                        @endif
                    </td>
                </tr>
                {{--  @if ($student->sport_required == true) --}}
                @if (in_array($student->course, config('sports.sport_required_courses')))
                    <tr>
                        <td class="p-2 font-semibold ">Sports</td>
                        <td class="p-2 ">
                            <div>{{ $student->sports ?? 'N/A' }} ({{ $student->sports_in_hindi ?? 'N/A' }})</div>
                            @if ($isSigned)
                                @if (!$student->isSameAsSignedData('sports') || !$student->isSameAsSignedData('sports_in_hindi'))
                                    <div class="signed-info">
                                        Previous Signed Data:
                                        <div>
                                            {{ $student->getSignedData('sports') }}
                                            ({{ $student->getSignedData('sports_in_hindi') }})
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="mt-4 flex justify-between">
            <div>
                <a href="{{ route('displayStudents', $status) }}?page={{ $page }}"
                    class="text-xs  px-4 py-2 bg-blue-500 text-white rounded-md mx-2 hover:bg-blue-700"><i
                        class="fa fa-arrow-left"></i> Back
                    to
                    List</a>

                @if (!$isSigned)
                    @if (Auth::user()->hasPermission('esign_student_data_approval'))
                        <button onClick = "approveStudent('{{ $student->_id }}');"
                            class="text-xs px-4 py-2 bg-green-500 text-white rounded-md mx-4 hover:cursor-pointer hover:bg-green-700">
                            <i class="fa fa-person-circle-check"></i> Approve & e-sign
                        </button>
                    @elseif (Auth::user()->hasPermission('student_data_edit'))
                        <a href="{{ route('requestForChange', $student->_id) }}"
                            class="text-xs px-4 py-2 bg-blue-500 text-white rounded-md mx-4 hover:cursor-pointer hover:bg-blue-700"><i
                                class="fa fa-edit"></i> Edit</a>
                    @endif
                @else
                    @if ($isDataIntegrityVerified)
                        <a href="{{ route('showCertificate', $student->_id) }}" target="_blank"
                            class="text-xs px-4 py-2 bg-blue-500 text-white rounded-md mx-4 hover:cursor-pointer">
                            <i class="fa fa-eye"></i>
                            Show Certificate</a>
                        @if (Auth::user()->hasPermission('print_certificate'))
                            <button onclick="printCertificate('{{ $student->_id }}')"
                                class="text-xs px-4 py-2 bg-blue-500 text-white rounded-md mx-2 hover:cursor-pointer">
                                <i class="fa fa-print"></i>
                                Print
                            </button>
                        @endif
                    @else
                        <button onClick = "approveStudent('{{ $student->_id }}');"
                            class="text-xs px-4 py-2 bg-green-500 text-white rounded-md mx-4 hover:cursor-pointer">Re
                            eSigning</button>
                        <div class="text-pink-800 text-xs font-medium">
                            * Data Integrity Broken. Re-eSigning is required to fix the
                            data integrity issue.
                        </div>
                    @endif
                @endif
            </div>
            <div class="text-xs">
                @if (!$isSigned)
                    <span
                        class="bg-pink-100 text-pink-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-pink-900 dark:text-pink-300">Not
                        yet approved and e-signed</span>
                @else
                    <div class="p-2 rounded-sm shadow-lg">
                        <div class="text-left">
                            <strong>Approved & Signed By:</strong>
                        </div>

                        <div class="flex flex-row">
                            <div>
                                @if (!$isSignatureValid)
                                    <div style="display: flex; align-items: center; color: red;">
                                        <span style="font-size: 24px; margin-right: 8px;">&#10008;</span>
                                    </div>
                                @elseif($isDataIntegrityVerified)
                                    <div style="display: flex; align-items: center; color: green;">
                                        <span style="font-size: 24px; margin-right: 8px;">&#10004;</span>
                                    </div>
                                @else
                                    <div style="color: orange; font-size: 24px;">&#9888;</div>
                                @endif
                            </div>
                            <div>
                                <span class="text-gray-600 text-xxs">
                                    @if (!$isSignatureValid)
                                        {{ 'Signature Invalid' }}
                                    @else
                                        {{ $signerName }}
                                    @endif
                                </span>
                                <br />
                                <span class="text-gray-400 text-xxs">Date & Time:
                                    {{ $student->approved_by['approved_at']->format('d-m-Y h:i:s A') ?? 'N/A' }}</span>
                                <br />
                                @if (!$isDataIntegrityVerified)
                                    <!-- Modal toggle -->
                                    <button data-modal-target="view-data-modal" data-modal-toggle="static-modal"
                                        class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm
                                        hover:cursor-pointer
                                        dark:bg-red-900
                                        dark:text-red-300"
                                        type="button">
                                        Data Integrity Broken, click to see detail.
                                    </button>
                                @else
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300">
                                        Data Integrity Verified</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @if ($isSigned && !$isDataIntegrityVerified)
            @php
                $rawData = $student->getRawData();
                $allSignedData = $student->getAllSignedData();

                $arrKeys = count($rawData) > count($allSignedData) ? array_keys($rawData) : array_keys($allSignedData);
                //$arrKeys = array_keys($rawData);
            @endphp
            <!-- Main modal -->
            <div id="view-data-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
                class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Comparision between currently modified data and signed data:
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 hover:cursor-pointer rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="view-data-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                            <div>
                                <table
                                    class="w-full text-xs text-left text-gray-900 border-collapse border border-gray-300">
                                    <thead>
                                        <tr>
                                            <th class="p-2 border">Field</th>
                                            <th class="p-2 border">Currently Modified Value</th>
                                            <th class="p-2 border">Signed Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($arrKeys as $key)
                                            @php
                                                if (isset($rawData[$key]) && isset($allSignedData[$key])) {
                                                    $bgColor =
                                                        $rawData[$key] == $allSignedData[$key]
                                                            ? ''
                                                            : 'data-missmatched';
                                                } else {
                                                    $bgColor = 'data-missmatched';
                                                }
                                            @endphp
                                            <tr class="border-b border border-gray-300 {{ $bgColor }}">
                                                <td class="p-2 border"><strong>{{ $key }}</strong></td>
                                                <td class="p-2 border">{{ $rawData[$key] ?? '' }}</td>
                                                <td class="p-2 border">{{ $allSignedData[$key] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <strong>#Note: Missmatched data is shown highlighted with yellow background.</strong>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="mt-2">
            @isset($message)
                @php
                    $textClass = $status == 'error' ? 'text-red-500' : 'text-green-500';
                @endphp
                <p class="{{ $textClass }}">{{ $message }}</p>
            @endisset
        </div>
        <div style="display:none;">
            @php
                $studentInfo = $student;
                // Exclude fields that are not needed for signing.
                unset($studentInfo->approved_by);
                unset($studentInfo->status);
                unset($studentInfo->updated_at);
            @endphp
            <textarea class="w-full" name="studentInfo" id="studentInfo" rows="10" cols="20"><?= json_encode($studentInfo) ?></textarea>
        </div>
    </div>

@endsection
@section('javascripts')
    <script>
        // Function to logout USB epass token for security
        async function logoutUSBToken() {
            let url = "{{ env('DIGISIGNDOMAIN') }}/api/logoutToken";
            try {
                const response = await fetch(url, {
                    method: "GET",
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const responseData = await response.json();
                if (!response.ok) {
                    if (response.status == 0) {
                        throw new Error(
                            `Unable to connect to the server at ${signJSONUrl}. Please check your internet connection.`
                        );
                    }
                    throw new Error(responseData.message || "An error occurred");
                }
                console.log(responseData.message);
            } catch (error) {
                console.error(error);
            }
        }

        async function approveStudent(studentId) {
            console.log('Approving student with id: ' + studentId);

            if (confirm('Are you sure you want to approve this student?')) {
                const domain = "{{ env('DIGISIGNDOMAIN') }}";
                var jws = await esignStudentInfo(domain);
                if (jws == null) {
                    console.log('JWS is null, may be the JSON data is invalid.');
                    //alert('An error occurred while signing the student information');
                    return;
                }
                logoutUSBToken(); //Logging out epass signing token
                confirmedFinalApprove(studentId, jws);
            }
        }

        async function confirmedFinalApprove(studentId, jws) {
            let url = "{{ route('approveStudent') }}";
            let csrfToken = "{{ csrf_token() }}";
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        studentId: studentId,
                        '_token': csrfToken,
                        'jws': jws
                    })
                })
                .then(response => response.json())
                .then((data) => {
                    //alert(data.message);
                    printCertificate(studentId);
                    window.location.reload();
                })
                .catch((error) => {
                    console.log('Error:', error);
                    alert('An error occurred while approving the student.');
                });
        }

        function printCertificate(studentId) {
            console.log('Printing certificate for student with id: ' + studentId);
            let showCertificateUrl = "{{ route('showCertificate', ':studentId') }}";
            showCertificateUrl = showCertificateUrl.replace(':studentId', studentId);
            let w = window.open(showCertificateUrl);
            if (w == null) {
                alert('Please allow popups for this website.');
                console.log('Popup blocked');
                return;
            }
            // Wait for content to load before printing
            w.onload = function() {
                w.focus(); // optional but helps on some browsers
                w.print();

                // Delay close to allow time for print dialog to open
                setTimeout(() => {
                    w.close();
                }, 500);
            };
        }

        var modalButtons = document.querySelectorAll("button[data-modal-toggle]");
        var hideButtons = document.querySelectorAll("button[data-modal-hide]");

        modalButtons.forEach(button => {
            let modalId = button.getAttribute("data-modal-target");
            button.addEventListener('click', e => {
                openModal(modalId);
            })
        });

        hideButtons.forEach(button => {
            let modalId = button.getAttribute("data-modal-hide");
            button.addEventListener('click', e => {
                closeModal(modalId);
            })
        });

        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.getElementById(modalId).classList.add('flex');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('flex');
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
@endsection
