<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $outlet_id
 * @property int $topic_id
 */
class prowly_outlet_topic_list extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'prowly_outlet_topic_list';

    /**
     * @var array
     */
    protected $fillable = ['outlet_id', 'topic_id'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}



