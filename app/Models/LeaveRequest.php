<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $primaryKey = 'leave_id';

    protected $fillable = [
        'lecturer_id', 'leave_type',
        'start_date', 'end_date',
        'reason', 'status'
    ];

    public function lecturer() {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}