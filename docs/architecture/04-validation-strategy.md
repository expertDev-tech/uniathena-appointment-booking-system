# Validation Strategy

## Overview

Validation is the first line of defense for the application.

Each API validates incoming requests before executing any business logic.

Validation is handled using Laravel Form Request classes to keep controllers clean and maintainable.

---

# Doctor APIs

## Create Availability

### Endpoint

POST /api/doctors/{doctor}/availabilities

### Validation Rules

| Field | Validation | Reason |
|--------|------------|--------|
| doctor | Must exist | Cannot create availability for a non-existent doctor |
| available_date | Required | Mandatory |
| available_date | Valid date | Prevent invalid dates |
| available_date | Today or future | Past availability is not useful |
| start_time | Required | Mandatory |
| end_time | Required | Mandatory |
| end_time | Greater than start_time | Valid schedule |
| slot_duration | Required | Mandatory |
| slot_duration | Integer | Business requirement |
| slot_duration | Greater than 0 | Prevent invalid duration |
| slot_duration | Minimum 15 minutes | Practical scheduling |
| Total duration | Must be divisible by slot duration | Prevent partial slots |
| Overlapping schedule | Not allowed | Prevent conflicting availability |

---

# Patient APIs

## View Available Slots

### Endpoint

GET /api/doctors/{doctor}/available-slots

### Validation Rules

| Field | Validation |
|--------|------------|
| doctor | Must exist |
| date | Required |
| date | Valid date |

If no availability exists, return

```json
{
    "success": true,
    "data": []
}
```

HTTP Status

```
200 OK
```

Returning an empty list is preferred over a 404 because the doctor exists, but has no available slots.

---

## Book Appointment

### Endpoint

POST /api/appointments

### Validation Rules

| Field | Validation | Reason |
|--------|------------|--------|
| patient_id | Must exist | Valid patient |
| availability_slot_id | Must exist | Valid slot |
| Availability Slot | Must belong to an existing availability | Prevent invalid booking |
| Availability Slot | Must not already be booked | Prevent duplicate booking |

Business validation inside the Service Layer:

- Slot must still be available.
- Booking is executed inside a database transaction.
- Row locking prevents concurrent bookings.

---

## Cancel Appointment

### Endpoint

PATCH /api/appointments/{appointment}/cancel

### Validation Rules

| Field | Validation |
|--------|------------|
| Appointment | Must exist |
| Status | Must be BOOKED |
| Reason | Required |

---

## Reschedule Appointment

### Endpoint

PATCH /api/appointments/{appointment}/reschedule

### Validation Rules

| Field | Validation |
|--------|------------|
| Appointment | Must exist |
| Appointment | Must be BOOKED |
| availability_slot_id | Required |
| availability_slot_id | Must exist |
| New Slot | Must be available |

Business validation:

- Old appointment becomes CANCELLED.
- New appointment is created using the selected slot.
- Notification is generated after successful rescheduling.

---

# Validation Principles

- Validation is handled by Form Request classes.
- Controllers never perform validation.
- Business rules are implemented in the Service Layer.
- Validation errors return HTTP 422.