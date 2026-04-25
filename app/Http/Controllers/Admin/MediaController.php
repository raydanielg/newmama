<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::latest();
        if ($request->has('folder')) {
            $query->where('folder', $request->folder);
        }
        $media = $query->get();
        $folders = Media::distinct()->pluck('folder');

        if ($request->ajax()) {
            return response()->json([
                'media' => $media,
                'folders' => $folders
            ]);
        }

        return view('admin.media.index', compact('media', 'folders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|image|max:10240', // 10MB limit
            'folder' => 'nullable|string|max:50'
        ]);

        $uploadedMedia = [];
        $folder = $request->input('folder', 'uploads');

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;
                
                $path = $file->storeAs('public/' . $folder, $filename);
                $url = Storage::url($folder . '/' . $filename);

                $item = Media::create([
                    'filename' => $filename,
                    'original_name' => $originalName,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'url' => $url,
                    'folder' => $folder,
                ]);

                $uploadedMedia[] = $item;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Files uploaded successfully',
            'media' => $uploadedMedia
        ]);
    }

    public function destroy(Media $media)
    {
        $path = str_replace('/storage/', 'public/', $media->url);
        Storage::delete($path);
        $media->delete();

        return response()->json(['success' => true]);
    }
}
