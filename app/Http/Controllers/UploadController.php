<?php

namespace App\Http\Controllers;

use App\Imports\SchoolStudentImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv']);

        Excel::queueImport(new SchoolStudentImport, request()->file('file'));

        return back()->with('success', 'Upload successful. Your file is being processed, this may take a few moments.');
    }
}
