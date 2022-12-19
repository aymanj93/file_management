<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\UpdateFolderRequest;
use App\Http\Resources\FolderResource;
use App\Models\Folder;

class FolderController extends Controller
{

    public function index()
    {
        $folders = Folder::whereNull('folder_id')
            ->with('children')
            ->select('id','name')
            ->orderBy('name', 'asc')
            ->get();

        return FolderResource::collection($folders);
    }


    public function store(StoreFolderRequest $request)
    {
        $request->validated();

        $folder = Folder::create($request->toArray());

        return response()->json($folder,201);
    }


    public function addFolder(int $id, StoreFolderRequest $request)
    {
        $request ->validated();

        $folder = Folder::create([
            'name' => $request->get('name'),
            'folder_id' => $id
        ]);

        return response()->json($folder,201);
    }
}
