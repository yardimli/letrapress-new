<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property int $record_count
 */
class prowly_titles extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['title', 'record_count'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */

    public function get_journalists() {
        return $this->belongsTo(prowly_journalist::class,'title_id','id');
    }


    public $timestamps = false;

}
