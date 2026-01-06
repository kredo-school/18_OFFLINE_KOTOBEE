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

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'group_members',
            'group_id',
            'user_id',
        )
        ->withPivot(['status', 'created_at'])
        ->withTimestamps();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // public function members()
    // {
    //     return $this->hasMany(GroupMember::class);
    // }


}
