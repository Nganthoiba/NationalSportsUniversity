@extends('layout.app')
@section('css')
    <style>
        table.data-change-table {}
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="max-w-5xl p-8 border-gray-300 bg-white shadow-lg rounded-lg overflow-x-auto mx-auto">
            <!--  max-w-4xl bg-white p-8 rounded-2xl shadow-lg -->
            <div class="mt-2 mb-2">
                <h6 class="font-semibold text-gray-800 mb-8">Request for data change of the registration no.
                    <strong>"{{ $student->registration_no }}"</strong></h5>
            </div>
            <form action="#" name="approveDataChangeForm" method="POST">
                @csrf
                <input type="hidden" name="registration_no" value="{{ $student->registration_no }}" />
                <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-700">
                    <thead class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Fields</th>
                            <th class="px-4 py-3">Current data</th>
                            <th class="px-4 py-3">New Data to be updated</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($fields as $field => $fieldData)
                            @if (str_contains($field, 'in_hindi'))
                                @continue
                            @endif
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                                        <span class="text-gray-700"><strong>{{ $fieldData['DisplayAs'] }}</strong></span>
                                        @if (isset($fieldData['isGroup']) && $fieldData['isGroup'])
                                            @foreach ($fieldData['subFields'] as $subField => $subfieldData)
                                                <span
                                                    class="text-gray-500">{{ $subfieldData['DisplayAs'] ? '(' . $subfieldData['DisplayAs'] . ')' : '' }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </label>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $strikethrough = in_array(
                                            $field,
                                            array_keys($dataChange->records_to_be_changed),
                                        )
                                            ? 'strikethrough'
                                            : '';
                                    @endphp
                                    <div class="{{ $strikethrough }}">
                                        <div>{{ $student->{$field} }}</div>
                                    </div>

                                    @if (isset($fieldData['isGroup']) && $fieldData['isGroup'])
                                        @foreach ($fieldData['subFields'] as $subField => $subfieldData)
                                            @php
                                                $strikethrough = in_array(
                                                    $subField,
                                                    array_keys($dataChange->records_to_be_changed),
                                                )
                                                    ? 'strikethrough'
                                                    : '';
                                            @endphp
                                            <div class="{{ $strikethrough }}">{{ $student->$subField ?? '' }} </div>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="new-data">
                                        <div>
                                            @if (in_array($field, array_keys($dataChange->records_to_be_changed)))
                                                {{ $dataChange->records_to_be_changed[$field] }}
                                            @endif
                                        </div>
                                        <div>
                                            @if (isset($fieldData['isGroup']) && $fieldData['isGroup'])
                                                @foreach ($fieldData['subFields'] as $subField => $subfieldData)
                                                    {{ $dataChange->records_to_be_changed[$subField] ?? '' }}
                                                @endforeach
                                            @endif
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2 mb-2">
                    <h5>Reason for change:</h5>
                    <textarea class="form-input w-full" rows="6" readonly>{{ $dataChange->reason_of_change }}</textarea>
                </div>

                <div class="data_to_be_signed hidden">
                    @php
                        $studentInfo = $newStudent;
                        // Exclude fields that are not needed for signing.
                        unset($studentInfo->approved_by);
                        unset($studentInfo->status);
                        unset($studentInfo->updated_at);
                    @endphp
                    <textarea class="w-full" name="studentInfo" id="studentInfo" rows="10" cols="20"><?= json_encode($studentInfo) ?></textarea>
                </div>

                <div class="mt-2 mb-2 justify-between" style="display:flex;">
                    <div class="p-2 rounded-sm shadow-lg">
                        <h5 class="text-gray-500">Request made by: </h5>
                        <div class="text-sm">{{ $requestedBy }}</div>
                        <div class="text-sm">{{ date('d M, Y H:i A', strtotime($dataChange->date_of_request)) }}</div>
                    </div>

                    <div class="gap-2">
                        <a href="javascript:window.history.back();" class="btn btn-primary p-4"><span>Back to
                                list</span></a>
                        @if (Auth::user()->isRole(['admin']))
                            <button type="button" data-toggle="modal" data-target="cancelRequestModal"
                                data-student-id="{{ $studentInfo->id }}" class="btn btn-danger"
                                id="reject-data-change-btn">Cancel</button>
                            <button type="button" data-student-id="{{ $studentInfo->id }}" class="btn btn-success"
                                id="approve-data-change-btn">Approve</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal backdrop and box -->
    <div id="cancelRequestModal"
        class="modal fixed inset-0 hidden items-center justify-center z-50 transition-opacity duration-300 p-6"
        style="background-color: rgba(152, 167, 152, 0.3);">

        <div
            class="modal-body relative bg-gray-100 rounded-lg p-4 w-[50%] max-w-[1200px] shadow-lg transform scale-95 opacity-0 transition-all duration-300">
            <!-- Close buttons -->
            <button type="button"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-2xl cursor-pointer close-modal">
                &times;
            </button>
            <form action="{{ route('student.cancelDataChange') }}" name="cancelRequestForm" method="POST">
                @csrf
                <div style="margin-top: 25px; margin-bottom:5px;display:flex flex-col; justify-content:space-between">
                    <h5 class="font-semibold ">Please mention the reason why you must cancel the request.</h5>
                    <textarea class="form-input w-full" name="reason" rows="5" required></textarea>
                    <input type="hidden" name="registration_no" value="{{ $dataChange->registration_no }}" />
                    <input type="hidden" name="id" value="{{ $dataChange->id }}" />
                </div>
                <div class="my-3 text-right gap-2 ">
                    <button class="btn btn-primary" type="submit">Confirm Cancel</button>
                    <button class="btn btn-secondary close-modal" type="button">Close</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('javascripts')
    <script>
        async function approveStudentDataChange(studentId) {
            console.log('Approving changes in the record of student with id: ' + studentId);

            if (confirm('Are you sure you want to approve the changes to be made for this particular student?')) {
                const domain = "{{ env('DIGISIGNDOMAIN') }}";
                var jws = await esignStudentInfo(domain);
                if (jws == null) {
                    console.log('JWS is null, may be the JSON data is invalid.');
                    //alert('An error occurred while signing the student information');
                    return;
                }
                confirmedFinalApproveDataChange(studentId, jws);
            }
        }

        async function confirmedFinalApproveDataChange(studentId, jws) {
            let url = "{{ route('approveStudentDataChanges') }}";
            let csrfToken = "{{ csrf_token() }}";
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        'studentId': studentId,
                        '_token': csrfToken,
                        'jws': jws
                    })
                })
                .then(async response => {
                    //
                    if (!response.ok) {
                        // If response code is not in the 200–299 range
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Server returned an error.');
                    }
                    return response.json();
                })
                .then((data) => {
                    alert(data.message);
                    window.location.reload();
                })
                .catch((error) => {
                    console.log('Error:', error);
                    alert('An error occurred while approving the changes in the student record. ' + error);
                });
        }

        document.querySelector("#approve-data-change-btn").addEventListener("click", (e) => {
            var studentId = e.target.getAttribute("data-student-id");
            approveStudentDataChange(studentId);
        });

        document.forms['cancelRequestForm'].onsubmit = function(event) {
            event.preventDefault();
            if (!confirm("Are you sure to cancel this request?")) {
                return false;
            }

            var formData = new FormData(this);
            fetch(this.action, {
                    method: "POST",
                    body: formData
                }).then(async response => {
                    //
                    if (!response.ok) {
                        // If response code is not in the 200–299 range
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Server returned an error.');
                    }
                    return response.json();
                })
                .then((data) => {
                    alert(data.message);
                    window.location.reload();
                })
                .catch((error) => {
                    console.log('Error:', error);
                    alert('An error occurred while cancelling the request. ' + error);
                });
        };
    </script>
@endsection
