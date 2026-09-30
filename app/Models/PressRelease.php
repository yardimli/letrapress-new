<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PressRelease extends Model
{
    protected $table = 'press_releases';

    protected $fillable = ['user_id', 'folder_id', 'subject', 'content', 'release_type', 'distribution', 'status', 'template_key', 'analysis', 'recipient_count', 'targeted_at', 'news_room_id'];

    protected $casts = [
        'analysis' => 'array',
        'targeted_at' => 'datetime',
    ];

    public function folder()
    {
        return $this->belongsTo(press_releases_folder::class, 'folder_id');
    }

    public function newsroom()
    {
        return $this->belongsTo(NewsRoom::class, 'news_room_id');
    }
}
