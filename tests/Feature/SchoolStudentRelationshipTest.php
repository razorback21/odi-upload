<?php

use App\Models\School;
use App\Models\Student;

it('a school can have many students', function () {
    // Create a school
    $school = School::factory()->create(['school_code' => 'TEST001']);

    // Create students for the school
    $student1 = Student::factory()->create(['school_code' => 'TEST001']);
    $student2 = Student::factory()->create(['school_code' => 'TEST001']);

    // Refresh the school to get the latest relationships
    $school->refresh();

    // Assert the relationship works
    expect($school->students)->toHaveCount(2);
    expect($school->students->pluck('student_id'))->toContain($student1->student_id);
    expect($school->students->pluck('student_id'))->toContain($student2->student_id);
});

it('a student belongs to a school', function () {
    // Create a school
    $school = School::factory()->create(['school_code' => 'TEST002']);

    // Create a student for the school
    $student = Student::factory()->create(['school_code' => 'TEST002']);

    // Assert the relationship works
    expect($student->school)->toBeInstanceOf(School::class);
    expect($student->school->school_code)->toBe('TEST002');
});

it('school uses school_code as primary key', function () {
    $school = School::factory()->create(['school_code' => 'TEST003']);

    expect($school->getKey())->toBe('TEST003');
    expect($school->getKeyName())->toBe('school_code');
    expect($school->getKeyType())->toBe('string');
});

it('can create student through school relationship', function () {
    $school = School::factory()->create(['school_code' => 'TEST004']);

    $student = $school->students()->create([
        'student_code' => 'STU00001',
        'first_name' => 'Test Student',
        'date_of_birth' => '2000-01-01',
    ]);

    expect($student->school_code)->toBe('TEST004');
    expect($student->first_name)->toBe('Test Student');
    expect($student->school)->toBeInstanceOf(School::class);
});

it('can query schools with students', function () {
    // Create schools with students
    $schoolWithStudents = School::factory()
        ->has(Student::factory()->count(3))
        ->create();

    $schoolWithoutStudents = School::factory()->create();

    // Test with() eager loading
    $schools = School::with('students')->get();
    $foundSchoolWithStudents = $schools->firstWhere('school_code', $schoolWithStudents->school_code);
    $foundSchoolWithoutStudents = $schools->firstWhere('school_code', $schoolWithoutStudents->school_code);

    expect($foundSchoolWithStudents)->not->toBeNull();
    expect($foundSchoolWithoutStudents)->not->toBeNull();

    // Test has() filtering
    $schoolsWithStudents = School::has('students')->get();
    $foundInHasQuery = $schoolsWithStudents->firstWhere('school_code', $schoolWithStudents->school_code);
    $notFoundInHasQuery = $schoolsWithStudents->firstWhere('school_code', $schoolWithoutStudents->school_code);

    expect($foundInHasQuery)->not->toBeNull();
    expect($notFoundInHasQuery)->toBeNull();
});

it('can query students with schools', function () {
    $school = School::factory()->create(['school_code' => 'TEST005']);
    $student = Student::factory()->create(['school_code' => 'TEST005']);

    // Test with() eager loading
    $students = Student::with('school')->get();
    $loadedStudent = $students->firstWhere('student_id', $student->student_id);

    expect($loadedStudent)->not->toBeNull();
    expect($loadedStudent->relationLoaded('school'))->toBeTrue();
    expect($loadedStudent->school->school_code)->toBe('TEST005');
});

it('can count students per school', function () {
    $school1 = School::factory()
        ->has(Student::factory()->count(5))
        ->create();

    $school2 = School::factory()
        ->has(Student::factory()->count(3))
        ->create();

    $schools = School::withCount('students')->get();

    $school1FromCollection = $schools->firstWhere('school_code', $school1->school_code);
    $school2FromCollection = $schools->firstWhere('school_code', $school2->school_code);

    expect($school1FromCollection->students_count)->toBe(5);
    expect($school2FromCollection->students_count)->toBe(3);
});

it('can update student school', function () {
    $school1 = School::factory()->create(['school_code' => 'TEST006']);
    $school2 = School::factory()->create(['school_code' => 'TEST007']);

    $student = Student::factory()->create(['school_code' => 'TEST006']);

    // Update student's school
    $student->school()->associate($school2);
    $student->save();

    $student->refresh();

    expect($student->school_code)->toBe('TEST007');
    expect($student->school->school_code)->toBe('TEST007');
});
