# 🎓 Final Year Project Management System (FYP-MS)

A Laravel-based system designed to manage final year projects for university students. It helps check if a proposed project has been done before, allows Heads of Department to assign supervisors, and enables communication between students and their supervisors.

## ✅ Key Features

- **Project Duplication Check**  
  Detects and avoids project topics that have already been done in previous years.

- **Supervisor Assignment**  
  Allows HoDs to assign supervisors to students based on project topics or availability.

- **Role-Based Access**  
  - **Admin / HoD**: Manage students, supervisors, and projects.
  - **Supervisors**: Monitor assigned students and guide them.
  - **Students**: Submit project proposals, view assigned supervisors.

- **Messaging System**  
  Enables chatting between students and their supervisors within the platform.

## 🧰 Tech Stack

- **Framework**: Laravel
- **Language**: PHP
- **Database**: MySQL or MariaDB
- **Views**: Blade templating engine
- **Authentication**: Laravel built-in authentication (Breeze / UI / Jetstream depending on what was used)

## 🔧 Installation Guide

1. **Clone the repository**
   ```bash
   git clone https://github.com/Jadowacu1/FYP-Laravel.git
   cd FYP-Laravel
````

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Create a `.env` file**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure your `.env`**

   * Set your **database name**, **username**, and **password**.

5. **Run database migrations**

   ```bash
   php artisan migrate
   ```

6. **(Optional) Seed the database**

   ```bash
   php artisan db:seed
   ```

7. **Serve the application**

   ```bash
   php artisan serve
   ```

   Visit: `http://localhost:8000`

---

### 👥 User Roles

| Role           | Description                                                                              |
| -------------- | ---------------------------------------------------------------------------------------- |
| **Admin**      | Views and registers universities, faculties, departments, and Heads of Department (HoDs) |
| **HoD**        | Manages students, assigns supervisors, and tracks previously submitted projects          |
| **Student**    | Submits project proposals and chats with their assigned supervisor                       |
| **Supervisor** | Views assigned students and communicates with them                                       |

---


## 🛡️ Future Enhancements

* PDF uploads for project proposals
* AI-based similarity detection for project titles
* Supervisor rating or feedback system
* Notifications system

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

Developed with ❤️ using Laravel.

```

