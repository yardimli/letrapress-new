<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class press_releases extends Model
{
  use HasFactory;
  protected $table = "press_releases";
  protected $fillable = [
    'user_id',
    'folder_id',
    'subject',
    'content'
  ];

  public function press_releases_folders() {
    return $this->hasOne(press_releases_folder::class,'id','folder_id');
  }

  public function press_releases_labels() {
    return $this->belongsToMany(press_releases_label::class,'press_releases_label_ref','press_releases_id','press_releases_label_id');
  }

}
