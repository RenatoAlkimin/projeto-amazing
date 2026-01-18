<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreEntitlement extends Model
{
    protected $fillable = [
        'store_id',
        'permission_id',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
