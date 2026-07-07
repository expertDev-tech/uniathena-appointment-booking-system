<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AvailabilitySlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'availability_id',
        'start_time',
        'end_time',
    ];

    public function availability()
    {
        return $this->belongsTo(
            DoctorAvailability::class,
            'availability_id'
        );
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }
}
