<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PressReleaseRecipient extends Model
{
    protected $fillable = ['press_release_id', 'recipient_type', 'recipient_id', 'source', 'match_score', 'match_reason'];
}
