<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $social_link
 * @property string $social_type
 * @property int $journalist_id
 */
class prowly_social_medias extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['social_link', 'social_type', 'journalist_id'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
