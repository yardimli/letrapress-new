<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $language
 * @property int $record_count
 */
class prowly_languages extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['language', 'record_count'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
