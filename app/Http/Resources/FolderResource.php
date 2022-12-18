<?php

namespace App\Http\Resources;

use App\Models\Folder;
use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'children' => FolderResource::collection($this->whenLoaded('children')),
        ];
    }
}
