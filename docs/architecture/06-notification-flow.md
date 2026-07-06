# Notification Flow

## Overview

The notification system informs patients about appointment events.

Notifications are processed asynchronously using Laravel Queues to keep API responses fast and reliable.

---

# Notification Types

The system supports the following notification types:

- BOOKED
- CANCELLED
- RESCHEDULED

---

# Notification Status

| Status | Description |
|----------|-------------|
| QUEUED | Waiting to be processed |
| SENT | Successfully delivered |
| FAILED | Delivery failed |

---

# Booking Flow

1. Patient books an appointment.
2. Appointment is stored.
3. Notification record is created with status `QUEUED`.
4. Database transaction is committed.
5. Queue job is dispatched.
6. Queue worker sends the email.
7. Notification status is updated.

---

# Retry Strategy

If email delivery fails:

- Increase `retry_count`.
- Store the error message in `last_error`.
- Retry the job.
- Maximum retry attempts: **3**.

If all retry attempts fail:

```
status = FAILED
```

The appointment remains successfully booked.

---

# Why Notification is Independent

Appointment booking is the primary business operation.

Email delivery is a communication process.

A failed email must never cancel a successfully booked appointment.

This separation ensures business consistency even if the mail server is unavailable.

---

# Queue Processing

AppointmentService

↓

NotificationService

↓

Create Notification Record

↓

Commit Transaction

↓

Dispatch Queue Job

↓

Queue Worker

↓

Mail Service

↓

Notification Status Updated

---

# Notification Table

The notification table stores:

- Appointment reference
- Notification type
- Message
- Delivery status
- Retry count
- Last error
- Sent timestamp

This provides a complete audit trail of notification delivery.

---

# Design Decisions

- Notifications are processed asynchronously.
- Database queue is used for this assessment.
- Failed notifications do not rollback appointments.
- Retry mechanism improves reliability.
- Notification history is stored for auditing and troubleshooting.