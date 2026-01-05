# School-Student Relationship Documentation

This document explains how to use the one-to-many relationship between Schools and Students in this Laravel application.

## Relationship Overview

-   **School** has many **Students** (One-to-Many)
-   **Student** belongs to **School** (Many-to-One)

## Database Structure

### Schools Table

-   Primary Key: `school_code` (string)
-   Fields: `school_code`, `school_name`, `created_at`, `updated_at`

### Students Table

-   Primary Key: `student_id` (integer)
-   Foreign Key: `school_code` (references schools.school_code)
-   Fields: `student_id`, `student_code`, `first_name`, `date_of_birth`, `school_code`, `created_at`, `updated_at`

## Model Relationships

### School Model

```php
// Get all students for a school
$school = School::find('SCH0001');
$students = $school->students; // Returns Collection of Student models

// Check if school has students
if ($school->students->count() > 0) {
    // School has students
}

// Get students count
$studentCount = $school->students()->count();
```

### Student Model

```php
// Get the school for a student
$student = Student::find(1);
$school = $student->school; // Returns School model

// Check if student has a school
if ($student->school) {
    // Student belongs to a school
}

// Get school name
$schoolName = $student->school->school_name;
```

## Query Examples

### Basic Queries

```php
// Get all schools with their students
$schools = School::with('students')->get();

// Get all students with their schools
$students = Student::with('school')->get();

// Find a school and eager load students
$school = School::with('students')->find('SCH0001');

// Find a student and eager load school
$student = Student::with('school')->find(1);
```

### Advanced Queries

```php
// Get schools that have students
$schoolsWithStudents = School::has('students')->get();

// Get schools with more than 5 students
$schoolsWithManyStudents = School::has('students', '>', 5)->get();

// Get students from a specific school
$studentsFromSchool = Student::where('school_code', 'SCH0001')->get();

// Get students and order by school name
$students = Student::with('school')->get()->sortBy('school.school_name');

// Count students per school
$schools = School::withCount('students')->get();
foreach ($schools as $school) {
    echo $school->school_name . ': ' . $school->students_count . ' students';
}
```

## Creating Records

### Creating a School

```php
$school = School::create([
    'school_code' => 'SCH0001',
    'school_name' => 'Lincoln High School'
]);
```

### Creating a Student

```php
// Method 1: Direct assignment
$student = Student::create([
    'student_code' => 'STU00001',
    'first_name' => 'John Doe',
    'date_of_birth' => '2000-01-01',
    'school_code' => 'SCH0001'
]);

// Method 2: Using relationship
$school = School::find('SCH0001');
$student = $school->students()->create([
    'student_code' => 'STU00002',
    'first_name' => 'Jane Smith',
    'date_of_birth' => '2001-05-15'
]);
```

## Updating Records

### Updating a Student's School

```php
$student = Student::find(1);
$student->school_code = 'SCH0002';
$student->save();
```

### Using Relationship

```php
$student = Student::find(1);
$newSchool = School::find('SCH0002');
$student->school()->associate($newSchool);
$student->save();
```

## Deleting Records

### Deleting a Student

```php
$student = Student::find(1);
$student->delete();
```

### Deleting a School (and optionally its students)

```php
$school = School::find('SCH0001');

// Option 1: Delete school and its students (if foreign key allows)
$school->delete();

// Option 2: Delete students first, then school
$school->students()->delete();
$school->delete();
```

## Factory Usage

### Creating Test Data

```php
// Create a school
$school = School::factory()->create();

// Create a student with associated school
$student = Student::factory()->create();

// Create multiple schools with students
$schools = School::factory()
    ->count(5)
    ->has(Student::factory()->count(10))
    ->create();

// Create students for existing school
$school = School::find('SCH0001');
$school->students()->createMany(
    Student::factory()->count(5)->make()->toArray()
);
```

## Common Pitfalls

1. **Primary Key Type**: Remember that School uses `school_code` (string) as primary key, not the default `id`.

2. **Foreign Key**: The foreign key in students table is `school_code`, not `school_id`.

3. **Eager Loading**: Always use `with()` when accessing relationships in loops to avoid N+1 queries.

4. **Null Relationships**: Always check if the relationship exists before accessing properties:
    ```php
    if ($student->school) {
        echo $student->school->school_name;
    }
    ```

## Performance Tips

1. Use eager loading (`with()`) when you know you'll need related data
2. Use `withCount()` when you only need the count of related records
3. Consider adding database indexes on `school_code` in both tables for better performance
4. Use `has()` and `whereHas()` for filtering based on relationship existence
