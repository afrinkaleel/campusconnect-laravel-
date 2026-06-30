<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SupervisionRequest extends Model
{
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'project_id', 'lecturer_id', 'status'
    ];

    public function project() {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function lecturer() {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}