<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    public const DEFAULT_IMAGE = "default-logo.png";

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'menu_id',
        'category_id',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
