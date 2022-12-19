<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'folder_id',
        'path',
        'size'
    ];

    public function folder() {
        return $this->belongsTo(Folder::class);
    }
}
