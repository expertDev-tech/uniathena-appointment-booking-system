# Appointment Booking System

A RESTful Appointment Booking System built with **Laravel 12** and **MySQL** that allows patients to book appointments with doctors while preventing duplicate bookings and handling concurrent booking requests safely.

The project follows a **Service Layer Architecture** with **Events**, **Queued Listeners**, and **Database Transactions** to build a clean, maintainable, and scalable backend.

---

# Features

- Doctor Availability Management
- View Available Slots
- Book Appointment
- Cancel Appointment
- Reschedule Appointment
- Unique Appointment Reference Number
- Prevent Double Booking
- Handle Concurrent Booking Requests
- Notification Storage
- Email Notification Simulation
- Event Driven Architecture
- Queued Notification Processing
- Proper Validation & Error Handling

---

# Technology Stack

| Technology | Version |
|------------|----------|
| PHP | 8.x |
| Laravel | 12 |
| MySQL | 8.x |
| Queue Driver | Database |
| REST API | JSON |

---

# Project Architecture

The application follows a layered architecture.

```
Controller
      │
      ▼
Service Layer
      │
      ▼
Database
```

Notifications are handled asynchronously.

```
Appointment Service
        │
        ▼
AppointmentNotificationEvent
        │
        ▼
Queued Listener
        │
        ├── Notification Service
        └── Email Service
```

---

# Installation

Clone the repository.

```bash
git clone <repository-url>
```

Install dependencies.

```bash
composer install
```

Copy environment file.

```bash
cp .env.example .env
```

Generate application key.

```bash
php artisan key:generate
```

Configure database credentials inside `.env`.

Run migrations and seeders.

```bash
php artisan migrate --seed
```

Start queue worker.

```bash
php artisan queue:work
```

Start Laravel server.

```bash
php artisan serve
```

---

# API Documentation

Detailed API documentation is available in:

```
docs/architecture/api-design.md
```

A Postman Collection is included in:

```
docs/postmain-guide.md
```

---

# Project Documentation

Additional documentation is available inside the **docs** directory.

```
docs/

architecture/
    business-requirements.md
    database-design.md
    api-design.md
    validation-strategy.md
    concurrency-strategy.md
    notification-flow.md
    project-architecture.md

decisions/
    architecture-decisions.md
```

---

# Assignment Coverage

| Requirement | Status |
|-------------|--------|
| Doctor Availability | ✅ |
| View Available Slots | ✅ |
| Book Appointment | ✅ |
| Prevent Double Booking | ✅ |
| Cancel Appointment | ✅ |
| Reschedule Appointment | ✅ |
| Notification Record | ✅ |
| Email Simulation | ✅ |
| Queue Processing | ✅ |
| Event & Listener | ✅ |
| Validation | ✅ |
| Error Handling | ✅ |
| Concurrent Booking | ✅ |

---

# Performance Considerations

- Database indexing for frequently queried columns.
- Database transactions for appointment booking.
- Row-level locking using `lockForUpdate()`.
- Queued notification processing to reduce API response time.
- Optimized database queries to minimize unnecessary lookups.

---

# Scaling Strategy

The application can be scaled using:

- Multiple Laravel application servers
- Load Balancer
- Multiple Queue Workers
- Redis Queue Driver
- Database Read Replicas
- Separate Notification Service
- Distributed Cache

---

# Design Decisions

Important architectural decisions are documented in:

```
docs/decisions/architecture-decisions.md
```

---

# Author

Ankit Parmar

Senior PHP / Laravel Full Stack Developer