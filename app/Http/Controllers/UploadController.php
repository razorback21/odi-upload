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

        try {
            Excel::queueImport(new SchoolStudentImport, request()->file('file'));

            return back()->with('success', 'Upload successful. Your file is now being imported, this may take a few moments.');

        } catch (\Exception $e) {
            \Log::error('Upload failed: '.$e->getMessage());

            return back()->with('error', 'Upload failed. Please try again later.');
        }
    }
}
