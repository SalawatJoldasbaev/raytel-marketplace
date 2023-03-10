<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\File\FileResource;

class FileController extends Controller
{
    public function upload(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $final = [];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            foreach ($file as $item) {
                $name = date('Y_m_d').'_'.\Str::random(32).'.'.$item->getClientOriginalExtension();
                $path = $item->storeAs('files', $name);
                $file = File::create([
                    'path' => $path,
                    'url' => config('app.url') . '/api/' . $path
                ]);
                $final[] = $file;
            }

            return FileResource::collection($final);
        }
    }

    public function getFile(Request $request, $fileName): \Illuminate\Http\Response
    {
        return response(Storage::get('files/' . $fileName))
            ->header('Content-Type', Storage::mimeType('files/' . $fileName));
    }
}
