<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $topic
 * @property int $record_count
 */
class prowly_topics extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['topic', 'record_count'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
