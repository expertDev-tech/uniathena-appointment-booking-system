# Database Design

## Overview

The Appointment Booking System is designed using a normalized relational database.

The system separates doctor availability, generated slots, appointments and notifications into different tables to keep responsibilities independent and improve scalability.

---

# Database Tables

## 1. doctors

### Purpose

Stores doctor information.

### Columns

| Column | Type | Description |
|---------|------|-------------|
| id | BIGINT | Primary Key |
| name | VARCHAR | Doctor name |
| email | VARCHAR | Unique email |
| created_at | TIMESTAMP | Created timestamp |
| updated_at | TIMESTAMP | Updated timestamp |

### Relationships

- Doctor has many Availabilities.

---

## 2. patients

### Purpose

Stores patient information.

### Columns

| Column | Type | Description |
|---------|------|-------------|
| id | BIGINT | Primary Key |
| name | VARCHAR | Patient name |
| email | VARCHAR | Unique email |
| created_at | TIMESTAMP | Created timestamp |
| updated_at | TIMESTAMP | Updated timestamp |

### Relationships

- Patient has many Appointments.

---

## 3. doctor_availabilities

### Purpose

Stores availability schedules created by doctors.

Example:

Date: 10 July

Start Time: 09:00

End Time: 12:00

Slot Duration: 15 Minutes

### Columns

| Column | Type |
|---------|------|
| id | BIGINT |
| doctor_id | Foreign Key |
| available_date | DATE |
| start_time | TIME |
| end_time | TIME |
| slot_duration | INTEGER |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

### Relationships

- Belongs to Doctor.
- Has many Availability Slots.

---

## 4. availability_slots

### Purpose

Stores every generated appointment slot.

Example

09:00 - 09:15

09:15 - 09:30

09:30 - 09:45

...

Keeping slots permanently in the database makes booking simple, supports row-level locking and improves scalability.

### Columns

| Column | Type |
|---------|------|
| id | BIGINT |
| availability_id | Foreign Key |
| start_time | TIME |
| end_time | TIME |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

### Relationships

- Belongs to Doctor Availability.
- Can have one Appointment.

---

## 5. appointments

### Purpose

Stores appointments booked by patients.

Each appointment represents one booked availability slot.

### Columns

| Column | Type |
|---------|------|
| id | BIGINT |
| patient_id | Foreign Key |
| availability_slot_id | Foreign Key |
| reference_number | VARCHAR (Unique) |
| status | ENUM (BOOKED, CANCELLED) |
| cancel_reason | TEXT (Nullable) |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

### Relationships

- Belongs to Patient.
- Belongs to Availability Slot.
- Has many Notifications.

> Note:
>
> The doctor is determined through the selected Availability Slot. Therefore, storing `doctor_id` inside the appointments table is unnecessary and avoids data duplication.

---

## 6. notifications

### Purpose

Stores appointment notification history.

Notification delivery is independent from appointment booking.

### Columns

| Column | Type |
|---------|------|
| id | BIGINT |
| appointment_id | Foreign Key |
| type | ENUM (BOOKED, CANCELLED, RESCHEDULED) |
| message | TEXT |
| status | ENUM (QUEUED, SENT, FAILED) |
| retry_count | INTEGER |
| last_error | TEXT (Nullable) |
| sent_at | TIMESTAMP (Nullable) |
| created_at | TIMESTAMP |
| updated_at | TIMESTAMP |

### Relationships

- Belongs to Appointment.

---

# Relationship Summary

Doctor

↓

Doctor Availability

↓

Availability Slots

↓

Appointment

↓

Notification

Patient

↓

Appointment

---

# Design Decisions

- Availability is separated from Appointment.
- Slots are generated and stored permanently.
- One appointment can be booked against one availability slot.
- Notification history is stored separately from appointments.
- Appointment booking and email notification are independent business processes.
