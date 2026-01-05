<?php

/**
 * Generate CSV with 1000+ student records
 * Run with: php generate_students_csv.php
 */

// Configuration
$totalRecords = 1500;
$outputFile = "students_import_{$totalRecords}_records.csv";

// Schools data
$schools = [
    ['code' => 'SCH-001', 'name' => 'Sorsogon National High School'],
    ['code' => 'SCH-002', 'name' => 'Bulan Science High School'],
    ['code' => 'SCH-003', 'name' => 'Gubat Central School'],
    ['code' => 'SCH-004', 'name' => 'Irosin National High School'],
    ['code' => 'SCH-005', 'name' => 'Barcelona Central School'],
    ['code' => 'SCH-006', 'name' => 'Casiguran National High School'],
    ['code' => 'SCH-007', 'name' => 'Castilla Science High School'],
    ['code' => 'SCH-008', 'name' => 'Donsol National High School'],
    ['code' => 'SCH-009', 'name' => 'Magallanes Central School'],
    ['code' => 'SCH-010', 'name' => 'Matnog National High School'],
];

// Filipino first names
$firstNames = [
    'Juan', 'Maria', 'Pedro', 'Ana', 'Carlo', 'Liza', 'Jose', 'Rosa',
    'Miguel', 'Carmen', 'Luis', 'Elena', 'Ramon', 'Sofia', 'Diego', 'Isabel',
    'Antonio', 'Patricia', 'Manuel', 'Luz', 'Ricardo', 'Cristina', 'Fernando', 'Angela',
    'Roberto', 'Teresa', 'Eduardo', 'Monica', 'Carlos', 'Diana', 'Rafael', 'Gloria',
    'Javier', 'Beatriz', 'Alberto', 'Margarita', 'Jorge', 'Laura', 'Alejandro', 'Sara',
    'Francisco', 'Andrea', 'Daniel', 'Clara', 'Gabriel', 'Lucia', 'Enrique', 'Adriana',
    'Pablo', 'Valeria', 'Raul', 'Natalia', 'Sergio', 'Carolina', 'Oscar', 'Victoria',
    'Felipe', 'Fernanda', 'Andres', 'Gabriela', 'Marcos', 'Melissa', 'Victor', 'Daniela',
];

// Filipino last names
$lastNames = [
    'Dela Cruz', 'Santos', 'Reyes', 'Lopez', 'Mendoza', 'Ramos', 'Garcia', 'Torres',
    'Gonzales', 'Flores', 'Rivera', 'Cruz', 'Bautista', 'Fernandez', 'Villanueva', 'Castro',
    'Martinez', 'Rodriguez', 'Aquino', 'Sanchez', 'Hernandez', 'Diaz', 'Morales', 'Ramirez',
    'Perez', 'Navarro', 'Jimenez', 'Vargas', 'Castillo', 'Herrera', 'Medina', 'Aguilar',
    'Gutierrez', 'Chavez', 'Rojas', 'Mendez', 'Ortiz', 'Salazar', 'Velasco', 'Pascual',
    'Valencia', 'Santiago', 'Mercado', 'Luna', 'Manalo', 'Abad', 'Campos', 'Cortez',
];

// Open CSV file for writing
$file = fopen($outputFile, 'w');

// Write header
fputcsv($file, [
    'student_id',
    'student_code',
    'first_name',
    'last_name',
    'date_of_birth',
    'school_code',
    'school_name',
]);

// Generate records
for ($i = 1; $i <= $totalRecords; $i++) {
    // Student ID starts from 10001
    $studentId = 10000 + $i;

    // Student code format: STU-001, STU-002, etc. (5 digits for 10500 records)
    $studentCode = 'STU-'.str_pad($i, 5, '0', STR_PAD_LEFT);

    // Random name
    $firstName = $firstNames[array_rand($firstNames)];
    $lastName = $lastNames[array_rand($lastNames)];

    // Random date of birth (between 2005 and 2010)
    $year = rand(2005, 2010);
    $month = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
    $day = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
    $dateOfBirth = "$year-$month-$day";

    // Random school
    $school = $schools[array_rand($schools)];

    // Write row
    fputcsv($file, [
        $studentId,
        $studentCode,
        $firstName,
        $lastName,
        $dateOfBirth,
        $school['code'],
        $school['name'],
    ]);
}

fclose($file);

echo "✅ Successfully generated $totalRecords records!\n";
echo "📄 File: $outputFile\n";
echo '📊 File size: '.number_format(filesize($outputFile) / 1024, 2)." KB\n";

// Display sample records
echo "\n--- Sample Records (First 10) ---\n";
$file = fopen($outputFile, 'r');
$header = fgetcsv($file);
echo implode(' | ', $header)."\n";
echo str_repeat('-', 120)."\n";

for ($i = 0; $i < 10; $i++) {
    $row = fgetcsv($file);
    if ($row) {
        echo implode(' | ', $row)."\n";
    }
}
fclose($file);

echo "\n--- Statistics ---\n";
$file = fopen($outputFile, 'r');
fgetcsv($file); // Skip header

$schoolCounts = [];
while ($row = fgetcsv($file)) {
    $schoolCode = $row[5];
    if (! isset($schoolCounts[$schoolCode])) {
        $schoolCounts[$schoolCode] = 0;
    }
    $schoolCounts[$schoolCode]++;
}
fclose($file);

echo "\nStudents per school:\n";
foreach ($schoolCounts as $code => $count) {
    $schoolName = '';
    foreach ($schools as $school) {
        if ($school['code'] === $code) {
            $schoolName = $school['name'];
            break;
        }
    }
    echo "  $code ($schoolName): $count students\n";
}

echo "\n✨ CSV file is ready for import!\n";
