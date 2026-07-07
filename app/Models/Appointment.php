<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'availability_slot_id',
        'cancel_reason',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function slot()
    {
        return $this->belongsTo(
            AvailabilitySlot::class,
            'availability_slot_id'
        );
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
