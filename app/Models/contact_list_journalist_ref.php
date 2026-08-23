<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $contact_list_id
 * @property int $journalist_id
 */
class contact_list_journalist_ref extends Model
{
  use HasFactory;

  /**
   * @var array
   */
  protected $fillable = ['contact_list_id', 'journalist_id'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
}