<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'folder_id'
    ];

    public function children()
    {
        return $this->hasMany(Folder::class, 'folder_id')->with('children');
    }
}
