<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttachmentRequest;
use App\Http\Requests\UpdateAttachmentRequest;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;

class AttachmentController extends Controller
{
    public function index(int $id) {
        $attachments = Attachment::where('folder_id', $id)->get();
        return AttachmentResource::collection($attachments);
    }

    public function store(int $id, StoreAttachmentRequest $request){
        $request->validated();

        $attachment = Attachment::create([
            'folder_id' => $id,
            'name' => $request->get('name'),
            'path' => $request->get('path'),
        ]);

        return response()->json($attachment, 201);
    }
}
