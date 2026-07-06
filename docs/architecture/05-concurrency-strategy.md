# Concurrency Strategy

## Overview

The system must prevent multiple patients from booking the same appointment slot simultaneously.

Concurrency control is implemented using database transactions and row-level locking.

---

# Booking Flow

Appointment booking follows this sequence:

1. Begin database transaction.
2. Lock the selected availability slot using `lockForUpdate()`.
3. Verify the slot is still available.
4. Create the appointment.
5. Create the notification record.
6. Commit the transaction.
7. Dispatch the notification queue.

---

# Why Row-Level Locking?

When two patients attempt to book the same slot at the same time:

Patient A

↓

Locks the selected slot.

↓

Creates the appointment.

↓

Commits the transaction.

↓

Releases the lock.

Patient B

↓

Waits until the lock is released.

↓

Checks the slot again.

↓

Receives a booking conflict because the slot is no longer available.

This guarantees that only one appointment can be created for a single availability slot.

---

# Why lockForUpdate()?

Laravel's `lockForUpdate()` acquires an exclusive lock on the selected database row during a transaction.

Benefits:

- Prevents duplicate bookings.
- Ensures data consistency.
- Supports concurrent users safely.
- Locks only the required slot instead of the entire table.

---

# Transaction Flow

```
Begin Transaction

↓

Lock Availability Slot

↓

Validate Slot Availability

↓

Create Appointment

↓

Create Notification

↓

Commit Transaction

↓

Dispatch Queue Job
```

---

# Scalability

The concurrency strategy is designed to scale.

Only the requested availability slot is locked.

Other patients booking different slots are not affected.

This minimizes database contention while maintaining data consistency.

---

# Design Decisions

- Booking always executes inside a database transaction.
- Row-level locking is preferred over application-level locking.
- Notification dispatch occurs only after a successful transaction commit.
- Business consistency is prioritized over notification delivery.