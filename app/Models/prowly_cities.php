<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $city
 * @property string $country
 * @property int $record_count
 * @property int $country_id
 */
class prowly_cities extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['city', 'country', 'record_count','country_id'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */

    public function get_journalists() {
        return $this->belongsTo(prowly_journalist::class,'city_id','id');
    }


    public $timestamps = false;

}
