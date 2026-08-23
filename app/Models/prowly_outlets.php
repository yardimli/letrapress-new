<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property boolean $waiting_for_update
 * @property int $city_id
 * @property string $outlet_name
 * @property int $influence_score
 * @property int $media_type_id
 * @property string $outlet_url
 * @property string $outlet_picture_url
 * @property string $email
 * @property string $phone
 * @property int $country_id
 * @property string $state
 * @property string $prowly_id
 * @property string $update_time
 */
class prowly_outlets extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'waiting_for_update', 'city_id', 'outlet_name', 'influence_score', 'media_type_id', 'outlet_url', 'outlet_picture_url', 'email', 'phone', 'country_id', 'state', 'prowly_id', 'update_time'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    public function j_title() {
        return $this->hasOne(prowly_titles::class,'id','title_id');
    }

    public function j_cities() {
        return $this->hasOne(prowly_cities::class,'id','city_id');
    }

    public function j_countries() {
        return $this->hasOne(prowly_countries::class,'id','country_id');
    }

    public function j_media_types() {
        return $this->hasOne(prowly_outlet_types::class,'id','media_type_id');
    }

    public function j_topics() {
        return $this->belongsToMany(prowly_topics::class,'prowly_outlet_topic_list','outlet_id','topic_id');
    }

    public function j_languages() {
        return $this->belongsToMany(prowly_languages::class,'prowly_outlet_language_list','outlet_id','language_id');
    }

    public function j_social_medias() {
        return $this->hasMany(prowly_outlet_social_medias::class,'outlet_id','id');
    }

    public function j_contact_lists() {
        return $this->belongsToMany(contact_list::class,'contact_list_outlet_ref','outlet_id','contact_list_id');
    }

}
