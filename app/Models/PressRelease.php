<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PressRelease extends Model
{
    protected $table = 'press_releases';

    protected $fillable = ['user_id', 'folder_id', 'subject', 'content'];

    public function folder()
    {
        return $this->belongsTo(press_releases_folder::class, 'folder_id');
    }
}
