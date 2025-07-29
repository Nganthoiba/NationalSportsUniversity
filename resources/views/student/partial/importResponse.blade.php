@php
    $textColor = $hasExistingData ? 'text-yellow-800' : 'text-green-800';
@endphp
<div class="bg-white {{ $textColor }} p-4 rounded-lg my-4">
    <div class="flex justify-between">
        <h3 class="font-semibold mb-2">
            @if ($hasExistingData)
                Registration Numbers Already Exist.
            @else
                Records Imported Successfully
            @endif
        </h3>
        <button onclick="this.parentElement.parentElement.remove();" class="btn cursor-pointer w-8 h-8"><i
                class="fa fa-times"></i></button>
    </div>
    @if ($hasExistingData)
        <p class="text-sm mb-2">
            The following student records were not imported because some registration numbers already exist in our
            system. Rows highlighted in red indicate the records with duplicate registration numbers that are already
            present in the application.:
        </p>
    @else
        <p>The following records have been imported successfully.</p>
    @endif
    <table class="min-w-full divide-y divide-gray-200 text-xs text-left text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
            <tr>
                <th>#</th>
                <th class="px-4 py-3">Registration No</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Batch</th>
                <th class="px-4 py-3">Year</th>
                <th class="px-4 py-3">Course</th>
                <th class="px-4 py-3">Department</th>
                <th class="px-4 py-3">Sports</th>
                <th class="px-4 py-3">Month</th>
                <th class="px-4 py-3">Gender</th>
                <th class="px-4 py-3">Father Name</th>
                <th class="px-4 py-3">Mother Name</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-xs text-gray-700">
            @foreach ($excelData as $key => $student)
                @php
                    $bgColor = isset($student['exists']) && $student['exists'] == true ? 'bg-red-200' : '';
                @endphp
                <tr class="{{ $bgColor }} hover:bg-gray-50">
                    <td class="px-2">{{ $key + 1 }}</td>
                    <td class="px-4 py-3">{{ $student['registration_no'] }}</td>
                    <td class="px-4 py-3">
                        <div>{{ $student['name_of_students'] }}</div>
                        <div>{{ $student['name_of_students_in_hindi'] }}</div>
                    </td>
                    <td>{{ $student['batch'] }}</td>
                    <td>{{ $student['year'] }}</td>
                    <td>
                        <div>{{ $student['course'] }}</div>
                        <div>{{ $student['course_in_hindi'] }}</div>
                    </td>
                    <td>
                        <div>{{ $student['department'] }}</div>
                        <div>{{ $student['department_in_hindi'] }}</div>
                    </td>
                    <td>
                        <div>{{ $student['sports'] }}</div>
                        <div>{{ $student['sports_in_hindi'] }}</div>
                    </td>
                    <td>
                        <div>{{ $student['month'] }}</div>
                        <div>{{ $student['month_in_hindi'] }}</div>
                    </td>
                    <td>{{ $student['gender'] }}</td>
                    <td>{{ $student['father_name'] }}</td>
                    <td>{{ $student['mother_name'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if ($hasExistingData)
        <p class="text-sm mt-2">Please try with the new registration numbers only.</p>
    @endif
</div>
