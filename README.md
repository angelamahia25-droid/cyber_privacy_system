# cyber project
# ShieldScan v1.0 - Data Privacy Risk Control System

A lightweight, pro-active cybersecurity tool designed for small businesses to monitor and remediate data privacy vulnerabilities.

##  Technical Stack
- **Language:** PHP 8.x
- **Database:** SQLite (Self-contained, no external server required)
- **Frontend:** Tailwind CSS (via CDN)
- **Security:** PDO Prepared Statements (SQLi Protection) & Bcrypt Password Hashing.

## 🛠️ Features
- **Authorized Analyst Portal:** Secure login/registration with System Secret Key requirement.
- **Privacy Asset Inventory:** Full CRUD management of data resources.
- **Vulnerability Monitor:** Automated scanner identifying High-Sensitivity Plaintext exposures.
- **Remediation Engine:** One-click encryption simulation to mitigate detected risks.
- **System Audit Trail:** Historical logging of all detected security events.

## 💻 Installation & Setup
1. Clone the repository into a PHP-enabled environment (e.g., GitHub Codespaces).
2. Run `php init_db.php` in the terminal to initialize the database schema.
3. Start the local server: `php -S 0.0.0.0:8080`.
4. Access the system via the generated URL.

**Default Admin Credentials:**
- **User:** admin
- **Pass:** admin123
- **System Secret:** SHIELD-2026