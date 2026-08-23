<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $country
 * @property int $record_count
 */
class prowly_countries extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['country', 'record_count'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
