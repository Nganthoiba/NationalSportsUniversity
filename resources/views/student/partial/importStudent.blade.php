<!-- Showing those student data which has not been imported yet, because registration numbers already exist in database -->
@php
    $hasExistingData = session('existingData') && count(session('existingData')) > 0;
    $excelData = session('excelData');
@endphp
@includeWhen(isset($excelData), 'student.partial.importResponse', [
    'excelData' => $excelData ?? [],
    'hasExistingData' => $hasExistingData,
])
<div id="importStudentLayout">
    {{-- <div class="w-2/3 mx-auto">
        @include('layout.server_response')
    </div> --}}
    <!-- Form for importing students -->
    <form name="importStudentsForm" id="importStudentsForm" action="{{ route('excel.importStudents') }}" method="POST"
        class="alert-box" enctype="multipart/form-data">
        @csrf
        <div class="bg-white shadow-md rounded px-8 py-3 my-2">
            <div class="text-end">
                <button type="button" class="btn cursor-pointer w-8 h-8 close-alert"><i
                        class="fa fa-times"></i></button>
            </div>

            <div class="flex">
                <div class="w-1/3">
                    <h2 class="text-lg font-bold mb-4">Import Students</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Please upload a valid excel file to
                        import
                        students' record. And make sure that the excel file has proper heading fields.</p>

                    <label for="studentExcelFile" class="text-sm font-medium text-gray-900 dark:text-gray-300">Upload
                        Excel
                        File</label>
                    <input type="file" name="excel_file" id="studentExcelFile" accept=".xls,.xlsx"
                        class="border border-gray-300 p-2 rounded-lg relative w-full mt-2 cursor-pointer" required>

                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Supported formats: .xls, .xlsx</p>

                    <div class="error">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        @endif
                    </div>

                    <div class="flex justify-between mt-4">
                        <a href="javascript:openModal();" id="preview_excel_link"
                            class="text-sm text-blue-500 hover:underline hover:text-purple-600 hidden">
                            Preview excel
                        </a>
                        <button type="submit" id="excelSubmitBtn"
                            class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4
                    focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600
                    dark:hover:bg-purple-700 dark:focus:ring-purple-900 cursor-pointer disabled:bg-purple-500 disabled:cursor-not-allowed"
                            disabled="true">Submit</button>
                    </div>
                </div>
                <div class="w-2/3 p-4" {{-- hidden --}} id="field_matching">
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Please make sure thet the excel file has the
                        following required
                        header names:</p>
                    <!-- In table -->
                    <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-700 mt-2">
                        <thead class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                            <tr>
                                {{-- <th class="px-4 py-3">Field Name</th> --}}
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">Required header name</th>
                                <th>Matching field found in excel?</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php
                                $cnt = 1;
                            @endphp
                            @foreach ($compulsoryFields as $field => $fieldData)
                                <tr class="hover:bg-gray-50">
                                    {{-- <td class="px-4 py-3">{{ $fieldData['DisplayAs'] }}</td> --}}
                                    <td class="px-4 py-1">{{ $cnt }}.</td>
                                    <td class="px-4 py-1">{{ $field }}</td>
                                    <td class="px-4 py-1 matching-field"></td>
                                </tr>
                                @php
                                    $cnt++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
    <!-- Modal backdrop and box -->
    <div id="myModal"
        class="fixed inset-0 hidden items-center justify-center z-50 transition-opacity duration-300 p-6"
        style="background-color: rgba(152, 167, 152, 0.3);">
        <div id="modalBox"
            class="relative bg-gray-100 rounded-lg p-4 w-[90%] max-w-[1200px] shadow-lg transform scale-95 opacity-0 transition-all duration-300 modal-body">
            <!-- Close buttons -->
            <button onclick="closeModal()"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-2xl cursor-pointer">
                &times;
            </button>

            <div style="margin-top: 5px; margin-bottom:5px;display:flex; justify-content:space-between">
                <h5 class="font-semibold ">Preview your excel content before upload</h5>
                <div id="sheetNames" style="padding-right:50px; margin-right:50px;"></div>
            </div>

            <!-- Your Excel table or preview component goes here -->
            <div class="overflow-auto h-100 bg-gray-50 rounded">
                <div id="excelPreview" class="w-full"></div>
            </div>

            {{-- 
            <div class="mt-4 text-right">
                <button onclick="closeModal()" class="bg-blue-500 text-white px-4 py-2 rounded cursor-pointer">
                    Close
                </button>
            </div> --}}

        </div>
    </div>
</div>

{{-- @section('javascripts') --}}
<script src="{{ asset('js/excel/xlsx.full.min.js') }}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script> --}}

<script>
    /* 
    const importStudentsBtn = document.getElementById('importStudentsBtn');
    importStudentsBtn.addEventListener('click', function() {
        //document.forms['importStudentsForm'].classList.remove('hidden');
        //document.getElementById('field_matching').classList.remove('hidden');
        //scroll to import layout
        document.getElementById("importStudentLayout").scrollIntoView(true);
    }); */

    function openModal() {
        const modal = document.getElementById("myModal");
        const box = document.getElementById("modalBox");

        modal.classList.remove("hidden");
        modal.classList.add("flex");

        setTimeout(() => {
            box.classList.remove("opacity-0", "scale-95");
            box.classList.add("opacity-100", "scale-100");
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById("myModal");
        const box = document.getElementById("modalBox");

        box.classList.remove("opacity-100", "scale-100");
        box.classList.add("opacity-0", "scale-95");

        setTimeout(() => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }, 300); // duration matches CSS transition
    }
    document.addEventListener('DOMContentLoaded', function() {

        document.getElementById('studentExcelFile').addEventListener('click', function(e) {
            e.target.value = null;
            document.getElementById("excelSubmitBtn").disabled = true;
        });

        document.getElementById('studentExcelFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) {
                document.getElementById("preview_excel_link").classList.add("hidden");
                //document.getElementById("field_matching").classList.add("hidden");
                document.getElementById("excelSubmitBtn").disabled = true;
                return;
            }

            document.getElementById("excelSubmitBtn").disabled = false;

            // Check file extension
            const allowedExtensions = ['.xls', '.xlsx'];
            const fileName = file.name.toLowerCase();
            const isExcel = allowedExtensions.some(ext => fileName.endsWith(ext));

            if (!isExcel) {
                alert("Please upload a valid Excel file (.xls or .xlsx).");
                e.target.value = ""; // Clear file input
                document.getElementById("preview_excel_link").classList.add("hidden");
                //document.getElementById("field_matching").classList.add("hidden");
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });

                // Show sheet names as clickable options
                const sheetDiv = document.getElementById('sheetNames');
                sheetDiv.classList.add("flex");
                sheetDiv.innerHTML = '<div class="mx-2"><strong>Sheets:</strong></div>';
                workbook.SheetNames.forEach((sheetName, index) => {
                    const btn = document.createElement('button');
                    btn.textContent = sheetName;
                    btn.classList.add("btn", "worksheet-btn", "text-sm");
                    if (index == 0) {
                        btn.classList.add("active");
                    }
                    btn.onclick = (e) => {
                        //previous active button
                        let prevActiveBtn = document.querySelector(
                            "button.worksheet-btn.active");
                        if (prevActiveBtn) {
                            prevActiveBtn.classList.remove("active");
                        }
                        e.target.classList.add("active");
                        renderSheet(workbook.Sheets[sheetName]);
                    };
                    sheetDiv.appendChild(btn);
                });

                // Auto render first sheet
                renderSheet(workbook.Sheets[workbook.SheetNames[0]]);
                document.getElementById("preview_excel_link").classList.remove("hidden");
                //document.getElementById("field_matching").classList.remove("hidden");
            };

            reader.readAsArrayBuffer(file);
        });
    });

    function renderSheet(sheet) {

        //Here get all the headers of the excel sheet and match them with the compulsory fields
        const headers = XLSX.utils.sheet_to_json(sheet, {
            header: 1,
            range: 0
        })[0];
        const fieldMatching = document.querySelectorAll('.matching-field');

        //I am going to place select-options for users to select the matching field
        fieldMatching.forEach((cell, index) => {
            cell.innerHTML = ''; // Clear previous content
            const fieldName = Object.keys(@json($compulsoryFields))[index];
            // create a hidden input field to store the field name
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `matching_fields[${fieldName}]`;
            hiddenInput.classList.add('matched-input');
            hiddenInput.required = true;

            headers.forEach(header => {
                //header should be converted to lowercase and trimmed
                header = header.toLowerCase().trim();
                if (header === fieldName) {
                    // If the header matches the field name, set the hidden input value
                    hiddenInput.value = header; // Set the value to the field name
                }
            });
            cell.appendChild(hiddenInput);
            const fieldNameDisplay = document.createElement('div');

            if (hiddenInput.value.trim() === '') {
                // If no match found, display a cross mark
                fieldNameDisplay.innerHTML = `<span class="text-red-500">
                        <i class="fa-solid fa-xmark"></i>
                        </span>`;
                hiddenInput.classList.add('no-matched');
            } else {
                // If a match is found, display a check mark
                fieldNameDisplay.innerHTML = `<span class="text-green-500">
                        <i class="fa-solid fa-check"></i>
                        </span>`;
                hiddenInput.classList.add('matched');
            }
            cell.appendChild(fieldNameDisplay);

            /* 
            const select = document.createElement('select');
            select.name = `matching_fields[${fieldName}]`;
            //select.name = `${fieldName}`;
            select.setAttribute('data-field-name', fieldName);
            select.classList.add('form-select', 'w-full', 'matched-field');
            select.required = true;
            select.innerHTML = '<option value="">Select Matching Field</option>';

            // Add an option for each header
            headers.forEach(header => {
                const option = document.createElement('option');
                //option.value = header;
                //header should be converted to lowercase and trimmed
                header = header.toLowerCase().trim();
                option.textContent = header;
                select.appendChild(option);

                //Set selected if fieldName matches the header in excel 
                if (header === fieldName) {
                    option.selected = true;
                }
            });

            cell.innerHTML = ''; // Clear previous content

            //check if select has value
            if (select.value) {
                // If the select already has a value, add a class to indicate it has been matched
                cell.appendChild(select);
                select.disabled = true; // Make it read-only if already matched
                //select.classList.add('bg-gray-200', 'cursor-not-allowed');
            } else {
                cell.innerHTML = '<span class="text-red-500">Matching field not found</span>';
            }

            select.addEventListener('change', function(e) {
                // Update the field matching display


                const selectedValue = this.value;
                //here set a class "selected" to the option that is selected
                //if (selectedValue) {
                //get the option element that is selected
                const selectedOption = Array.from(this.options).find(option => option.value ===
                    selectedValue);
                if (selectedOption) {
                    selectedOption.classList.add("selected");
                }

                //Now disable all other options other select elements whose options having the same value as the selected option
                const allSelects = document.querySelectorAll('.matched-field');
                allSelects.forEach(otherSelect => {
                    if (otherSelect !== this || selectedValue.trim() === '') {
                        // Disable the option in other selects which do not have the class "selected"                                
                        Array.from(otherSelect.options).forEach(option => {
                            if (hasFieldBeenTaken(option.value)) {
                                //option.disabled = true; // Disable the option in other selects
                                option.classList.add("disabled");
                            } else {
                                //option.disabled = false; // Enable other options    
                                option.classList.remove("disabled");
                            }
                        });
                    }
                });

                //}

            }); */
        });

        const html = XLSX.utils.sheet_to_html(sheet, {
            id: "excel-table",
        });

        const excelPreview = document.getElementById('excelPreview');
        excelPreview.innerHTML = html;
    }

    // Fuction to check if the particular field has been taken particularly for a field or not
    function hasFieldBeenTaken(fieldName) {
        //Getting all the matched fields
        const matchedFields = document.querySelectorAll('.matched-field');
        let fieldsTaken = [];
        matchedFields.forEach(select => {
            if (select.value) {
                fieldsTaken.push(select.value);
            }
        });

        //Now check if the fieldName is in the fieldsTaken array
        if (fieldsTaken.includes(fieldName)) {
            return true; // Field has been taken
        }
        return false; // Field is available            
    }

    document.forms['importStudentsForm'].onsubmit = function(event) {
        event.preventDefault();

        if (!confirm("Are all the matching fields correct? Are you sure to submit?")) {
            return;
        }

        // check if all the fields are matched
        const unmatchedInputs = document.querySelectorAll('.no-matched');
        if (unmatchedInputs.length > 0) {
            alert(
                "The excel file you choose does not have the exact required headings, please make sure to do so before submitting."
            );
            return;
        }
        this.submit();
    };
</script>
{{-- @endsection --}}
