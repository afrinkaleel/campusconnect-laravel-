<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProjectUpdate extends Model
{
    protected $primaryKey = 'update_id';

    protected $fillable = ['project_id', 'update_text'];

    public function project() {
        return $this->belongsTo(Project::class, 'project_id');
    }
}