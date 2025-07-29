@extends('layout.app')
@section('content')
    <div class="container">
        <div class="max-w-5xl p-8 bg-white shadow-lg rounded-lg overflow-x-auto mx-auto">

            <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <h6 class="text-lg font-semibold text-gray-800">
                    Request for Data Change — <span class="text-indigo-600">{{ $dataChange->registration_no }}</span>
                </h6>
                <a href="{{ route('getDataChangeHistories', $dataChange->old_student_data['id']) }}"
                    class="btn btn-info mt-3 sm:mt-0">
                    View Overall Change History <i class="fa fa-arrow-right ml-1"></i>
                </a>
            </div>

            <table class="w-full text-sm text-left text-gray-700 border">
                <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-4 py-2 border">Fields</th>
                        <th class="px-4 py-2 border">Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($fields as $field => $fieldData)
                        @if (str_contains($field, 'in_hindi'))
                            @continue
                        @endif
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border font-medium">
                                {{ $fieldData['DisplayAs'] }}
                                @if (!empty($fieldData['isGroup']))
                                    @foreach ($fieldData['subFields'] as $subField => $subfieldData)
                                        <div class="text-xs text-gray-500">
                                            ({{ $subfieldData['DisplayAs'] }})
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                            <td class="px-4 py-2 border">
                                @if (isset($dataChange->records_to_be_changed[$field]))
                                    @php
                                        $className = isset($dataChange->old_student_data[$field])
                                            ? 'text-red-600 line-through'
                                            : 'text-gray-500';
                                    @endphp
                                    <div class="relative group inline-block">
                                        <span
                                            class="{{ $className }} text-xs">{{ $dataChange->old_student_data[$field] ?? '--No previous data--' }}
                                        </span>
                                        <div
                                            class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-800 text-white text-sm rounded px-2 py-1 whitespace-nowrap z-10">
                                            Old data.
                                        </div>
                                    </div>
                                    <div class="relative group inline-block">
                                        <span
                                            class="text-green-700 font-semibold ml-2 inline-flex items-center px-2 py-0.5 rounded bg-green-100 text-xs">
                                            {{ $dataChange->records_to_be_changed[$field] ?? '' }}
                                        </span>
                                        <div
                                            class="absolute bottom-full mb-2 hidden group-hover:block bg-green-800 text-white text-sm rounded px-2 py-1 whitespace-nowrap z-10">
                                            New data.
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-700">{{ $dataChange->old_student_data[$field] ?? '' }}</span>
                                @endif

                                {{-- SubFields if present --}}
                                @if (!empty($fieldData['isGroup']))
                                    @foreach ($fieldData['subFields'] as $subField => $subfieldData)
                                        <div>
                                            @if (isset($dataChange->records_to_be_changed[$subField]))
                                                @php
                                                    $className = isset($dataChange->old_student_data[$subField])
                                                        ? 'text-red-600 line-through'
                                                        : 'text-gray-500';
                                                @endphp



                                                <div class="relative group inline-block">
                                                    <span
                                                        class="{{ $className }}">{{ $dataChange->old_student_data[$subField] ?? '' }}</span>
                                                    <div
                                                        class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-800 text-white text-sm rounded px-2 py-1 whitespace-nowrap z-10">
                                                        Old data.
                                                    </div>
                                                </div>

                                                <div class="relative group inline-block">
                                                    <span
                                                        class="text-green-700 font-semibold ml-2 inline-flex items-center px-2 py-0.5 rounded bg-green-100 text-xs">
                                                        {{ $dataChange->records_to_be_changed[$subField] ?? '' }}
                                                    </span>
                                                    <div
                                                        class="absolute bottom-full mb-2 hidden group-hover:block bg-green-800 text-white text-sm rounded px-2 py-1 whitespace-nowrap z-10">
                                                        New data.
                                                    </div>
                                                </div>
                                            @else
                                                <span
                                                    class="text-gray-700">{{ $dataChange->old_student_data[$subField] ?? '' }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="px-4 py-2 border text-gray-800 font-semibold">Reason for change:</td>
                        <td class="px-4 py-2 border font-medium text-gray-600">{{ $dataChange->reason_of_change }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-3 border rounded bg-gray-50">
                    <h5 class="text-gray-500">Request made by:</h5>
                    <div class="text-sm font-medium">{{ $dataChange->requesterName }}</div>
                    <div class="text-xs text-gray-500">
                        {{ date('d M, Y | h:i A', strtotime($dataChange->date_of_request)) }}
                    </div>
                </div>

                <div class="p-3 border rounded bg-gray-50">
                    <h5 class="text-gray-500">Reviewed by:</h5>
                    <div class="text-sm font-medium">{{ $dataChange->reviewerName }}</div>
                    <div class="text-sm">
                        <strong>Status:</strong>
                        @php
                            $statusColor = [
                                'approved' => 'text-green-600',
                                'pending' => 'text-blue-600',
                                'cancelled' => 'text-red-600',
                            ];
                        @endphp
                        <span class="font-semibold {{ $statusColor[$dataChange->status] ?? 'text-gray-600' }}">
                            {{ ucfirst($dataChange->status) }}
                        </span>
                    </div>

                    @if ($dataChange->status == 'cancelled')
                        <div class="mt-2 bg-red-100 border border-red-300 text-red-800 text-sm p-2 rounded">
                            <strong>Reason:</strong> {{ $dataChange->reason_if_cancelled }}
                        </div>
                    @endif

                    <div class="text-xs text-gray-500 mt-1">
                        @if (!is_null($dataChange->date_of_review))
                            {{ date('d M, Y | h:i A', strtotime($dataChange->date_of_review)) }}
                        @endif
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('displayDataChange') }}"
                        class="btn btn-primary w-full sm:w-auto inline-flex items-center justify-center px-4 py-2">
                        <i class="fa fa-arrow-left mr-2"></i> Back to list
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
