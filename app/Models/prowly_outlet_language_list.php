<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $outlet_id
 * @property int $language_id
 */
class prowly_outlet_language_list extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prowly_outlet_language_list';

    /**
     * @var array
     */
    protected $fillable = ['outlet_id', 'language_id'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
