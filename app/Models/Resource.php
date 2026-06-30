<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $primaryKey = 'resource_id';

    protected $fillable = [
        'name', 'quantity_total',
        'quantity_available', 'location'
    ];

    public function bookings() {
        return $this->hasMany(ResourceBooking::class, 'resource_id');
    }
}