<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'time' => 'date'
    ];

    public function images()
    {
        return $this->hasMany(OrderFile::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function order_logs()
    {
        return $this->hasMany(OrderLog::class);
    }

    public function driver_logs()
    {
        return $this->hasMany(DriverLog::class);
    }
}
