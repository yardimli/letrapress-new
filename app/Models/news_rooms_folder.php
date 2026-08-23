<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class news_rooms_folder extends Model
{
  use HasFactory;
  protected $table = "news_rooms_folder";
  protected $fillable = [
    'user_id',
    'folder_name',
    'order',
    'type'
  ];
  public $timestamps = false;
}