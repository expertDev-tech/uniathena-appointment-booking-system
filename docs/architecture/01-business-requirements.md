# Business Requirements

## Objective

Develop a RESTful Appointment Booking System where:

- Doctors define their availability.
- Patients can view available slots.
- Patients can book appointments.
- Patients can cancel appointments.
- Patients can reschedule appointments.
- Email notifications are sent asynchronously.

---

## Actors

### Doctor

Responsibilities

- Create availability schedule
- Define slot duration

---

### Patient

Responsibilities

- View available slots
- Book appointment
- Cancel appointment
- Reschedule appointment

---

## Business Rules

1. Availability belongs to a single doctor.
2. Slots are generated from doctor availability.
3. One slot can have only one active appointment.
4. Reference number is generated automatically.
5. Notification is created after successful booking.
6. Email sending must not affect appointment booking.