<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ResourceBooking extends Model
{
    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'resource_id', 'user_id', 'project_id',
        'booking_date', 'time_slot', 'status'
    ];

    public function resource() {
        return $this->belongsTo(Resource::class, 'resource_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function project() {
        return $this->belongsTo(Project::class, 'project_id');
    }
}