<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\File\FileResource;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = $file->hashName();
            $path = $file->storeAs('files', $name);
            $file = File::create([
                'path' => $path,
                'url' => config('app.url') . '/api/' . $path
            ]);
            return new FileResource($file);
        }
    }

    public function getFile(Request $request, $fileName)
    {
        return response(Storage::get('files/' . $fileName))
            ->header('Content-Type', Storage::mimeType('files/' . $fileName));
    }
}
