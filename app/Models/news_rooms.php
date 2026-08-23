<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class news_rooms extends Model
{
  use HasFactory;
  protected $table = "news_rooms";
  protected $fillable = [
    'user_id',
    'folder_id',
    'subject',
    'summary',
    'featured_image',
    'content',
    'order',
    'date'
  ];

  public function news_rooms_folders() {
    return $this->hasOne(news_rooms_folder::class,'id','folder_id');
  }

  public function news_rooms_labels() {
    return $this->belongsToMany(news_rooms_label::class,'news_rooms_label_ref','news_rooms_id','news_rooms_label_id');
  }

}
