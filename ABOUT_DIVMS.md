# DIVMS System Documentation

## What is DIVMS?
DIVMS stands for **Digital Impounded Vehicle Management System**. It is a web application built to support the operations of impounded vehicle management at the Jinja Road Police Division in Kampala, Uganda.

The system helps police staff manage impounded vehicles, record violations, calculate fines, collect payments, generate release forms, and audit actions.

## Core Purpose
DIVMS is designed to:
- Replace paper-based impound records with a digital workflow
- Track vehicle status from impound to release
- Manage fines and payments in a central ledger
- Provide role-based access for staff
- Preserve audit trails for all operations
- Make information searchable through a public portal

## Primary User Roles
The application uses role-based access control. The main roles are:

- **Admin**
  - Full access to all system features
  - Manage users, audit logs, reports, vehicles, and financial records
- **Police Officer**
  - Register impounded vehicles
  - Update vehicle status
  - Generate release forms
- **Finance Officer**
  - Record payments
  - Generate payment receipts
  - View revenue reports

## Main System Features

### Vehicle Management
- Store vehicle details such as plate number, make/model, color, chassis number, and engine number.
- Attach vehicle images for evidence.
- Track ownership information and violation history.
- Manage vehicle lifecycle through statuses like `Impounded`, `Pending Payment`, `Cleared`, `Released`, and `Auctioned`.

### Fines & Payments
- Record violation types and configure fine amounts.
- Calculate storage fees and outstanding balances.
- Support payment recording by cash, bank, or mobile money.
- Generate receipts for payments.

### Release Forms
- Create formal release authorization documents.
- Ensure release is only processed after required payments and approvals.

### Audit Logging
- Capture all user actions with timestamps and references.
- Log event details for user activities, record changes, and access attempts.
- Help administrators monitor compliance and detect unauthorized activity.

### Reporting
- Generate daily, monthly, and revenue reports.
- Track total collections, outstanding fines, and vehicle status counts.

### Public Portal
- Allow members of the public to search for vehicle records.
- Provide a limited, read-only search experience without authentication.

## Technical Architecture

### Framework & Language
- **Backend:** Laravel 12
- **Language:** PHP 8.2+
- **Frontend:** Blade templates
- **Database:** MySQL / MariaDB

### Key Components
- `routes/web.php` — Defines public and authenticated routes.
- `app/Http/Controllers/` — Handles UI actions and business logic.
- `app/Models/` — Represents database entities and relationships.
- `app/Http/Middleware/CheckRole.php` — Enforces active user status and role permissions.
- `database/migrations/` — Defines the schema for vehicles, users, payments, and logs.

## Important Models
- `User`
- `Role`
- `Vehicle`
- `VehicleOwner`
- `VehicleViolation`
- `Fine`
- `Payment`
- `ReleaseForm`
- `AuditLog`
- `VehicleImage`
- `VehicleStatusLog`
- `ViolationType`

## How Access is Controlled
The middleware `app/Http/Middleware/CheckRole.php` performs these checks:
- Ensures the user is authenticated
- Verifies the user account is active
- Restricts access based on the user's role

If a user is inactive, they are logged out and redirected to the login page with an error message.

## System Setup

### Required Software
- PHP 8.2 or higher
- Composer
- MySQL or MariaDB

### Setup Steps
1. Install dependencies:
   ```bash
   composer install
   ```
2. Configure environment variables in `.env`.
3. Generate the application key:
   ```bash
   php artisan key:generate
   ```
4. Create the database and run migrations:
   ```bash
   php artisan migrate --seed
   ```
5. Create the storage link:
   ```bash
   php artisan storage:link
   ```
6. Start the server:
   ```bash
   php artisan serve
   ```

## Credentials and Security
Sensitive credentials are not stored in the repository. The application uses the `.env` file for secrets such as database credentials and mail settings.

### Example Default Accounts
These are sample accounts for local testing and should be updated immediately in a production system:

- **Administrator**
  - Email: `admin@divms.ug`
  - Password: `Admin@1234`
- **Police Officer**
  - Email: `officer@divms.ug`
  - Password: `Officer@1234`
- **Finance Officer**
  - Email: `finance@divms.ug`
  - Password: `Finance@1234`

> Note: Never commit production passwords, API keys, or secret values into source control.

## Notes for Future Maintenance
- Keep role names consistent: `admin`, `officer`, `finance_officer`.
- Use the `is_active` flag on `User` to disable access without deleting accounts.
- Add new report types by extending `ReportController` and creating Blade views under `resources/views/reports`.
- Update audit logic carefully; it is critical for accountability.

## Where to Find Key Files
- `routes/web.php` — Route definitions and role-restricted access
- `app/Http/Controllers/DashboardController.php` — Main dashboard logic
- `app/Http/Controllers/VehicleController.php` — Vehicle registration and status management
- `app/Http/Controllers/PaymentController.php` — Payment flow and receipts
- `app/Http/Controllers/ReleaseFormController.php` — Release form generation
- `app/Http/Middleware/CheckRole.php` — Access control logic

## Summary
DIVMS is a focused police management application for impounded vehicles, blending vehicle data, violation tracking, payments, release authorization, and audit reporting into one secure system.

This document is intended as a concise reference for the system, its purpose, and how it is built. For operational use, keep credentials secure and configure all sensitive values through `.env` only.
