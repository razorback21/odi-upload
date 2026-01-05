<?php

require_once __DIR__.'/../vendor/autoload.php';

use App\Models\School;
use App\Models\Student;

// This is a demonstration script showing how to use the School-Student relationships
// Run with: php examples/school-student-demo.php

echo "=== School-Student Relationship Demo ===\n\n";

// Create a school
echo "1. Creating a school...\n";
$school = School::create([
    'school_code' => 'DEMO001',
    'school_name' => 'Demo High School',
]);
echo "Created school: {$school->school_name} ({$school->school_code})\n\n";

// Create students for the school
echo "2. Creating students for the school...\n";
$student1 = $school->students()->create([
    'student_code' => 'DEMO001',
    'first_name' => 'Alice Johnson',
    'date_of_birth' => '2000-05-15',
]);

$student2 = $school->students()->create([
    'student_code' => 'DEMO002',
    'first_name' => 'Bob Smith',
    'date_of_birth' => '2001-03-22',
]);

echo "Created students: {$student1->first_name} and {$student2->first_name}\n\n";

// Demonstrate relationships
echo "3. Demonstrating relationships:\n";

// Get all students for a school
echo "Students for {$school->school_name}:\n";
foreach ($school->students as $student) {
    echo "- {$student->first_name} ({$student->student_code})\n";
}
echo "\n";

// Get the school for a student
echo "School for {$student1->first_name}:\n";
echo "- {$student1->school->school_name} ({$student1->school->school_code})\n\n";

// Advanced queries
echo "4. Advanced relationship queries:\n";

// Count students per school
$schools = School::withCount('students')->get();
echo "Student counts:\n";
foreach ($schools as $school) {
    echo "- {$school->school_name}: {$school->students_count} students\n";
}
echo "\n";

// Eager loading example
echo "5. Eager loading example:\n";
$students = Student::with('school')->limit(2)->get();
foreach ($students as $student) {
    echo "- {$student->first_name} attends {$student->school->school_name}\n";
}

echo "\n=== Demo Complete ===\n";
