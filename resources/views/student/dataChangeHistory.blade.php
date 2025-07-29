@extends('layout.app')
@section('css')
    <style>
        .status {
            border-radius: 9px;
            font-size: 0.76rem;
            color: #FFFFFF;
            padding: 4px 8px;
        }

        .approved {
            background-color: #0cb306;
            color: #FFFFFF;
        }

        .pending {
            background-color: #5533cf;
        }

        .cancelled {
            background-color: #fc8c8c;
        }
    </style>
@endsection
@section('content')
    <!-- Displaying courses ($courses) in a table -->
    <div class="container mx-auto">
        <h5 class="text-2xl font-semibold mb-2">Data Change History</h5>
        <div class="min-w-full bg-white shadow-md rounded-lg overflow-auto px-4 py-2">
            <table class="table_style" id="dataChangeHistoryTable">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 text-xs{{--  leading-normal --}}">
                        <th class="py-3 px-6 text-left">#</th>
                        <th class="py-3 px-6 text-left">Regd. No.</th>
                        <th class="py-3 px-6 text-left">Date of request</th>
                        <th class="py-3 px-6 text-left">Requested By</th>
                        <th class="py-3 px-6 text-left">Approved By</th>
                        <th class="py-3 px-6 text-left">Date of Review</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($dataChanges as $key => $dataChange)
                        <tr>
                            <td>{{ $key + 1 }}.</td>
                            <td>{{ $dataChange->registration_no }}</td>
                            <td>{{ date('d M, Y', strtotime($dataChange->date_of_request)) }}</td>
                            <td>{{ $dataChange->requesterName }}</td>
                            <td>{{ $dataChange->reviewerName }}</td>
                            <td>
                                @if (!is_null($dataChange->date_of_review))
                                    {{ date('d M, Y', strtotime($dataChange->date_of_review)) }}
                                @endif
                            </td>
                            <td><span class="status {{ $dataChange->status }}">{{ $dataChange->status }}</span></td>
                            <td>
                                <a href="{{ route('viewDataChangeDetail', $dataChange->id) }}" class="btn btn-info">View
                                    detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

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
        document.addEventListener('DOMContentLoaded', function() {
            $("#dataChangeHistoryTable").DataTable();
        });
    </script>
@endsection
