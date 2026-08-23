<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property boolean $waiting_for_update
 * @property int $city_id
 * @property string $journalist_name
 * @property string $first_name
 * @property string $last_name
 * @property int $title_id
 * @property int $influence_score
 * @property int $media_type_id
 * @property string $email
 * @property string $phone
 * @property string $journalist_picture_url
 * @property int $country_id
 * @property string $state
 * @property string $prowly_id
 * @property string $outlet_name
 * @property string $outlet_id
 * @property string $update_time
 */
class prowly_journalist extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'waiting_for_update', 'city_id', 'journalist_name', 'first_name', 'last_name', 'title_id', 'influence_score', 'media_type_id', 'email', 'phone', 'journalist_picture_url', 'country_id', 'state', 'prowly_id', 'outlet_name', 'outlet_id', 'update_time'];

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
        return $this->belongsToMany(prowly_topics::class,'prowly_topic_list','journalist_id','topic_id');
    }

    public function j_languages() {
        return $this->belongsToMany(prowly_languages::class,'prowly_language_list','journalist_id','language_id');
    }

    public function j_social_medias() {
        return $this->hasMany(prowly_social_medias::class,'journalist_id','id');
    }

    public function j_contact_lists() {
        return $this->belongsToMany(contact_list::class,'contact_list_journalist_ref','journalist_id','contact_list_id');
    }

}
