<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $outlet_type
 * @property int $record_count
 */
class prowly_outlet_types extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['outlet_type', 'record_count'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
