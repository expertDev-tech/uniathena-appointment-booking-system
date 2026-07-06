# Project Architecture

## Overview

The Appointment Booking System follows a layered architecture to keep the application maintainable, scalable and easy to understand.

Each layer has a single responsibility and communicates only with the next layer.

---

# Architecture Flow

Client

↓

API Route

↓

Controller

↓

Form Request

↓

Service

↓

Model (Eloquent)

↓

Database

---

# Layer Responsibilities

## 1. Routes

### Responsibility

Routes map HTTP requests to controllers.

### Rules

- No business logic
- No validation
- No database queries

Example

POST /api/appointments

↓

AppointmentController@store

---

## 2. Controllers

### Responsibility

Controllers coordinate the request and response.

Responsibilities

- Receive validated request
- Call Service Layer
- Return JSON response

Controllers never:

- Execute business logic
- Access the database directly
- Generate reference numbers
- Send notifications

Controllers remain thin.

---

## 3. Form Requests

### Responsibility

Validate incoming requests before they reach the business layer.

Responsibilities

- Validation rules
- Authorization (returns true)

Benefits

- Cleaner controllers
- Reusable validation
- Consistent API responses

Examples

StoreAvailabilityRequest

BookAppointmentRequest

CancelAppointmentRequest

RescheduleAppointmentRequest

---

## 4. Service Layer

The Service Layer contains all business logic.

Responsibilities

- Appointment booking
- Appointment cancellation
- Appointment rescheduling
- Availability creation
- Slot generation
- Database transactions
- Concurrency handling
- Notification coordination

Services communicate with Eloquent models.

Business rules never exist inside controllers.

---

## 5. Models

Models represent database entities.

Responsibilities

- Relationships
- Attribute casting
- Query scopes (if required)

Models should not contain business workflows.

Examples

Doctor

Patient

DoctorAvailability

AvailabilitySlot

Appointment

Notification

---

## 6. Jobs

Jobs execute asynchronous work.

Current Job

SendAppointmentNotificationJob

Responsibilities

- Send notification email
- Update notification status
- Retry failed notifications

---

## 7. Mail

Mail classes are responsible for formatting email content.

Business logic is not implemented inside Mail classes.

---

# Folder Structure

app/

Http/

Controllers/

Requests/

Models/

Services/

Jobs/

Mail/

Enums/

Exceptions/

Providers/

---

# Business Flow

Doctor

↓

Create Availability

↓

Generate Availability Slots

↓

Patient Views Available Slots

↓

Patient Books Appointment

↓

Appointment Created

↓

Notification Created

↓

Queue Job Dispatched

↓

Email Sent

---

# Exception Handling

The application follows standard REST API status codes.

| Status Code | Meaning |
|-------------|----------|
| 200 | Success |
| 201 | Resource Created |
| 404 | Resource Not Found |
| 409 | Business Conflict |
| 422 | Validation Failed |
| 500 | Internal Server Error |

---

# Queue Processing

Notifications are processed asynchronously.

Booking completes first.

Email sending happens later through the queue worker.

This improves response time and application scalability.

---

# Scalability

The architecture is designed so that business logic remains unchanged as the application grows.

Future infrastructure improvements may include

- Redis Queue
- Read Replicas
- Load Balancer
- Multiple Application Servers
- Distributed Cache

These changes do not require modification of the Service Layer.

---

# Architecture Principles

The project follows the following principles

- Single Responsibility Principle (SRP)
- Separation of Concerns
- Layered Architecture
- RESTful API Design
- Database Transactions
- Row-Level Locking
- Asynchronous Notifications

---

# Conclusion

The architecture keeps business logic isolated from infrastructure concerns.

Controllers remain lightweight.

Services contain business rules.

Models represent data.

Jobs handle asynchronous processing.

This separation improves readability, maintainability and scalability.