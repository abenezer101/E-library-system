# Melu'e Foundation E-Library System

An inclusive digital library platform built for the **Melu'e Foundation** — an organization dedicated to supporting children living with autism and other special needs, and their parents and guardians.

The system connects a **Telegram bot**, a **PHP admin dashboard**, and a **public booklist web application** to a shared MySQL/MariaDB database, enabling members to register, browse, download and rent books, while administrators manage the catalog, memberships, announcements and book circulation.

---

## Table of Contents

- [Features](#features)
- [System Architecture](#system-architecture)
- [Technology Stack](#technology-stack)
- [Repository Structure](#repository-structure)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
  - [1. Database](#1-database)
  - [2. Admin Dashboard](#2-admin-dashboard)
  - [3. Telegram Bot](#3-telegram-bot)
  - [4. Booklist Web Application](#4-booklist-web-application)
- [Configuration](#configuration)
- [Usage](#usage)
- [Database Schema](#database-schema)
- [Security Recommendations](#security-recommendations)
- [Troubleshooting](#troubleshooting)
- [Credits & License](#credits--license)

---

## Features

### Member Experience (Telegram Bot)

- **Registration & Login** — members register with their Telegram account, share a phone number, and submit a valid ID (Kebele ID, Fayda National ID, School ID, or Passport) as a photo for admin approval.
- **Bilingual interface** — full English and Amharic language support, switchable from account settings.
- **E-Book downloads** — members request books with a unique voucher code and receive the PDF directly in the chat.
- **Online catalog** — a front-end booklist web app (opened from the bot) to browse the collection by title, author, and category.
- **Book renting** — members can join a waitlist to rent a physical copy; notifications are sent when a book is issued to them.
- **Announcements** — live push notifications delivered to all registered members, plus an on-demand announcements view.
- **Account management** — view/edit profile name and preferred language.

### Administrator Experience (Dashboard)

- **Secure admin login** with a dedicated session.
- **Membership management** — view registered users, review submitted ID photos, approve or decline registrations, edit and delete user accounts.
- **Catalog management** — add, view, edit and remove books (cover image + PDF).
- **Book circulation** — issue books to waitlisted members with due dates, track issued books, and notify members automatically.
- **Messaging** — send individual or filtered notifications (e.g., members with overdue rentals) to Telegram users.
- **Announcement publishing** — create, edit and delete announcements with attached images.
- **Bot health monitoring** — a dashboard page that checks whether the Telegram bot is online.

### Public Booklist Web App

- Responsive, mobile-first catalog page with live search, category filtering, and cover-image carousels.

---

## System Architecture

```
┌────────────────────┐     ┌───────────────────────────┐     ┌─────────────────────┐
│   Telegram Users   │ ──► │     Telegram Bot          │ ──► │                     │
│ (Members/Patrons)  │ ◄── │     (Python / mainBOT.py) │ ◄── │    MySQL / MariaDB  │
└────────────────────┘     └───────────────────────────┘     │      Database       │
                                                            │    ("elibrary")     │
┌────────────────────┐     ┌───────────────────────────┐     │                     │
│  Public Booklist   │ ──► │       PHP Web Server       │ ──► │                     │
│   Web Application  │     │   (Apache / XAMPP)         │     │                     │
└────────────────────┘     │  - Member Dashboard        │     └─────────────────────┘
                           │  - Static Booklist         │
                           └───────────────────────────┘
```

- The **Telegram bot** interacts with the database directly and handles all member-facing logic.
- The **PHP admin dashboard** runs on Apache (XAMPP) and shares the same database.
- The **booklist web app** is a static front end; the "advanced" variant uses PHP to search a *separate* `library` database.

---

## Technology Stack

| Layer           | Technology                                                                                         |
| --------------- | -------------------------------------------------------------------------------------------------- |
| Backend (bot)   | Python 3, `pyTelegramBotAPI` (telebot), `mysql-connector-python`, `requests`                       |
| Backend (admin) | PHP 8.2, MySQLi, Apache/XAMPP                                                                      |
| Database        | MySQL / MariaDB 10.4+ (`utf8mb4`)                                                                  |
| Frontend        | HTML5, CSS3, vanilla JavaScript, Bootstrap 3, DataTables, Tailwind CSS, Swiper, GSAP, ScrollReveal |
| Tooling         | XAMPP (Apache + MySQL), Windows batch script                                                       |

---

## Repository Structure

```
.
├── mainBOT.py                          # Telegram bot entry point (all bot logic)
├── schema.sql                          # Database schema (importable, no data/credentials)
├── elibrary.sql                        # Full local dump (schema + data) — DO NOT COMMIT
├── requirements.txt                    # Python dependencies for the bot
├── Melu-e Foundation E-library copy.bat# One-click local startup (Windows)
├── Dashboard/                          # PHP admin panel (XAMPP)
│   ├── index.php                       #   Admin login page
│   ├── home.php / session.php          #   Auth helpers / session guard
│   ├── dbcon.php                       #   Database connection (root-level pages)
│   └── pages/                          #   Dashboard pages
│       ├── index.php                   #   Main dashboard view
│       ├── includes/nav.php            #   Shared navigation
│       ├── addbook.php / addedbook.php #   Add a book (+ handler)
│       ├── viewbook.php / editbook.php #   List / edit books
│       ├── issueBook.php / issuedBook.php  #   Issue books + handler
│       ├── viewuser.php / deleteview.php   #   List / delete users
│       ├── resgistration_approval.php / approve.php  # Registration approval
│       ├── sendmessage.php / message.php    #   Notify members
│       ├── makeannouncement.php / announce.php # Announcements
│       ├── Checkbotstatus.php          #   Bot health checker
│       ├── counter/                    #   Dashboard stat counters
│       └── vendor/                     #   JS/CSS vendor assets
├── Booklist webapp/                    # Public book catalog front end
│   ├── index.html / style.css / app.js #   Main catalog page
│   ├── book details/                   #   Individual book page template
│   └── advanced book details/          #   Search-backed variant (PHP)
│       └── search.php / admin.php      #   Backend endpoints
└── failed/                             # Early-stage prototypes (not used in production)
```

---

## Prerequisites

- **Windows** local machine (or any OS with a PHP + MySQL stack).
- **XAMPP** (Apache + MySQL + PHP 8.x) installed at a known path.
- **Python 3.8+** installed and added to `PATH`.
- A **Telegram Bot token** obtained from [@BotFather](https://t.me/BotFather).
- Network access to the Telegram API from the host running the bot.

---

## Installation

### 1. Database

1. Start **Apache** and **MySQL** from the XAMPP Control Panel.
2. Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
3. Create a database named `elibrary` (default `utf8mb4_general_ci`).
4. Import `schema.sql` (structure only — no data or credentials).

   ```sql
   mysql -u root -p elibrary < schema.sql
   ```

   Then create your initial admin account (see the commented bootstrap section at the bottom of `schema.sql`).

5. *(Optional, advanced booklist variant)* Create a second database named `library` and add a `books(title, author, genre, cover)` table.

> **Note:** `schema.sql` is the version to keep in the repository. The full dump in `elibrary.sql` contains live admin credentials and should **not** be committed.

### 2. Admin Dashboard

1. Copy the entire project to your web root, e.g. `C:\xampp\htdocs\`.
2. Verify MySQL credentials in:
   - `Dashboard/dbcon.php`
   - `Dashboard/pages/dbconnect.php`
3. Open the dashboard:  
   `http://localhost/<project-folder>/Dashboard/index.php`

### 3. Telegram Bot

1. Install Python dependencies:
   
   ```bash
   pip install pyTelegramBotAPI mysql-connector-python requests keyboard
   ```
   
   (Or create a `requirements.txt` with these packages and run `pip install -r requirements.txt`.)

2. Set your bot token in `mainBOT.py`:
   
   ```python
   bot_token = "PASTE_YOUR_TOKEN_HERE"
   ```

3. Confirm the database configuration block in `mainBOT.py` points to your MySQL instance:
   
   ```python
   db_config = {
       'user': 'root',
       'host': 'localhost',
       'database': 'elibrary',
   }
   ```

4. Run the bot:
   
   ```bash
   python mainBOT.py
   ```

### 4. Booklist Web Application

The static catalog (`Booklist webapp/index.html`) works without a backend.

For the search-backed variant (`advanced book details/`):

1. Ensure the `library` database exists with a `books` table.
2. Point `search.php` / `admin.php` at your MySQL credentials (currently `root` / empty password).
3. Serve the folder through Apache and open it in a browser.

---

## Configuration

Recommended approach to avoid committing secrets:

- **Do not commit your real Telegram bot token.** Keep `bot_token` empty in the repository and inject it at runtime (environment variable, local config file, or a git-ignored `config.py`).

- **Do not store production database credentials in PHP files.** Move them to an ignored config file (e.g., `config.inc.php`) or environment variables.

- Add the following to your `.gitignore` at minimum:
  
  ```gitignore
  elibrary.sql
  __pycache__/
  *.pyc
  config.py
  .env
  Dashboard/pages/valid_ids/
  Dashboard/pages/profile_pictures/
  Dashboard/pages/pdfs/
  Dashboard/pages/thumbnails/
  .cursor/
  failed/
  ```

---

## Usage

### One-click startup (Windows)

Double-click **`Melu-e Foundation E-library copy.bat`**. This will:

1. Start the XAMPP control panel and the Apache/MySQL services.
2. Open the admin dashboard in the default browser.
3. Launch the Python bot in a new console window.

> Note: paths inside the script are hard-coded (`C:\xampp\...`) — edit them if your XAMPP installation differs.

### Manual startup

1. Start Apache + MySQL (XAMPP Control Panel).
2. Run the bot: `python mainBOT.py`.
3. Open the dashboard: `http://localhost/<project-folder>/Dashboard/index.php`.

---

## Database Schema

| Table          | Purpose                                                                                                                 |
| -------------- | ----------------------------------------------------------------------------------------------------------------------- |
| `admin`        | Administrator accounts (username, password) for the dashboard login.                                                    |
| `users`        | Registered members: Telegram ID, name, phone, submitted valid ID, profile picture, approval status, preferred language. |
| `books`        | E-book catalog: title, publisher, year, cover thumbnail, PDF path.                                                      |
| `issued_books` | Physical book circulation: member, book details, due/issue dates, waitlist & rental flags.                              |
| `announcement` | Announcements: author, title, message, attached image, timestamp.                                                       |
| `messages`     | Outbound user notifications queued for the bot to deliver (user messages and rental notices).                           |

---

## Security Recommendations

The current implementation is a development build and includes several weaknesses that should be addressed **before deploying publicly or pushing the repository to a remote**:

1. **Enable authentication** — the dashboard session guard in `Dashboard/pages/index.php` is currently commented out. Require an authenticated session on every admin page (e.g., `require 'session.php'`).
2. **Hash passwords** — store admin passwords using `password_hash()` / `password_verify()` and remove the plaintext rows from any committed SQL dump.
3. **Use prepared statements** — replace string-concatenated SQL with parameterized queries (MySQLi prepared statements or PDO) to eliminate SQL injection.
4. **Validate uploads** — allowlist file extensions and MIME types, randomize stored filenames, and store uploaded content outside the web root where possible.
5. **Add CSRF protection** — include per-session tokens on all state-changing forms.
6. **Sanitize output** — escape all user-supplied data with `htmlspecialchars()` before rendering.
7. **Rotate default credentials** — change the built-in admin accounts and the `root`/empty MySQL password before first deployment.
8. **Git hygiene** — never commit database dumps containing live credentials, uploaded member ID images, or the Telegram bot token.

---

## Troubleshooting

| Problem                          | Likely fix                                                                                                   |
| -------------------------------- | ------------------------------------------------------------------------------------------------------------ |
| `Connection Failed` in dashboard | MySQL not running, or wrong credentials in `dbconnect.php`.                                                  |
| Bot starts but doesn't respond   | Invalid `bot_token`, or the bot wasn't set up via `@BotFather`.                                              |
| Book PDF/thumbnail not found     | Stored `pdf_path` does not match the actual file location under `Dashboard/pages/`.                          |
| Bot health shows "not running"   | `Checkbotstatus.php` polls a hard-coded IP/port — either enable the bot's health endpoint or update the URL. |
| `.bat` script fails              | XAMPP not installed at `C:\xampp` or the folder path contains different characters.                          |

---

## Credits & License

Developed in collaboration with:

- **Abenezer Abera**

Built for the **Melu'e Foundation** to support children with autism and other special needs by making knowledge inclusive and accessible.

This project is intended for educational and non-profit use in support of the Foundation's mission. Contact the Foundation (`https://melu-e.org`) for licensing questions.