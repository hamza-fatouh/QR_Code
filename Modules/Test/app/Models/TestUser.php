<?php

namespace Modules\Test\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Test\Database\Factories\TestUserFactory;

class TestUser extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'email',
        'qr_code',
        'is_verified',
        'time'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_verified' => 'boolean',
        'time' => 'datetime',
    ];

    // protected static function newFactory(): TestUserFactory
    // {
    //     // return TestUserFactory::new();
    // }
}
