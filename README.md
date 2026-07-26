# iTax - Modern Irish Payroll & Employee Management System

A sleek, state-of-the-art web application for managing employee profiles, running weekly payroll computations, and exporting tax documents according to standard Irish taxation rules.

---

## 🚀 Key Features

* **Interactive Audit Dashboard**: Real-time high-level insights on total employee count, gross payroll costs, net wages paid, and cumulative tax breakdowns (PAYE, USC, and PRSI).
* **Employee Profiles Manager**: Create and manage detailed employee records, track salary structures, and manage individual payroll histories.
* **Tax Computation Engine**: Instantly calculate weekly taxes based on:
  * **PAYE (Pay As You Earn)**: Standard 20% and 40% brackets based on customizable cutoffs and tax credits.
  * **USC (Universal Social Charge)**: Comprehensive calculation based on standard 2026 bands (0.5%, 2%, 4%, and 8%).
  * **PRSI (Pay Related Social Insurance)**: Automated Class A calculations including weekly PRSI credit adjustments for earnings under €424.
* **PDF Report Generation**: Download single payslips or compile full Year-To-Date (YTD) employee reports into clean, printable PDFs using DomPDF.
* **Dynamic Dark-Theme Interface**: A premium modern UI built with Outfit typography, dark theme glassmorphism, responsive navigation, and live data charts.

---

## 🛠️ Architecture & Tech Stack

* **Backend**: Laravel 12 (PHP 8.2+)
* **Database**: SQLite (configured for local database storage)
* **Frontend**: Livewire, Alpine.js, Tailwind CSS (for fast, responsive, and reactive components)
* **Data Visualization**: Chart.js for payroll distribution tracking
* **PDF Exporting**: `barryvdh/laravel-dompdf` for document compiling

---

## ⚙️ Getting Started

### Prerequisites

* **PHP 8.2+** (e.g., XAMPP, Laragon, or standard CLI setup)
* **Composer**
* **Node.js & npm**

### Setup & Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/mtyalvee/itax.git
   cd itax
   ```

2. **Install Composer Dependencies**:
   ```bash
   composer install
   ```

3. **Install Node Dependencies**:
   ```bash
   npm install
   ```

4. **Environment Configuration**:
   * Copy the example environment file:
     ```bash
     cp .env.example .env
     ```
   * Set up your database connection in `.env`. By default, it is configured to use SQLite:
     ```env
     DB_CONNECTION=sqlite
     ```
   * Generate an application key:
     ```bash
     php artisan key:generate
     ```

5. **Run Migrations & Seeders** (if database setup is needed):
   ```bash
   php artisan migrate --seed
   ```

6. **Start the Servers**:
   * Start the Laravel server:
     ```bash
     php artisan serve
     ```
   * Start the Vite development server in another terminal:
     ```bash
     npm run dev
     ```

7. **Visit the app**: Open **[http://127.0.0.1:8000](http://127.0.0.1:8000)** in your browser.

---

## 🔮 Future Updates & Roadmap

* [ ] **Multi-Period Payroll Support**: Support for bi-weekly, semi-monthly, and monthly payroll cycles.
* [ ] **Revenue Commissioner Integration (ROS)**: Direct submission of payroll submissions (PSR) and retrieval of RPNs via ROS API.
* [ ] **Advanced Allowances & Deductions**: Ability to add non-taxable allowances, pension contributions, health insurance, and cycle-to-work schemes.
* [ ] **Time and Attendance Logs**: Tracking employee timesheets and auto-calculating overtime wages.
* [ ] **Multi-User Role Management**: Introduce Role-Based Access Control (RBAC) to separate HR Admins, Accountants, and General Employees (who can log in to view/download their own payslips).
