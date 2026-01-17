<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    public const ADMIN = 1;
    public const OWNER = 2;
    public const STAFF = 3;

    protected $fillable = [
        "name",
    ];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
