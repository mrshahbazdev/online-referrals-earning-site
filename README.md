# CodeShack - Advanced Earning &amp; Referral Platform

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php)
![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss)

**Live Demo:** [https://earnzy.uk](https://earnzy.uk)

## About The Project

CodeShack is a comprehensive and secure web application built with Laravel, designed as a multi-level earning platform. It empowers users to earn rewards by completing various online tasks and leveraging a powerful, multi-level referral system. The platform is managed through a feature-rich admin panel that provides complete control over the application's core functionalities.

This project is built with a focus on security, scalability, and a seamless user experience, featuring a modern, responsive frontend and a robust backend.

-----

## Key Features

### 👤 User Panel

  * **Secure Authentication:** Full registration and login system, restricted to Gmail accounts for enhanced security.
  * **Email Verification:** Mandatory email verification for new users before they can access platform features.
  * **Dynamic Home Page:** A central hub for users, displaying key stats, referral links, and the latest announcements.
  * **Multi-Level Referral System:** A "My Team" page where users can track their referrals across 3 levels, view their status (Pending/Active), and monitor their total commission earnings.
  * **Multi-Platform Task System:** Users can complete a variety of tasks from different platforms (YouTube, TikTok, Facebook) such as watching videos, liking content, or following accounts.
  * **Interactive Task Completion:**
      * **Video Tasks:** In-app video player with a timer to ensure tasks are completed correctly.
      * **Link-Based Tasks:** A secure system that opens tasks in a popup window with a timer. It detects if the window is closed prematurely and prevents reward abuse.
      * **Cross-Tab Protection:** Prevents users from running multiple tasks simultaneously in different browser tabs.
  * **Financial Management:**
      * **Deposit System:** Users can choose from multiple deposit methods (e.g., USDT TRC20, BEP20) set by the admin, view QR codes and addresses, and upload proof of transaction.
      * **Withdrawal System:** Secure withdrawal system with level-based weekly limits. Withdrawals are only enabled after profile completion and KYC approval.
      * **Transaction History:** A detailed history page with filters for all financial activities (deposits, withdrawals, task rewards, commissions).
  * **Advanced Profile Management:** A dedicated profile page with a tabbed interface for updating personal information, changing passwords, and uploading a profile picture via AJAX.
  * **KYC Submission System:** A comprehensive KYC form for users to submit their documents and a selfie for verification.
  * **Level Upgrade System:** Users can view different membership levels and upgrade their accounts using their wallet balance.
  * **Dynamic UI Elements:**
      * **Scrolling Announcement Bar:** Displays the latest admin announcements on all pages.
      * **Notification Popup:** A bell icon in the header opens a modal with the latest announcement.
      * **Floating WhatsApp Button:** A persistent button for quick access to support.
      * **PWA "Install App" Feature:** Allows users to install the website as an app on their mobile or desktop devices.

### ⚙️ Admin Panel

  * **Secure Admin Authentication:** A separate, guarded login and session system for administrators.
  * **Dynamic Dashboard:** A central dashboard with real-time statistics on total users, revenue, pending KYC, and withdrawal requests, including a chart for weekly user registrations.
  * **Full User Management:** A comprehensive CRUD interface to manage users. Admins can search, sort (by balance, level), and edit all user details, including balance, level, and personal information. It also includes a feature to manually verify a user's email.
  * **Full Admin Management:** A dedicated section to add or delete other admin accounts. Deleting an admin automatically terminates all their active sessions.
  * **Level Management:** Full CRUD for creating and managing user levels, including setting the level name, icon, upgrade cost, daily task limit, and weekly withdrawal limit.
  * **Task Management:** Full CRUD for creating and managing tasks. Admins can set the task type (e.g., YouTube Like, TikTok Follow), title, URL, reward amount, duration, and assign it to a specific user level.
  * **Request Management:** Dedicated pages to manage and approve/reject KYC Submissions, Investment Requests, and Withdrawal Requests.
  * **Website Settings:** A central page to manage global site settings:
      * Website Name & Logo
      * Custom Header Scripts (for Google Analytics, etc.)
      * WhatsApp Support Number
      * Terms & Conditions and About Us pages (using a rich text editor).
  * **Comprehensive Logging:**
      * **Admin Activity Log:** Records every action taken by any admin (e.g., creating a user, approving a deposit, changing settings).
      * **User Activity Log:** Records key user actions (e.g., completing a task, upgrading a level).

-----

## Technology Stack

  * **Backend:** Laravel 11, PHP 8.2
  * **Database:** MySQL / SQLite
  * **Frontend:** Blade Templates, Tailwind CSS, Vanilla JavaScript (for dynamic features)
  * **Authentication:** Laravel Breeze (customized), Laravel Socialite (for Google Login)
  * **Email:** SMTP

-----

## Installation & Setup

Follow these steps to set up the project on your local machine.

1.  **Clone the repository:**

    ```bash
    git clone [https://your-repository-url.com/project.git](https://your-repository-url.com/project.git)
    cd project
    ```

2.  **Install PHP dependencies:**

    ```bash
    composer install
    ```

3.  **Create your environment file:**

    ```bash
    cp .env.example .env
    ```

4.  **Generate your application key:**

    ```bash
    php artisan key:generate
    ```

5.  **Configure your `.env` file:**
    Open the `.env` file and set up your database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) and mail server details (`MAIL_HOST`, etc.).

6.  **Run database migrations:**
    This will create all the necessary tables in your database.

    ```bash
    php artisan migrate
    ```

7.  **Create the storage link:**
    This makes your uploaded files (profile pictures, logos) publicly accessible.

    ```bash
    php artisan storage:link
    ```

8.  **Create a default Admin and Level:**
    Run Tinker and create the necessary initial data.

    ```bash
    php artisan tinker
    ```

    Inside Tinker, run these commands:

    ```php
    // Create a default level
    \App\Models\Level::create(['name' => 'Bronze', 'upgrade_cost' => 0, 'daily_task_limit' => 5, 'weekly_withdrawal_limit' => 10]);

    // Create a default admin user
    \App\Models\User::create(['username' => 'admin', 'email' => 'admin@example.com', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'role' => 'admin', 'level_id' => 1]);
    ```

9.  **Serve the application:**

    ```bash
    php artisan serve
    ```

    Your application will be available at `http://127.0.0.1:8000`.

-----

## Default Admin Credentials

  * **URL:** `/admin/login`
  * **Email:** `admin@example.com`
  * **Password:** `password`

<!-- end list -->
