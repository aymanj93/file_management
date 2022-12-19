<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttachmentRequest;
use App\Http\Requests\UpdateAttachmentRequest;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function index(int $id) {
        $attachments = Attachment::where('folder_id', $id)->get();
        return AttachmentResource::collection($attachments);
    }

    public function store(int $id, StoreAttachmentRequest $request){

        if (isset($request->validator) && $request->validator->fails())
        {
            return response()->json([
                'message' => 'Invalid format',
                'errors' => $request->validator->messages(),
            ],422);
        }

        $file = $request->file('file');

        $fileContent = file_get_contents($request['file']);
        $path = $request->get('name').'_'.time().'.'.$file->getClientOriginalExtension();
        $fileSize = number_format($file->getSize() / 1048576,2);

        Storage::disk('public')->put($path, $fileContent);

        $attachment = Attachment::create([
            'folder_id' => $id,
            'name' => $request->get('name'),
            'path' => $path,
            'size' => $fileSize.' MB'
        ]);

        return new AttachmentResource($attachment);
    }

    public function download(int $id)
    {
        $attachment = Attachment::findOrFail($id);
        return Storage::disk('public')->download($attachment->path);
    }
}
