<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    public const DEFAULT_LOGO = "default-logo.png";
    protected $fillable = [
        'name',
        'logo',
        'address',
        'phone',
        'slug',
        'expires_at',
        'owner_id',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(RestaurantSubscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(RestaurantSubscription::class)
            ->where('is_active', true)
            ->where('ends_at', '>=', now());
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
