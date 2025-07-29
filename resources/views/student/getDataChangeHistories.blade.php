@extends('layout.app')

@section('css')
    <style>
        table.data-change-history-table {
            /* width: 100% !important; */
            border-collapse: collapse;
            border: 1px solid #ddd !important;
            overflow-x: auto;
            background-color: #ffffff;
            margin: auto;
        }

        table.data-change-history-table thead>tr th:nth-child(even) {
            background-color: #e1f3b5;
        }

        table.data-change-history-table tbody>tr td:nth-child(even) {
            background-color: #f1f8df;
        }

        table.data-change-history-table tfoot>tr td:nth-child(even) {
            background-color: #d3e6a3;
        }

        /** odd columns **/
        table.data-change-history-table thead>tr th:nth-child(odd):not(:first-child) {
            background-color: #eae0ef;
        }

        table.data-change-history-table tbody>tr td:nth-child(odd):not(:first-child) {
            background-color: #fcf7fb;
        }

        table.data-change-history-table tfoot>tr td:nth-child(odd):not(:first-child) {
            background-color: #e2d0eb;
        }

        table.data-change-history-table th,
        table.data-change-history-table td {
            padding: 4px 6px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            /* by default */
        }

        table.data-change-history-table td {
            font-size: 0.77rem !important;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="flex justify-between">
            <h1 class="text-lg font-bold mb-4">Data Change Histories for student bearing Regd. No.
                '{{ $student->registration_no }}'</h1>
            <div>
                <button class="btn btn-primary" onclick="window.history.back();"><i class="fa fa-arrow-left"></i> Back</button>
            </div>
        </div>


        @if ($dataChanges->isEmpty())
            <p class="text-gray-500">No data change histories found.</p>
        @else
            @php
                $oldStudentData = $dataChanges->first()->old_student_data;
            @endphp
            <div class="w-full bg-white shadow-md rounded-lg overflow-x-auto ">
                {{-- table_style --}}
                <table class="w-full data-change-history-table">
                    <thead>
                        <tr>
                            <th class="text-lg font-bold bg-blue-100">Fields</th>
                            {{-- <th class="text-lg font-bold">Old Data</th> --}}
                            @foreach ($dataChanges as $dataChange)
                                <th class="text-lg font-bold">
                                    {{ date('d M, Y', strtotime($dataChange->created_at)) }}
                                    <div class="text-xs font-light">Requested by {{ $dataChange->requesterName }}</div>
                                </th>
                            @endforeach
                            {{-- 
                            @if ($dataChanges->last()->status === 'approved')
                                <th class="text-lg font-bold current-data">
                                    Current Data
                                </th>
                            @endif --}}
                        </tr>
                    </thead>
                    <tbody class="p-4">

                        @foreach ($compulsoryFields as $field => $value)
                            {{-- Check if $field contains substring 'in_hindi', if so then skip to the next iteration  --}}
                            @if (str_contains($field, 'in_hindi'))
                                @continue
                            @endif
                            <tr>
                                <td class="font-bold text-gray-500 px-6 bg-blue-50">{{ $value['DisplayAs'] }}</td>
                                @foreach ($dataChanges as $dataChange)
                                    <td>
                                        @if (isset($dataChange->records_to_be_changed[$field]))
                                            <span class="strikethrough">
                                                {{ $dataChange->old_student_data[$field] ?? '' }}
                                            </span>&nbsp;
                                            <span class="new-data">{{ $dataChange->records_to_be_changed[$field] ?? '' }}
                                            </span>
                                        @else
                                            <span class="text-gray-700">{{ $dataChange->old_student_data[$field] ?? '' }}
                                            </span>
                                        @endif

                                        @if (isset($value['isGroup']) && $value['isGroup'])
                                            @foreach ($value['subFields'] as $subField => $subfieldData)
                                                @if (isset($dataChange->records_to_be_changed[$subField]))
                                                    <div>
                                                        <span class="strikethrough">
                                                            {{ $dataChange->old_student_data[$subField] ?? '' }}
                                                        </span>&nbsp;
                                                        <span
                                                            class="new-data">{{ $dataChange->records_to_be_changed[$subField] ?? '' }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="text-gray-500">
                                                        {{ $dataChange->old_student_data[$subField] ?? '' }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                @endforeach
                                {{-- 
                                @if ($dataChanges->last()->status === 'approved')
                                    <!-- Latest student data -->
                                    <td class="text-sm current-data">
                                        @if (isset($student->{$field}))
                                            <span class="text-gray-700">{{ $student->{$field} }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                @endif --}}
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            @foreach ($dataChanges as $dataChange)
                                <td>
                                    <div>
                                        Reviewed By:
                                        <span
                                            class="font-bold">{{ $dataChange->reviewerName ?? 'Not yet reviewed' }}</span>
                                    </div>
                                    <div>
                                        <strong>Status:</strong>
                                        @if ($dataChange->status === 'approved')
                                            <span class="text-green-600 font-bold">Approved</span>
                                        @elseif ($dataChange->status === 'cancelled')
                                            <span class="text-red-600 font-bold">Cancelled</span>
                                            <div>
                                                <strong>Reason for cancellation:</strong>
                                                <span>{{ $dataChange->reason_if_cancelled ?? '' }}</span>
                                            </div>
                                        @else
                                            <span class="text-yellow-600 font-bold">Pending</span>
                                        @endif
                                    </div>
                                    <div>
                                        @if (isset($dataChange->date_of_review))
                                            {{ date('d M, Y', strtotime($dataChange->date_of_review)) }}
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
@endsection
