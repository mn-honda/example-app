<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function delivery() {
        return $this->hasOne(Delivery::class);
    }

    public function sale_details() {
        return $this->hasMany(SaleDetail::class);
    }
    protected $casts = [
        'date' => 'datetime:Y-m-d',
    ];
}
