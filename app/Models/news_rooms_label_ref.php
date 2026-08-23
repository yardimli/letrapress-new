<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class news_rooms_label_ref extends Model
{
  use HasFactory;
  protected $table = "news_rooms_label_ref";
  protected $fillable = [
    'news_rooms_id',
    'news_rooms_label_id'
  ];
  public $timestamps = false;
}