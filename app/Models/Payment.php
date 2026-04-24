<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'payment_type', 'amount', 'payment_date', 
        'notes', 'proof_image', 'status'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'confirmed' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }
}