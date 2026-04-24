<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'role', 'phone', 'email', 'avatar', 'is_active'];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_teams');
    }
}