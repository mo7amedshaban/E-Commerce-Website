# 🛒 E-Commerce Website (Laravel + jQuery)

A full-featured bilingual E-Commerce website developed using **Laravel (PHP Framework)** and **jQuery**.  
This project simulates a real online store with product listing, shopping cart, multi-language support (Arabic & English), and a payment flow via card checkout.

---

## 🌟 Key Features

- 🧭 **Multi-language**: Supports both Arabic 🇸🇦 and English 🇬🇧
- 🛍️ **Product management**: View products, categories, and details
- 🛒 **Cart system**: Add, update, remove products with real-time price calculation
- 💳 **Payment system**: Simulated credit card checkout functionality
- 🔐 **Secure routing**: Using Laravel authentication and CSRF protection
- 📱 **Responsive design**: Works on all screen sizes and devices
- 🧠 **Clean MVC structure** with Laravel best practices
- ⚙️ AJAX-powered actions using **jQuery**

---

## 🧑‍💻 Tech Stack

| Layer         | Technology         |
|---------------|--------------------|
| 🖥️ Frontend    | HTML5, CSS3, Bootstrap, jQuery |
| 🧠 Backend     | Laravel 8 (MVC Framework) |
| 🗄️ Database    | MySQL |
| 🌐 Languages   | English 🇬🇧 & Arabic 🇸🇦 |
| 💳 Payment UI | HTML/CSS + JavaScript (simulated) |
| 🧩 Auth System | Laravel Auth (login/register/middleware) |

---

## 🚀 Getting Started

Follow these steps to run the project locally on your machine.

### 1. Clone the Repository

```bash
https://github.com/mo7amedshaban/E-Commerce-Website.git
cd E-Commerce-Website
```

2. Install PHP Dependencies
```bash

composer install
```

3. Install Frontend Assets (Optional if using Laravel Mix)
```bash

npm install && npm run dev
```
4. Configure Environment Variables
```bash

Duplicate .env.example file and rename it to .env

Update your database credentials:

DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```
5. Generate Application Key
```bash


php artisan key:generate
```
6. Run Database Migrations
```bash

php artisan migrate
php artisan db:seed
```
8. Serve the Application Locally
```bash

php artisan serve

Then open your browser and go to:
http://127.0.0.1:8000
```

