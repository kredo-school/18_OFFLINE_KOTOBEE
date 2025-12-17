<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'note',
        'owner_id',
        'secret',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
