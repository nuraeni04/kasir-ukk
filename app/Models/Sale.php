<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = "sale";

    protected $fillable = [
        'no_invoice', 
        'member_id', 
        'user_id',
        'total_price', 
        'total_paid', 
        'total_use_points'
    ];


    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function items()
    {
        return $this->hasMany(SaleItems ::class);
    }
}