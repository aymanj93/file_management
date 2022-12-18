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
            ->orderBy('name', 'asc')
            ->get();

        return FolderResource::collection($folders);
    }


    public function store(StoreFolderRequest $request)
    {
        $request->validated();

        $folder = Folder::create([
            'name' => $request->get('name')
        ]);

        return response()->json($folder,201);
    }


    public function createFolder(int $id,StoreFolderRequest $request)
    {
        $request ->validated();

        $folder = Folder::create([
            'name' => $request->get('name'),
            'folder_id' => $id
        ]);

        return response()->json($folder,201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function edit(Folder $folder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFolderRequest  $request
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFolderRequest $request, Folder $folder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folder $folder)
    {
        //
    }
}
