<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use ZipArchive;
use Illuminate\Support\Facades\File;

class VideoController extends Controller
{

    public function uploadZip(Request $request)
    {
        $request->validate([
            'zipfile' => 'required|mimes:zip'
        ]);

        $zip = new ZipArchive;
        $file = $request->file('zipfile');

        $zipName = time() . '.zip';
        $zipPath = public_path('uploads/zips');

        if (!file_exists($zipPath)) {
            mkdir($zipPath, 0777, true);
        }

        $file->move($zipPath, $zipName);

        $extractPath = public_path('uploads');

        if ($zip->open($zipPath . '/' . $zipName) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
        }

        // scan extracted files
        $files = File::files($extractPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'mp4') {

                $filename = $file->getFilename();

                // extract number for sorting (1.mp4, 2.mp4)
                preg_match('/\d+/', $filename, $match);
                $order = isset($match[0]) ? (int)$match[0] : 0;

                \App\Models\Video::create([
                    'title' => $filename,
                    'filename' => $filename,
                    'sort_order' => $order
                ]);
            }
        }

        return redirect()->back()->with('success', 'ZIP uploaded and playlist created!');
    }
    public function index()
    {
        return Video::orderBy('sort_order')->get();
    }

    public function store(Request $request)
    {
        $file = $request->file('video');
        $name = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads'), $name);

        $video = Video::create([
            'title' => $request->title,
            'filename' => $name,
            'sort_order' => $request->sort_order ?? 0
        ]);

        return redirect()->back()->with('success', 'Video successfully uploaded!');
    }

    public function destroy($id)
    {
        $video = Video::findOrFail($id);

        // delete file from storage
        $filePath = public_path('uploads/' . $video->filename);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // delete database record
        $video->delete();

        return response()->json([
            'success' => true,
            'message' => 'Video deleted successfully'
        ]);
    }
}
