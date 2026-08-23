<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class press_releases_folder extends Model
{
  use HasFactory;
  protected $table = "press_releases_folder";
  protected $fillable = [
    'user_id',
    'folder_name',
		'order',
    'type'
  ];
  public $timestamps = false;
}