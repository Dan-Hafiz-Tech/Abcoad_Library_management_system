# 📚 Library Management System

A comprehensive web-based library management system built with PHP, MySQL, HTML, CSS, and JavaScript.

## 🌟 Features

### Admin Features
- **Dashboard**: View comprehensive statistics including total categories, authors, books, issued books, and registered students
- **Category Management**: Add, update, and delete book categories
- **Author Management**: Manage author information
- **Book Management**: Complete CRUD operations for books with details like title, author, category, ISBN, quantity
- **Issue & Return System**: Issue books to students and track returns with due dates
- **Student Management**: Search and view complete student profiles and borrowing history
- **Account Management**: Update admin password and profile information
- **Overdue Tracking**: Monitor and manage overdue books

### Student Features
- **Registration & Login**: Self-registration with auto-generated unique student ID
- **Dashboard**: Overview of borrowed books, return dates, and available books
- **Browse Books**: Search and filter books by title, author, category, or ISBN
- **Profile Management**: Update personal information and profile picture
- **Password Management**: Change password and recover forgotten passwords
- **Borrowing History**: View complete history of borrowed and returned books
- **Due Date Tracking**: Monitor return dates and receive overdue notifications

## 🛠️ Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Server**: Apache (XAMPP/WAMP/LAMP)

## 📋 Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache Web Server
- Web browser (Chrome, Firefox, Safari, Edge)

## 🚀 Installation Guide

### Step 1: Install XAMPP/WAMP/LAMP

Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)

### Step 2: Setup Database

1. Start Apache and MySQL from XAMPP Control Panel
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Create a new database named `library_management`
4. Import the SQL file or run the database schema provided

### Step 3: Configure Database Connection

Edit `config.php` and update these values if needed:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'library_management');
```

### Step 4: Setup Project Files

1. Copy all project files to your web server directory:
   - For XAMPP: `C:\xampp\htdocs\library`
   - For WAMP: `C:\wamp\www\library`
   - For LAMP: `/var/www/html/library`

2. Ensure proper file permissions (Linux/Mac):
```bash
chmod -R 755 /var/www/html/library
```

### Step 5: Initialize Database

1. Run the SQL schema to create all tables
2. Default admin credentials will be created:
   - **Username**: admin
   - **Password**: password

### Step 6: Access the System

Open your web browser and navigate to:
```
http://localhost/library/
```

## 📁 Project Structure

```
library/
│
├── config.php                 # Database configuration
├── index.php                  # Landing page
├── logout.php                 # Logout functionality
│
├── admin_login.php            # Admin login page
├── admin_dashboard.php        # Admin dashboard
├── manage_categories.php      # Category management
├── manage_authors.php         # Author management
├── manage_books.php           # Book management
├── issue_book.php            # Book issuing interface
├── return_book.php           # Book return interface
├── manage_students.php        # Student management
├── admin_profile.php          # Admin profile page
│
├── student_register.php       # Student registration
├── student_login.php          # Student login
├── student_dashboard.php      # Student dashboard
├── browse_books.php           # Browse available books
├── my_books.php              # Student's borrowed books
├── student_profile.php        # Student profile page
├── forgot_password.php        # Password recovery
│
├── css/
│   └── styles.css            # Custom styles
│
├── js/
│   └── scripts.js            # JavaScript functions
│
└── uploads/                   # Profile pictures directory
```

## 🔐 Default Login Credentials

### Admin
- **Username**: admin
- **Password**: password

### Student
Students must register first. Upon registration, a unique student ID is automatically generated.

## 💡 Usage Guide

### For Administrators

1. **Login**: Use admin credentials to access the admin panel
2. **Add Categories**: Navigate to Categories → Add new categories
3. **Add Authors**: Go to Authors → Add author information
4. **Add Books**: Navigate to Books → Add book details including title, author, category, ISBN, and quantity
5. **Issue Books**: 
   - Go to Issue Book
   - Search for student by Student ID
   - Select book and set due date
   - Confirm issuing
6. **Return Books**: Mark books as returned when students return them
7. **Monitor**: Use dashboard to track statistics and overdue books

### For Students

1. **Register**: Create an account (Student ID is auto-generated)
2. **Login**: Use email and password to access your dashboard
3. **Browse Books**: Navigate to Browse Books to see available books
4. **Filter**: Use search and filters to find specific books
5. **Track**: Monitor your borrowed books and due dates from dashboard
6. **Profile**: Update your profile information and change password

## 🔄 Database Schema

### Key Tables

- **admin**: Administrator accounts
- **students**: Student accounts and profiles
- **categories**: Book categories
- **authors**: Book authors
- **books**: Book catalog
- **issued_books**: Book borrowing records
- **password_reset**: Password recovery tokens

## 🎨 Customization

### Colors
The system uses a purple gradient theme. To customize:

Edit the gradient in CSS:
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

### Book Issue Duration
Default: 14 days

To modify, edit the issue book form or update the query in `issue_book.php`:
```php
$due_date = date('Y-m-d', strtotime('+14 days'));
```

### Student ID Format
Current format: STU{YEAR}{4-DIGIT-RANDOM}

Example: STU20241234

To modify, edit `generateStudentId()` function in `config.php`

## 🐛 Troubleshooting

### Database Connection Error
- Verify MySQL is running
- Check database credentials in `config.php`
- Ensure database exists

### Login Issues
- Clear browser cache and cookies
- Verify credentials
- Check if user exists in database

### File Upload Issues
- Check `uploads/` directory permissions
- Verify PHP upload settings in `php.ini`

## 🔒 Security Features

- Password hashing using PHP's `password_hash()`
- SQL injection prevention using prepared statements and sanitization
- Session management for authentication
- Input validation and sanitization
- Role-based access control (Admin/Student)

## 📱 Responsive Design

The system is fully responsive and works on:
- Desktop computers
- Tablets
- Mobile phones

## 🚧 Future Enhancements

- Email notifications for overdue books
- Book reservation system
- Advanced reporting and analytics
- QR code for book identification
- Multi-language support
- Book recommendations
- Fine calculation for overdue books
- Export reports to PDF/Excel

## 📄 License

This project is open-source and available for educational purposes.

## 👥 Support

For issues or questions:
1. Check the troubleshooting section
2. Review the documentation
3. Contact system administrator

## 🙏 Credits

Developed as a comprehensive library management solution for educational institutions.

---

**Version**: 1.0.0  
**Last Updated**: December 2024  
**Status**: Production Ready

## 📞 Contact

For technical support or inquiries about the system, please contact your system administrator.

---

Happy Reading! 📚✨