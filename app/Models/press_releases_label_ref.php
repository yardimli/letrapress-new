<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class press_releases_label_ref extends Model
{
  use HasFactory;
  protected $table = "press_releases_label_ref";
  protected $fillable = [
    'press_releases_id',
    'press_releases_label_id'
  ];
  public $timestamps = false;
}