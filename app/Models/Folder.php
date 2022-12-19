<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'folder_id'
    ];

    public function children()
    {
        return $this->hasMany(Folder::class, 'folder_id')->with('children');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
