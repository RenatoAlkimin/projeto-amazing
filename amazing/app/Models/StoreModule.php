<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreModule extends Model
{
    protected $fillable = [
        'store_id',
        'module_key',
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
}
