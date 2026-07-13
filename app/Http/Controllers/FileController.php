<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use ZipArchive;
use File;

class FileController extends Controller
{
    public function downloadFiles(Request $request)
    {
        $filePaths = $request->input('filePaths', []);
        if (empty($filePaths)) {
            return response()->json(['message' => 'No files selected.'], 400);
        }

        try {
            File::makeDirectory(storage_path('app/public/tobedownload'), $mode = 0777);
        } catch (\Exception $e) {
        } // ignore file exist exception.

        $file_name = "Certificates-" . date("Y-m-d_H-i-s") . ".zip";

        $zip_file = storage_path('app/public/tobedownload/'.$file_name);
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        // Add each file to the zip archive
        $pathsArray = json_decode($filePaths);
        foreach ($pathsArray as $filePath) {
            $actualFile = storage_path('app/public/' . $filePath);
            $customFilePath = str_replace("pdf/", "", $filePath);
            $zip->addFile($actualFile, $customFilePath);
        }

        $zip->close();
        $headers = array('Content-Type' => mime_content_type($zip_file));

        // Send the zip file as a response
        return response()->download($zip_file, $file_name,$headers)->deleteFileAfterSend(true);
    }
}
