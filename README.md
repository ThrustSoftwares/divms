# DIVMS - Digital Impounded Vehicle Management System
### 👮 Jinja Road Police Division, Kampala, Uganda

DIVMS is a secure, web-based platform designed to automate and streamline the management of impounded vehicles. It replaces manual record-keeping with an efficient digital workflow, improving transparency, accountability, and operational efficiency.

---

## 🚀 Key Features

### 1. Vehicle Management
*   **Registration:** Capture plate numbers, make, model, color, chassis/engine numbers, and impound location.
*   **Status Tracking:** Real-time tracking through 5 states: `Impounded`, `Pending Payment`, `Cleared`, `Released`, and `Auctioned`.
*   **Media Support:** Upload and store vehicle images for evidence and identification.

### 2. Fine & Payment System
*   **Auto-Calculation:** Fines are automatically calculated based on violation types and storage duration (daily storage fees).
*   **Payment Tracking:** Record payments (Cash, Bank, Mobile Money) with receipt generation.
*   **Financial Reports:** Track daily/monthly revenue and outstanding balances.

### 3. Role-Based Access Control (RBAC)
*   **Admin:** Full system access, user management, and audit logs.
*   **Police Officer:** Vehicle registration, status updates, and release form generation.
*   **Finance Officer:** Payment recording and revenue reporting.

### 4. Accountability & Compliance
*   **Audit Trail:** Every action (create, update, delete) is logged with user details, IP address, and timestamps.
*   **Printable Forms:** Generate official **Payment Receipts** and **Vehicle Release Authorization** forms.
*   **Analytics:** Interactive dashboard showing vehicle distribution and revenue trends.

---

## 🛠️ Tech Stack
*   **Framework:** Laravel 12 (PHP 8.4)
*   **Database:** MySQL / MariaDB
*   **Frontend:** Blade Templates + Vanilla CSS (Custom White & Light Blue Theme)
*   **Icons/Charts:** SVG Icons + Chart.js

---

## 📥 Installation

### Prerequisites
*   PHP >= 8.4
*   Composer
*   MySQL Server

### Setup Steps
1. **Clone/Extract** the project to your local directory.
2. **Install Dependencies:**
   ```bash
   composer install
   ```
3. **Configure Environment:**
   Update `.env` with your database credentials:
   ```env
   DB_DATABASE=divms
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```
4. **Generate App Key:**
   ```bash
   php artisan key:generate
   ```
5. **Create Database:**
   Create a database named `divms` in your MySQL server.
6. **Run Migrations & Seeders:**
   ```bash
   php artisan migrate:fresh --seed
   ```
7. **Create Storage Link:**
   ```bash
   php artisan storage:link
   ```
8. **Start Server:**
   ```bash
   php artisan serve
   ```

---

## 🔐 Default Credentials

| Role | Email | Password |
|---|---|---|
| **Administrator** | `admin@divms.ug` | `Admin@1234` |
| **Police Officer** | `officer@divms.ug` | `Officer@1234` |
| **Finance Officer** | `finance@divms.ug` | `Finance@1234` |

---

## 📁 Project Structure Highlights

*   **`app/Models`**: 12 core models handling Vehicles, Owners, Fines, Payments, and Logs.
*   **`app/Http/Controllers`**: Logic for Dashboard, Vehicles, Finance, and Reports.
*   **`app/Http/Middleware`**: 
    *   `CheckRole`: Handles RBAC.
    *   `AuditLogMiddleware`: Captures all system activities.
*   **`resources/views`**: Custom Blade templates styled with a premium light blue theme.
*   **`divms.sql`**: Standalone SQL export including schema and seed data.

---

## 🛡️ License
Professional software developed for the Uganda Police Force - Jinja Road Division.
