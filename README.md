# CareMate BD — Bangladesh's Trusted Caregiver Marketplace

> **A Concern of Techboloy**  
> Connecting families with verified, compassionate caregivers across all 8 divisions and 64 districts of Bangladesh.

---

## 🌟 Overview

**CareMate BD** is an enterprise-grade, admin-mediated caregiver marketplace designed specifically for the healthcare and home assistance landscape of Bangladesh. It connects families with verified caregivers for:

- **Elderly Care & Geriatric Nursing**
- **Specialized Child Care & Babysitting**
- **Post-operative & Clinical Nursing**
- **Medical Transport & Patient Attendants**

CareMate BD is built with an **Admin-Mediated Quality Control Architecture**, ensuring that every caregiver on the platform undergoes strict NID verification, qualification scrutiny, police clearance checks, and reference interviews before being published.

---

## 🚀 Key Features

### 1. Multi-Role User Architecture
- **Clients / Families:** Browse verified caregivers, submit hiring requests, manage bookings, leave reviews, and raise support disputes.
- **Caregivers:** Complete an accredited 9-step onboarding academy, set daily/hourly rates, manage calendar availability, track earnings, and request payouts via bKash, Nagad, Rocket, or Bank Transfer.
- **Admin Operations:** Comprehensive command center for verifying NID dossiers, reviewing hiring requests, managing caregiver profiles, setting marketplace display order/sequencing, and overseeing finances.

### 2. Comprehensive Bangladesh Geographic Coverage
- Complete database of **all 8 administrative divisions** (Dhaka, Chattogram, Rajshahi, Khulna, Barishal, Sylhet, Rangpur, Mymensingh) and **64 districts**.
- Dynamic cascading location filters on marketplace search, registration, and administrative controls.

### 3. Marketplace Sequencing & Priority Engine
- Administrators can set exact marketplace display sequences (`sort_order`), ensuring premium, top-rated, and emergency-ready providers appear in custom-curated order.
- Filter caregivers by specialty, division, district, gender, experience, ratings, price range, and live-in/live-out status.

### 4. Enterprise Security & Privacy
- **Encrypted NID & Personal Identifiers:** National ID numbers, home addresses, and emergency contact details are encrypted in the database at rest.
- **Role-Based Access Control (RBAC):** Strict policy middleware protecting client, caregiver, and administrator panels.
- **Audit Trail:** Every administrative action (approval, status change, rate override, dispute resolution) is immutably logged with timestamp and IP address.

---

## 🛠️ Technology Stack

- **Framework:** Laravel 12 (PHP 8.4)
- **Database:** SQLite (local/testing) / MySQL / PostgreSQL
- **Frontend:** Vanilla CSS design system with custom CareMate Glassmorphism tokens (`#0a394a`, `#15798e`) & Blade Templates
- **Asset Bundler:** Vite
- **Testing:** PHPUnit Feature & Unit Test Suites

---

## 📋 Installation & Setup

### Prerequisites
- PHP 8.2 or higher (PHP 8.4 recommended)
- Composer
- Node.js (v18+) & NPM

### Step-by-Step Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/alaminCf/carematebd-marketplace.git
   cd carematebd-marketplace
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Database Migrations & Seed Data:**
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```
   *This seeds all 8 divisions, 64 districts, service categories, sample verified caregivers, and admin accounts.*

5. **Build Assets & Link Storage:**
   ```bash
   php artisan storage:link
   npm run build
   ```

6. **Start the Development Server:**
   ```bash
   php artisan serve
   ```
   Access the application at `http://127.0.0.1:8000`.

---

## 🔑 Default Credentials (Seeded)

| Role | Email | Password |
|---|---|---|
| 
| **Caregiver** | `rabeya@caremate.com` | `password123` |
| **Client / Family** | `fatima@example.com` | `password123` |

---

## 🏢 Contact & Corporate Office

- **Address:** E-14/X, ICT Tower (14th Floor), Agargaon, Dhaka-1207, Bangladesh
- **Contact:** +880 1610-296460
- **Email:** contact@carematebd.com
- **Parent Organization:** A Concern of Techboloy

---

## 📄 License
The CareMate BD platform is proprietary software developed for CareMate BD / Techboloy.
