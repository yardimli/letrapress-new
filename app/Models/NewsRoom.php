<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsRoom extends Model
{
    protected $table = 'news_rooms';

    protected $fillable = ['user_id', 'folder_id', 'subject', 'summary', 'featured_image', 'content', 'order', 'date'];

    public function folder()
    {
        return $this->belongsTo(news_rooms_folder::class, 'folder_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
