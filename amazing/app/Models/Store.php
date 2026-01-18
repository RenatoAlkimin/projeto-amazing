<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'scope_slug',
        'name',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class)
            ->withPivot(['role_id', 'status'])
            ->withTimestamps();
    }
}
