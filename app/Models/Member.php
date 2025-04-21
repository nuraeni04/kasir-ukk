<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = "member";

    protected $fillable = [
        'id',
        'name',
        'phone_number',
        'poin',
    ];

    public function sale()
    {
        return $this->hasMany(Sale::class);
    }
}