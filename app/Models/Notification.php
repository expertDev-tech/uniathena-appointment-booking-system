<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'type',
        'message',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
