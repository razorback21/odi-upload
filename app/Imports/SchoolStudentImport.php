<?php

namespace App\Imports;

use App\Models\School;
use App\Models\Student;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SchoolStudentImport implements ShouldQueue, ToCollection, WithChunkReading, WithHeadingRow
{
    protected array $schoolBuffer = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $schoolCode = $row['school_code'];

            // Buffer check to avoid duplicate queries
            if (! isset($this->schoolBuffer[$schoolCode])) {
                $school = School::firstOrCreate(
                    ['school_code' => $schoolCode],
                    ['school_name' => $row['school_name']]
                );

                $this->schoolBuffer[$schoolCode] = $school->school_code;
            }

            // Save student data with correct column names matching the database schema
            Student::create(
                [
                    'student_code' => $row['student_code'],
                    'student_id' => $row['student_id'],
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'date_of_birth' => $row['date_of_birth'],
                    'school_code' => $this->schoolBuffer[$schoolCode], // Use school_code, not school_id
                ]
            );
        }
    }

    // Process 1000 rows at a time
    public function chunkSize(): int
    {
        // TODO: Move this to the config file.
        return 1000;
    }
}
