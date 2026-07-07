# Business Requirements

## Overview

The Appointment Booking System allows patients to book appointments with doctors based on their available schedules. The system ensures data consistency, prevents duplicate bookings, and provides notifications for important appointment activities.

---

# Functional Requirements

## 1. Doctor Availability

Doctors can define their available schedule.

Each availability contains:

- Available Date
- Start Time
- End Time
- Slot Duration

The system automatically generates appointment slots based on the configured duration.

---

## 2. View Available Slots

Patients can view available appointment slots for a specific doctor.

### Business Rules

- Doctor must exist.
- Availability must exist.
- Only unbooked slots are returned.
- Date filter is optional.
- Slots are ordered by start time.

---

## 3. Book Appointment

Patients can book an available appointment slot.

### Business Rules

- Patient must exist.
- Availability slot must exist.
- Slot must not already be booked.
- Past time slots cannot be booked.
- Booking generates a unique reference number.
- Booking is performed inside a database transaction.
- Row-level locking prevents concurrent bookings of the same slot.

---

## 4. Cancel Appointment

Patients can cancel an existing appointment.

### Business Rules

- Appointment must exist.
- Only BOOKED appointments can be cancelled.
- Cancellation reason is required.
- Cancelled slots automatically become available again.

---

## 5. Reschedule Appointment

Patients can reschedule an appointment.

### Business Rules

- Appointment must exist.
- Only BOOKED appointments can be rescheduled.
- New slot must exist.
- New slot must not already be booked.
- New slot must belong to the same doctor.
- Appointment is updated inside a database transaction.

---

## 6. Notification

The system generates notifications after appointment activities.

### Booking

- Store notification record.
- Simulate email notification.

### Cancellation

- Store notification record.
- Simulate email notification.

### Reschedule

- Store notification record.
- Simulate email notification.

Notifications are processed asynchronously using Laravel Queues.

---

# Non-Functional Requirements

## Data Consistency

Database transactions are used to ensure appointment integrity.

---

## Concurrency

The system prevents duplicate bookings by using row-level locking (`lockForUpdate()`).

---

## Scalability

Notification processing is asynchronous using queued listeners to reduce API response time.

---

## Maintainability

Business logic is separated into service classes.

Notifications are implemented using Laravel Events and Listeners.

The architecture follows the Single Responsibility Principle (SRP).

---

# Assumptions

- A patient can book multiple appointments.
- One appointment belongs to one patient.
- One appointment belongs to one availability slot.
- A slot can only have one active (BOOKED) appointment.
- Cancelled appointments release the slot automatically.
- Rescheduling keeps the same appointment and updates its slot.
- Notification emails are simulated using Laravel logs.