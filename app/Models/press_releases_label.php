<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class press_releases_label extends Model
{
  use HasFactory;
  protected $table = "press_releases_label";
  protected $fillable = [
    'user_id',
    'label_name',
    'label_color'
  ];
  public $timestamps = false;
}