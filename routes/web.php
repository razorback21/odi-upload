<?php

use App\Http\Controllers\UploadController;
use App\Models\School;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('upload');
})->name('home');

Route::post('/upload', UploadController::class)->name('import');

Route::get('/test', function () {
    $schools = School::with('students')->find('SCH-001');

    return $schools->toArray();
})->name('test');
