<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class StudentsImport implements ToCollection
{
    protected $headers = [];
    public $importedCount = 0;
    public $updatedCount = 0;

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            return;
        }

        // Extract first row as headers
        $headers = array_values(array_filter($rows->first()->toArray(), function ($key) {
            return trim($key) !== '';
        }));

        $this->headers = array_map(fn($key) => $this->sanitizeKey($key), $headers);

        $dataRows = $rows->slice(1); // Remove the first row
        $bulkOperations = [];

        foreach ($dataRows as $row) {
            $sanitizedRow = $this->getKeyValuePair($row);

            // Check if student already exists
            if ($this->isRecordExist($sanitizedRow['registration_no'])) {
                $this->updatedCount++;
            } else {
                $this->importedCount++;
            }

            // Prepare bulk upsert operation
            $bulkOperations[] = [
                'updateOne' => [
                    ['registration_no' => trim($sanitizedRow['registration_no'])], // Find by registration no
                    ['$set' => $sanitizedRow], // Update or insert
                    ['upsert' => true]
                ]
            ];
        }

        // Execute bulk upsert safely
        if (!empty($bulkOperations)) {
            Student::raw()->bulkWrite($bulkOperations);
        }
    }

    private function getKeyValuePair($data)
    {
        $keyValuePair = [];
        foreach ($this->headers as $index => $header) {
            $keyValuePair[$header] = isset($data[$index]) ? $this->sanitizeValue($data[$index]) : '';
        }
        $keyValuePair['status'] = 0; // Default to not status
        return $keyValuePair;
    }

    private function sanitizeKey($key)
    {
        return str_replace('.', '', preg_replace('/\s+/', '_', trim($key)));
    }

    private function sanitizeValue($value)
    {
        return trim($value) === '.' ? '' : trim($value);
    }

    private function isRecordExist($regNo){
        // return true;
        return Student::where('registration_no', $regNo)->exists();
    }
}
