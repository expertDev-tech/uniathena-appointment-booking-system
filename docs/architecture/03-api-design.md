# API Design

## Overview

The system exposes RESTful JSON APIs for doctors and patients.

Doctors manage availability.

Patients view available slots, book appointments, cancel appointments and reschedule appointments.

---

# Doctor APIs

---

## Create Availability

### Endpoint

POST /api/doctors/{doctor}/availabilities

### Description

Allows a doctor to create an availability schedule.

### Request

```json
{
    "available_date": "2026-07-10",
    "start_time": "09:00",
    "end_time": "12:00",
    "slot_duration": 15
}
```

### Success Response

HTTP Status

```
201 Created
```

```json
{
    "success": true,
    "message": "Availability created successfully."
}
```

---

# Patient APIs

---

## View Available Slots

### Endpoint

GET /api/doctors/{doctor}/available-slots?date=2026-07-10

### Description

Returns all available slots for a doctor on a selected date.

### Success Response

```json
[
    {
        "slot_id": 18,
        "start_time": "09:00",
        "end_time": "09:15"
    },
    {
        "slot_id": 19,
        "start_time": "09:15",
        "end_time": "09:30"
    }
]
```

---

## Book Appointment

### Endpoint

POST /api/appointments

### Description

Books an available slot for a patient.

### Request

```json
{
    "patient_id": 5,
    "availability_slot_id": 18
}
```

### Success Response

```json
{
    "success": true,
    "message": "Appointment booked successfully.",
    "data": {
        "reference_number": "APT-20260710-000001"
    }
}
```

---

## Cancel Appointment

### Endpoint

PATCH /api/appointments/{appointment}/cancel

### Description

Cancels an existing appointment.

### Request

```json
{
    "reason": "Medical emergency"
}
```

### Success Response

```json
{
    "success": true,
    "message": "Appointment cancelled successfully."
}
```

---

## Reschedule Appointment

### Endpoint

PATCH /api/appointments/{appointment}/reschedule

### Description

Moves an appointment to another available slot.

### Request

```json
{
    "availability_slot_id": 30
}
```

### Success Response

```json
{
    "success": true,
    "message": "Appointment rescheduled successfully."
}
```

---

# Response Format

Successful responses follow a consistent JSON structure.

```json
{
    "success": true,
    "message": "Operation completed successfully.",
    "data": {}
}
```

Validation errors return HTTP 422.

Resource not found returns HTTP 404.

Booking conflicts (slot already booked) return HTTP 409.

Unexpected server errors return HTTP 500.

---

# API Design Decisions

- APIs follow REST principles.
- Doctors can only manage availability.
- Patients can only view slots and manage appointments.
- Appointment booking uses `availability_slot_id` instead of manually passing doctor details.
- All responses are returned in JSON format.
- Business logic is implemented in the Service Layer, keeping controllers lightweight.
