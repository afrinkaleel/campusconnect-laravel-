<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'user_type',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function projects() {
        return $this->hasMany(Project::class, 'student_id');
    }

    public function supervisedProjects() {
        return $this->hasMany(Project::class, 'supervisor_id');
    }

    public function leaveRequests() {
        return $this->hasMany(LeaveRequest::class, 'lecturer_id');
    }

    public function userNotifications() {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function resourceBookings() {
        return $this->hasMany(ResourceBooking::class, 'user_id');
    }
}