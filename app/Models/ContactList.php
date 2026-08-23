<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactList extends Model
{
    protected $table = 'contact_list';

    protected $fillable = ['user_id', 'name', 'description'];

    public function journalists()
    {
        return $this->belongsToMany(Journalist::class, 'contact_list_journalist_ref', 'contact_list_id', 'journalist_id');
    }

    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'contact_list_outlet_ref', 'contact_list_id', 'outlet_id');
    }
}
