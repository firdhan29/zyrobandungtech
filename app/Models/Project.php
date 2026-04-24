<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'name', 'description', 'client_name', 'client_phone', 'client_email',
        'total_amount', 'dp_amount', 'remaining_amount', 'start_date', 
        'end_date', 'status', 'progress_notes', 'progress_percentage',
        'whatsapp_link'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_amount' => 'decimal:2',
        'dp_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'project_teams');
    }

    public function getRemainingPercentageAttribute()
    {
        return 100 - $this->progress_percentage;
    }
}