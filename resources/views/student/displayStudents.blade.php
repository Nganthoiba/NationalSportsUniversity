@extends('layout.app')
@section('content')
    <div class="student-datatable">
        <div class="justify-center">
            @if (Auth::user()->hasPermission(['excel_file_upload', 'student_data_entry']))
                <div class="text-end">

                    <div class="relative inline-block text-left">
                        <div>
                            <button type="button"
                                class="inline-flex justify-center w-full rounded-md border 
                                    border-blue-300 
                                    bg-blue-500 text-sm font-medium text-white hover:bg-blue-700 
                                    cursor-pointer 
                                    focus:outline-none focus:ring-2 
                                    vertical-align-middle
                                    focus:ring-offset-2 focus:ring-indigo-500 py-2 px-4"
                                id="menu-button" aria-expanded="false" aria-haspopup="true">
                                Add New Record
                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <!-- Dropdown menu, show/hide based on menu state. -->
                        <div class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden"
                            id="menu-items">
                            <div class="py-1">
                                <a href="#importStudentLayout" id="importStudentsBtn" data-toggle='alert'
                                    data-target="importStudentsForm"
                                    class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-200 menu-item">Import
                                    students' record from excel</a>
                                <a href="{{ route('addNewStudent') }}"
                                    class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-200 menu-item">Add New
                                    Student Manually</a>

                            </div>
                        </div>
                    </div>

                    <script>
                        // Toggle the visibility of the dropdown menu
                        const button = document.getElementById('menu-button');
                        const menu = document.getElementById('menu-items');
                        const menuItems = document.querySelectorAll("a.menu-item");

                        button.addEventListener('click', () => {
                            menu.classList.toggle('hidden');
                        });

                        menuItems.forEach(element => {
                            element.addEventListener('click', () => {
                                menu.classList.toggle('hidden');
                            });
                        });
                    </script>
                </div>
                {{-- <div class="w-2/3 mx-auto">
                    @include('layout.server_response')
                </div> --}}
                @include('student.partial.importStudent')
            @endif
            <div class="flex justify-between">
                <h6>List of registered students:</h6>
                <div class="hidden">
                    <input type="checkbox" name="editPermission" id="editPermission"
                        {{ Auth::user()->hasPermission('student_data_edit') ? 'checked' : '' }}>
                </div>
            </div>
            <div class="bg-white p-2 shadow-lg rounded-lg">
                <table id="studentTable" data-url="{{ route('api.getStudents') }}"
                    data-viewStudentDetailLink="{{ route('viewStudent', '_student_id_') }}"
                    class="w-full text-xs text-left text-gray-900                    
                     border-gray-300 student-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            {{-- <th>Gender</th> --}}
                            <th>Batch</th>
                            <th>Registration No</th>
                            <th>Month</th>
                            <th class="text-start">Passing Year</th>
                            <th style="max-width:110px;">Course</th>
                            <th>Department</th>
                            <th>Sports</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        <!-- Search input row (just below header) -->
                        <tr class="filter-row">
                            <th><input type="text" disabled class="bg-gray-100 text-xs" style="max-width:10px;" />
                            </th>
                            <th>
                                <input type="text" placeholder="Search Name" class="search-field"
                                    name="search_student_name" style="max-width:130px;" />
                            </th>
                            <th><input type="text" placeholder="Batch" class="search-field text-xs" name="search_batch"
                                    style="max-width: 65px;" />
                            </th>
                            <th><input type="text" placeholder="Reg No" class="search-field text-xs"
                                    name="search_registration_no" style="max-width: 100px;" />
                            </th>
                            <th>
                                @php
                                    $months = App\CustomLibrary\Month::$months;
                                @endphp
                                {{-- <input type="text" placeholder="Month" class="search-field text-xs" name="search_month"
                                     /> --}}
                                <select name="search_month" style="max-width: 90px;" class="search-option text-xs">
                                    <option value="">Select Month</option>
                                    @foreach ($months as $month)
                                        <option value="{{ $month['eng'] }}">
                                            {{ $month['eng'] }} ({{ $month['hindi'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </th>
                            <th><input type="number" placeholder="Year" class="search-field text-xs" name="search_year"
                                    style="max-width: 60px;" />
                            </th>
                            <th>
                                <select name="search_course" class="search-option text-xs" style="max-width:130px;">
                                    <option value="">All Courses</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->course_name }}">
                                            {{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select name="search_department" class="search-option text-xs" style="max-width:120px;">
                                    <option value="">All Departments</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->dept_name }}">
                                            {{ $department->dept_name }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select name="search_sports" class="search-option text-xs" style="max-width:100px;">
                                    <option value="">All Sports</option>
                                    @foreach ($sports as $sport)
                                        <option value="{{ $sport->sport_name }}">
                                            {{ $sport->sport_name }}</option>
                                    @endforeach
                                </select>

                            </th>
                            <th>
                                <select name="search_grade" class="search-option text-xs" style="max-width:60px;">
                                    <option value="">All</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->grade }}">
                                            {{ $grade->grade }}</option>
                                    @endforeach
                                </select>
                            </th>
                            <th>
                                <select class="search-option text-xs" id="search_status" name="search_status"
                                    data-placeholder="Status" style="max-width:100px;">
                                    @php
                                        $variable = [
                                            '' => 'All',
                                            '0' => 'Pending',
                                            '1' => 'Signed',
                                            '2' => 'Data Change Requested',
                                        ];
                                    @endphp
                                    @foreach ($variable as $key => $value)
                                        <option value="{{ $key }}"
                                            @if (strtolower($value) == strtolower($status)) {{ 'selected' }} @endif>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@section('javascripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Initialize DataTable
            $(document).ready(function() {
                var studentTable;
                //Function to call api for deleting a student
                window.deleteStudent = (studentId) => {
                    if (!confirm('Are you sure you want to delete this student?')) {
                        return;
                    }
                    $.ajax({
                        url: `/api/student/delete/${studentId}`,
                        type: 'DELETE',
                        //content type                
                        success: function(response) {
                            alert('Student deleted successfully');
                            studentTable.DataTable().ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            let errorResponse = xhr.responseJSON;
                            if (errorResponse && errorResponse.message) {
                                alert('Error deleting student: ' + errorResponse.message);
                                return;
                            }
                            alert('Error deleting student: ' + xhr.responseText);
                        }
                    });
                };

                studentTable = $('#studentTable');
                loadStudentsInDataTable();

                function loadStudentsInDataTable() {

                    var status = document.querySelector("#search_status").value;

                    studentTable.DataTable({
                        responsive: true,
                        "destroy": true,
                        "stateSave": false,
                        processing: true,
                        serverSide: true,
                        orderCellsTop: true,
                        fixedHeader: true,
                        searching: true,
                        paging: true,
                        ajax: {
                            url: studentTable.data('url'),
                            data: function(d) {
                                d.columns[10]["search"]["value"] = status;
                                d.university_id = "{{ Auth::user()->university_id }}";
                            }
                        },
                        "language": {
                            "emptyTable": "No stundent's record available."
                        },
                        lengthMenu: [
                            [-1, 10, 25, 50, 100],
                            ["All", 10, 25, 50, 100],
                        ],
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'name_of_students',
                                //name: 'name_of_students'
                                render: function(data, type, row) {
                                    return `
                                    <div>${row.name_of_students}</div>
                                    <div>${row.name_of_students_in_hindi}</div>
                                    `;
                                }
                            },
                            /* {
                                data: 'gender',
                                name: 'gender'
                            }, */
                            {
                                data: 'batch',
                                name: 'batch'
                            },
                            {
                                data: 'registration_no',
                                name: 'registration_no'
                            },
                            {
                                data: 'month',
                                name: 'month',
                                render: function(data, type, row) {
                                    return `
                                <div>${row.month}</div>
                                <div>${row.month_in_hindi}</div>
                                `;
                                }
                            },
                            {
                                data: 'year',
                                name: 'year'
                            },
                            {
                                data: 'course',
                                render: function(data, type, row) {
                                    return `
                                <div>${row.course}</div>
                                <div>${row.course_in_hindi}</div>
                                `;
                                }
                            },
                            {
                                data: 'department',
                                render: function(data, type, row) {
                                    return `
                                <div>${row.department}</div>
                                <div>${row.department_in_hindi}</div>
                                `;
                                }
                            },
                            {
                                data: 'sports',
                                render: function(data, type, row) {
                                    return `
                                <div>${row.sports}</div>
                                <div>${row.sports_in_hindi}</div>
                                `;
                                }
                            },
                            {
                                data: 'grade',
                                name: 'grade'
                            },
                            {
                                data: 'status',
                                name: 'status',
                                render: function(data, type, row) {
                                    switch (row.status) {
                                        case 0:
                                            return '<span class="px-2 py-1 bg-blue-500 text-white rounded-md">Pending</span>';
                                        case 1:
                                            return '<span class="px-2 py-1 bg-green-500 text-white rounded-md">Signed</span>';
                                        case 2:
                                            return '<div class="px-2 py-1 bg-yellow-500 text-white rounded-md">Data Change Requested</div>';
                                        default:
                                            /* if (row.status == null || row.status == '') {
                                                return '<span class="px-2 py-1 bg-blue-500 text-white rounded-md">Pending</span>';
                                            } */
                                            return '<span class="px-2 py-1 bg-gray-500 text-white rounded-md">Unknown</span>';
                                    }
                                },
                                searchable: true
                            },
                            //Action for view details
                            {
                                data: 'view_details',
                                render: function(data, type, row) {
                                    //console.log(row);
                                    let viewDetailHyperlink = `<a href="${row.view_details}" 
                                    class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-2 hover:border-transparent rounded">
                                    <i class="fa fa-eye"></i>
                                    </a>`;

                                    //check for edit permission
                                    let editHyperlink = document.getElementById(
                                        "editPermission").checked ? `<a href="${row.edit_student}" 
                                    class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-1 px-2 hover:border-transparent rounded">
                                    <i class="fa fa-edit"></i>
                                    </a>` : '';

                                    let deleteHyperlink = `<a href="javascript:deleteStudent('${row.id}');" 
                                    class="bg-transparent hover:bg-red-500 text-red-700 font-semibold hover:text-white py-1 px-2 hover:border-transparent rounded">
                                    <i class="fa fa-trash"></i> 
                                    </a>`;

                                    if (row.status == 1) {
                                        return `
                                        <div class="flex gap-2">
                                        ${viewDetailHyperlink}
                                        ${editHyperlink}
                                        </div>
                                        `;
                                    } else if (row.status == 2) {
                                        return `
                                        <div class="flex gap-1">
                                        ${viewDetailHyperlink}                                        
                                        </div>
                                        `;
                                    }

                                    return `
                                    <div class="flex gap-1">
                                    ${viewDetailHyperlink}
                                    ${editHyperlink}
                                    </div>
                                    `;
                                },
                                orderable: false,
                                searchable: false
                            }
                        ],
                        initComplete: function() {
                            const api = this.api();

                            // Use .filter-row inputs
                            api.columns().every(function() {
                                const column = this;
                                const input = $('.filter-row th').eq(column.index())
                                    .find('input, select.search-option');

                                input.on('keyup change clear', function() {
                                    if (column.search() !== this.value) {
                                        if (this.name == "search_status") {
                                            status = this.value;
                                        }
                                        column.search(this.value).draw();
                                        // alert(`Searching for: ${this.name}`);
                                    }
                                });
                            });
                        },
                        order: [
                            [1, 'asc']
                        ],
                        pageLength: 10
                    });
                }
            });
        });
    </script>
@endsection
