# MediaBay Login System Setup

This document explains how to set up and use the login and registration system for MediaBay.

## Prerequisites

1. **XAMPP/WAMP/MAMP** or any local server with PHP and MySQL
2. **PHP 7.4+** with MySQL extension enabled
3. **MySQL/MariaDB** database server

## Setup Instructions

### 1. Database Setup

**Option A: Using the PHP Setup Script (Recommended)**
1. Start your local server (XAMPP/WAMP/MAMP)
2. Navigate to `http://localhost/your-project-folder/setup_database.php`
3. This will automatically create the database and tables

**Option B: Using SQL File**
1. Open phpMyAdmin or your MySQL client
2. Import the `database_setup.sql` file
3. Or run the SQL commands manually

### 2. Database Configuration

The database configuration is in `config.php`:
```php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');  // Change if you have a password
define('DB_NAME', 'demo');
```

**Update these values if needed:**
- `DB_USERNAME`: Your MySQL username (default: 'root')
- `DB_PASSWORD`: Your MySQL password (default: empty)
- `DB_NAME`: Database name (default: 'demo')

### 3. File Structure

```
MediaBay All Out/
├── index.php              # Main file with login/registration forms
├── config.php             # Database configuration
├── setup_database.php     # Database setup script
├── database_setup.sql     # SQL setup file
└── assets/
    ├── css/
    │   └── main.css       # Styles for forms and messages
    └── js/
        └── main.js        # JavaScript for form handling
```

## Features

### User Registration
- Username validation (3-20 characters, alphanumeric with underscores)
- Email validation and uniqueness check
- Password requirements (minimum 6 characters)
- Password confirmation
- Secure password hashing using PHP's `password_hash()`

### User Login
- Login with username OR email
- Secure password verification
- Session management
- Remember user login state

### Security Features
- SQL injection prevention using prepared statements
- Password hashing with bcrypt
- Session-based authentication
- Input validation and sanitization
- CSRF protection (basic)

### User Interface
- Responsive design
- Form validation with error messages
- Success/error message display
- Password visibility toggle
- User profile display when logged in

## Usage

### For Users

1. **Registration:**
   - Click "Sign Up" button
   - Fill in username, email, and password
   - Confirm password
   - Submit form

2. **Login:**
   - Click "Login" button
   - Enter username/email and password
   - Submit form

3. **Logout:**
   - Click "Logout" button in user profile

### For Developers

**Test Account (created by setup script):**
- Username: `testuser`
- Email: `test@example.com`
- Password: `test123`

**Database Table Structure:**
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Customization

### Styling
- Edit `assets/css/main.css` to customize form appearance
- Look for `.auth-form`, `.error-message`, `.success-message` classes

### Validation Rules
- Modify validation logic in `index.php` (lines 1-150)
- Update form requirements in HTML forms

### Database Fields
- Add new fields to the `users` table
- Update PHP processing code accordingly

## Troubleshooting

### Common Issues

1. **"Could not connect" error:**
   - Check if MySQL is running
   - Verify database credentials in `config.php`

2. **"Table doesn't exist" error:**
   - Run `setup_database.php` or import `database_setup.sql`

3. **Forms not submitting:**
   - Ensure file is named `index.php` (not `index.html`)
   - Check that PHP is enabled on your server

4. **Styling issues:**
   - Verify CSS files are loading correctly
   - Check browser console for errors

### Security Notes

- Change default database credentials in production
- Use HTTPS in production
- Consider adding rate limiting for login attempts
- Implement password reset functionality
- Add email verification for new registrations

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Verify all prerequisites are met
3. Check server error logs
4. Ensure PHP and MySQL are properly configured
