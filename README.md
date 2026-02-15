# 🛒 Alideda – Laravel E-Commerce Platform

---

## ⚙️ Installation Guide --> LINE 63 -- IMPORTANT --

Alideda is a Laravel-based multi-store e-commerce platform developed as a university project.

The system allows users to create stores, add products, place orders, and manage them through different user roles (User, Store Owner, Admin).

---

## 👨‍💻 Author

Name: Veselin Sevo  
Index Number: YOUR_INDEX_NUMBER

---

## 🚀 Main Features

### 👤 User

- Register / Login / Logout
- Create and manage stores
- Create and manage products
- Add products to cart
- Checkout and place orders
- View personal orders
- Contact admin page

### 🏪 Store Owner

- View orders related to own stores
- Change store order statuses
- Track store-specific order flow

### 🛠 Admin Panel

- Manage users (ban / unban / delete)
- Manage stores (verify / unverify / delete)
- Manage products
- Manage orders
- View statistics dashboard with charts
- View contact messages
- View key user activities (login, logout, registration)

### 📊 Activity Logging

The system logs important user activities:

- Registration
- Login
- Logout

Admin can filter activity logs by date.

---

## 🛠 Technologies Used

- Laravel 12
- PHP 8+
- MySQL
- Tailwind CSS
- Chart.js
- Vite

---

# ⚙️ Installation Guide

Follow these steps after cloning the repository.

---

## 1️⃣ Clone the project

```bash
git clone https://github.com/YOUR_GITHUB_USERNAME/Alideda.git
cd Alideda
```

---

## 2️⃣ Install PHP dependencies

```bash
composer install
```

---

## 3️⃣ Create environment file

```bash
cp .env.example .env
```

---

## 4️⃣ Generate application key

```bash
php artisan key:generate
```

---

## 5️⃣ Configure database

Edit the `.env` file and set:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=alideda
DB_USERNAME=root
DB_PASSWORD=
```

Then create the database manually in MySQL:

```sql
CREATE DATABASE alideda;
```

---

## 6️⃣ Run migrations and seeders

```bash
php artisan migrate:fresh --seed
```

---

## 7️⃣ Create storage symbolic link

```bash
php artisan storage:link
```

---

## 8️⃣ Install frontend dependencies

```bash
npm install
npm run build
```

For development mode use:

```bash
npm run dev
```

---

## 9️⃣ Run the application

```bash
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000
```

---

# 🔐 Demo Admin Account (if seeders are included)

Email: admin@example.com  
Password: password

---

# 📁 Project Structure Overview

App/Services → Business logic (OrderService, StoreOrderService, etc.)  
App/Http/Controllers/Admin → Admin management  
App/Http/Controllers/Owner → Store owner logic  
resources/views → Blade templates  
database/migrations → Database structure  
database/seeders → Demo data

---

# 📌 Important Notes

- The `.env` file is not included in the repository.
- Database must be created manually before running migrations.
- `php artisan storage:link` is required for product images.
- This project is intended for educational purposes.

---

# 🎓 Educational Purpose

This project demonstrates:

- MVC architecture
- Service layer usage
- Role-based access control
- Filtering & sorting implementation
- Activity logging
- Admin dashboard statistics
- Multi-store order splitting system
