<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'appointment_id' => $this->id,

            'reference_number' => $this->reference_number,

            'patient_id' => $this->patient_id,

            'availability_slot_id' => $this->availability_slot_id,

            'status' => $this->status,

            'created_at' => $this->created_at,
        ];
    }
}
