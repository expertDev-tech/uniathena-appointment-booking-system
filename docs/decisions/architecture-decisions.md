# Architecture Decisions

## Purpose

This document records the important design decisions taken during the development of the Appointment Booking System.

Each decision includes the reason behind it.

---

# ADR-001

## Decision

Use a Service Layer.

### Reason

Business logic should remain independent from controllers.

Controllers only receive requests and return responses.

This makes the application easier to test, maintain and extend.

---

# ADR-002

## Decision

Do not use the Repository Pattern.

### Reason

Laravel Eloquent already provides an excellent abstraction over the database.

Adding repositories would introduce unnecessary complexity for this project.

---

# ADR-003

## Decision

Store generated availability slots permanently.

### Reason

Instead of generating slots dynamically every time,

the system stores them in the database.

Benefits

- Faster searching
- Simple booking
- Better concurrency handling
- Row-level locking
- Improved scalability

---

# ADR-004

## Decision

Use Availability Slot for appointment booking.

### Reason

Appointments reference an availability slot instead of manually storing doctor schedules.

This removes duplicate data and simplifies validation.

---

# ADR-005

## Decision

Use database transactions during booking.

### Reason

Booking an appointment involves multiple database operations.

Transactions guarantee consistency if any operation fails.

---

# ADR-006

## Decision

Use row-level locking (lockForUpdate).

### Reason

Prevents multiple patients from booking the same slot simultaneously.

Only the selected slot is locked.

Other bookings continue without interruption.

---

# ADR-007

## Decision

Notification processing is asynchronous.

### Reason

Sending email should never delay appointment booking.

Notifications are dispatched through Laravel Queues after the database transaction commits.

---

# ADR-008

## Decision

Notification failure must not rollback appointment booking.

### Reason

Appointment booking is the primary business transaction.

Email delivery is a secondary communication process.

Business success should never depend on external services.

---

# ADR-009

## Decision

Store notification history.

### Reason

Keeping notification records allows

- Delivery tracking
- Retry support
- Error investigation
- Audit history

---

# ADR-010

## Decision

Use Laravel Database Queue.

### Reason

The assignment only requires queue implementation.

The database queue is simple to configure and sufficient for this assessment.

In production, Redis or another queue backend can be adopted without changing business logic.

---

# ADR-011

## Decision

Use Laravel 12.

### Reason

The assignment requires Laravel 10 or higher.

Laravel 12 satisfies the requirement while providing the latest stable framework improvements.

---

# ADR-012

## Decision

Keep Controllers Thin.

### Reason

Controllers should coordinate requests only.

Business logic belongs inside the Service Layer.

This improves readability and maintainability.

---

# ADR-013

## Decision

Keep Models Thin.

### Reason

Models represent data and relationships.

Business workflows remain inside Services.

---

# ADR-014

## Decision

Use RESTful APIs.

### Reason

REST provides a consistent and predictable interface.

HTTP methods clearly represent application actions.

GET

Retrieve resources.

POST

Create resources.

PATCH

Update resources.

---

# ADR-015

## Decision

Return consistent JSON responses.

### Reason

Every API follows the same response structure.

Example

{
    "success": true,
    "message": "...",
    "data": {}
}

This simplifies API consumption for frontend applications.

---

# ADR-016

## Decision

Design for scalability.

### Reason

The business architecture should remain unchanged as user traffic grows.

Future improvements such as

- Redis
- Read Replicas
- Load Balancers
- Horizontal Scaling

can be introduced without modifying business logic.

---

# Final Design Philosophy

The primary objective of this project is not only to implement the required assignment but also to demonstrate clean architecture and sound software engineering principles.

The project emphasizes

- Maintainability
- Scalability
- Readability
- Separation of Concerns
- Business-first Design

Every architectural decision has been taken with these principles in mind.