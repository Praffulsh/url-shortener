# Multi-Tenant URL Shortener

A clean, production-ready multi-tenant URL Shortener application built with **Laravel 13**, **Tailwind CSS**, and **MySQL / SQLite**.

The application features organization/company-level isolation, granular role-based permissions (SuperAdmin, Company Admin, Company Member), secure team invitation flows, and high-performance public URL redirection with real-time analytics.

---

## 🚀 Features

- **Multi-Tenant Architecture**: Each company operates with isolated members and short URLs.
- **Role-Based Access Control (RBAC)**:
  - **SuperAdmin**: System-wide access. Manages companies, invites company administrators to onboard new organizations, views all URLs across all companies for auditing, but cannot create short URLs.
  - **Company Admin**: Manages their organization. Invites team members (Admins or Members) via an in-dashboard modal, creates short URLs, and views all URLs belonging to their company.
  - **Company Member**: Generates short URLs and views only the URLs created by themselves.
- **Public URL Redirection**: Fast 302 redirects from `/{short_code}` to the original URL while tracking click counts (`visits`).
- **Seamless Invitation Flow**: Shareable tokens with dedicated acceptance registration pages. Flash notifications provide direct invitation links for easy testing and local verification.
- **Modern Responsive UI**: Built with Blade components, Tailwind CSS, and Alpine.js. Includes modals, responsive navigation, and status badges.

---

## 🛠️ Tech Stack

- **Framework**: Laravel 13.x (PHP 8.3+)
- **Frontend**: Blade, Tailwind CSS, Alpine.js, Vite 8.x
- **Database**: MySQL (default) or SQLite

---

## 📋 Prerequisites

Before setting up the project, make sure you have the following installed:

- **PHP** >= 8.3 (tested on PHP 8.5, with `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `curl` extensions)
- **Composer** >= 2.x
- **Node.js** >= 20.x & **NPM**
- **MySQL** >= 8.0 (or SQLite)

---

## ⚙️ Installation & Setup

### 1. Clone the Repository
```bash
git clone https://github.com/Praffulsh/url-shortener.git
cd url-shortener
```

### 2. Install PHP & Node Dependencies
```bash
composer install
npm install
```

### 3. Configure the Environment
Copy the example environment configuration:
```bash
cp .env.example .env
```

Generate the application encryption key:
```bash
php artisan key:generate
```

Configure your database in `.env`:

#### MySQL (Default)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=url_shortener
DB_USERNAME=root
DB_PASSWORD=your_password
```
*(Make sure to create the `url_shortener` database in your MySQL instance if it does not exist).*

#### SQLite (Alternative Quick Setup)
```env
DB_CONNECTION=sqlite
# DB_DATABASE is automatically set to database/database.sqlite
```
Create the SQLite file if using SQLite:
```bash
touch database/database.sqlite
```

### 4. Run Migrations & Seed Default Records
```bash
php artisan migrate:fresh --seed
```

### 5. Build Frontend Assets
Compile the assets with Vite:
```bash
npm run build
```

*(For live hot-reloading during development, you can run `npm run dev` in a separate terminal).*

### 6. Start the Local Server
```bash
php artisan serve
```

The application will be live at: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔐 Default Credentials (Seeded)

The database seeder pre-populates the initial **SuperAdmin** account. All companies, admins, team members, and short URLs are created dynamically via the application:

- **Role**: `SuperAdmin`
- **Email**: `superadmin@gmail.com`
- **Password**: `password`

---

## 📂 Project Architecture

```text
app/
├── Http/
│   └── Controllers/
│       ├── CompanyController.php      # Company listing & onboarding with admin invitation
│       ├── DashboardController.php    # Role-aware dashboard (metrics, short URLs & team members)
│       ├── InvitationController.php   # Generating & accepting member/admin invitations
│       └── ShortUrlController.php     # CRUD & public short code redirection
├── Models/
│   ├── Company.php                    # Company entity & relationships
│   ├── Invitation.php                 # Invitation tokens & role assignments
│   ├── ShortUrl.php                   # Short URLs with visits tracking
│   └── User.php                       # Role helper methods & company associations
└── Policies/
    ├── CompanyPolicy.php              # Company management authorization
    ├── InvitationPolicy.php           # Team invitation permission gates
    └── ShortUrlPolicy.php             # Scoped creation & viewing authorization
```

### Database Schema Highlights
- `companies`: Primary organization records (`id`, `name`, `timestamps`).
- `users`: Includes `company_id` (foreign key to `companies`, nullable for SuperAdmin) and `role` (`super_admin`, `admin`, `member`).
- `short_urls`: Includes `company_id`, `user_id`, `original_url`, `short_code` (unique index), and `visits`.
- `invitations`: Includes `company_id`, `email`, `role`, `token` (unique), and `accepted_at`.

---

## 📝 Code Style & Standards

This project adheres to PSR-12 coding standards formatted with **Laravel Pint**:
```bash
vendor/bin/pint
```

---

## 📄 License

This open-source project is distributed under the [MIT License](LICENSE).
