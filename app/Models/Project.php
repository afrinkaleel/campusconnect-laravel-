<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $primaryKey = 'project_id';

    protected $fillable = [
        'title', 'description', 'student_id',
        'supervisor_id', 'temp_supervisor_id', 'status'
    ];

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function supervisor() {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
    public function tempSupervisor() {
        return $this->belongsTo(User::class, 'temp_supervisor_id');
    }
    public function updates() {
        return $this->hasMany(ProjectUpdate::class, 'project_id');
    }
    public function bookings() {
        return $this->hasMany(ResourceBooking::class, 'project_id');
    }
}