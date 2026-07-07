# Postman API Testing Guide

## Base URL

```
http://127.0.0.1:8000/api
```

Add the following header to every request.

| Key | Value |
|-----|-------|
| Accept | application/json |

---

# Testing Order

Follow the APIs in the order below.

---

# 1. Create Doctor Availability

**POST**

```
/doctors/{doctor}/availabilities
```

Request

```json
{
    "available_date": "2026-07-20",
    "start_time": "09:00",
    "end_time": "12:00",
    "slot_duration": 30
}
```

Expected Result

- Doctor availability created
- Appointment slots generated automatically

---

# 2. Get Available Slots

**GET**

```
/doctors/{doctor}/available-slots?date=2026-07-20
```

Example

```
/doctors/1/available-slots?date=2026-07-20
```

Expected Result

Returns all available appointment slots for the selected doctor.

---

# 3. Book Appointment

**POST**

```
/appointments
```

Request

```json
{
    "patient_id": 1,
    "availability_slot_id": 5
}
```

Expected Result

- Appointment created
- Unique reference number generated
- Notification record created
- Email simulated using Laravel Queue

---

# 4. Cancel Appointment

**PATCH**

```
/appointments/{appointment}/cancel
```

Example

```
/appointments/1/cancel
```

Request

```json
{
    "cancel_reason": "Personal reason"
}
```

Expected Result

- Appointment cancelled
- Slot becomes available again
- Notification created
- Email simulated

---

# 5. Reschedule Appointment

**PATCH**

```
/appointments/{appointment}/reschedule
```

Example

```
/appointments/1/reschedule
```

Request

```json
{
    "availability_slot_id": 8
}
```

Expected Result

- Appointment moved to new slot
- Old slot becomes available
- Notification created
- Email simulated

---

# Queue Worker

Before testing notifications, start the queue worker.

```bash
php artisan queue:work
```

---

# Expected Database Changes

## appointments

- New appointment created
- Status updated on cancel
- Slot updated on reschedule

## notifications

Each appointment action creates a notification record.

| Action | Notification Type |
|---------|-------------------|
| Booking | BOOKED |
| Cancellation | CANCELLED |
| Reschedule | RESCHEDULED |

---

# Email Simulation

Email sending is simulated using Laravel Log.

Check

```
storage/logs/laravel.log
```

---

# Tested Scenarios

## Booking

- Successful booking
- Duplicate booking prevented
- Booking past slot prevented

## Cancellation

- Successful cancellation
- Already cancelled appointment rejected

## Reschedule

- Successful reschedule
- Booked slot rejected
- Different doctor slot rejected

## Concurrency

- Simultaneous booking requests
- Duplicate booking prevented using database transaction and row-level locking (`lockForUpdate()`).

---

# Test Data

Use seeded data.

- Patients
- Doctors

Run

```bash
php artisan migrate:fresh --seed
```

to reset the database before testing.