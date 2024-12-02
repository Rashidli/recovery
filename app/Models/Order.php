<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function images()
    {
        return $this->hasMany(OrderFile::class);
    }

    public function order_logs()
    {
        return $this->hasMany(OrderLog::class);
    }

    public function driver_logs()
    {
        return $this->hasMany(DriverLog::class);
    }

    /**
     * Sifariş yaradılan tarixdən 45 dəqiqə keçib və driver təyin olunmayıbsa true qaytarır.
     *
     * @return bool
     */
    public function getIsPendingDriverAssignmentAttribute()
    {
        if (!$this->created_at) {
            return false;
        }

        $createdAt = Carbon::parse($this->created_at);

        // Əgər status canceled və ya reached_cancelled deyilsə
        if (!$this->statusNotCancelled()) {
            return false;
        }

        // Əgər driver_logs boşdursa və sifarişin yaradılma tarixindən 45 dəqiqə keçibsə
        if (!$this->driver_logs()->exists() && $createdAt->diffInMinutes(Carbon::now()) > 45) {
            return true;
        }

        // Əgər driver_logs boş deyil və onun ilk elementində logged_at ilə yaradılma tarixi arasında 45 dəqiqə fərq varsa
        $firstLog = $this->driver_logs()->first();
        if ($firstLog) {
            $loggedAt = Carbon::parse($firstLog->logged_at); // logged_at tarixini Carbon obyektinə çeviririk
            if ($createdAt->diffInMinutes($loggedAt) > 45) {
                return true;
            }
        }

        return false;
    }

    /**
     * Statusun canceled və ya reached_cancelled olmamasını yoxlayır.
     *
     * @return bool
     */
    private function statusNotCancelled()
    {
        return !in_array($this->status, ['canceled', 'reached_cancelled']);
    }

}
