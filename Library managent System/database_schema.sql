-- Library Management System Database Schema

CREATE DATABASE library_management;
USE library_management;

-- Admin Table
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Students Table
CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    profile_picture VARCHAR(255) DEFAULT 'default.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories Table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Authors Table
CREATE TABLE authors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    author_name VARCHAR(100) NOT NULL,
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Books Table
CREATE TABLE books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    isbn VARCHAR(20) UNIQUE NOT NULL,
    author_id INT NOT NULL,
    category_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    available_quantity INT NOT NULL DEFAULT 1,
    publication_year YEAR,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Issued Books Table
CREATE TABLE issued_books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    book_id INT NOT NULL,
    student_id INT NOT NULL,
    issue_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE NULL,
    status ENUM('issued', 'returned') DEFAULT 'issued',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Password Reset Tokens Table
CREATE TABLE password_reset (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(100) NOT NULL,
    user_type ENUM('admin', 'student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL
);

-- Insert Default Admin
INSERT INTO admin (username, email, password, full_name) 
VALUES ('admin', 'admin@library.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Admin');
-- Default password: password

-- Sample Categories
INSERT INTO categories (category_name, description) VALUES
('Fiction', 'Fictional literature and novels'),
('Non-Fiction', 'Educational and factual books'),
('Science', 'Scientific publications and research'),
('Technology', 'Computer science and technology books'),
('History', 'Historical documents and books');

-- Sample Authors
INSERT INTO authors (author_name, bio) VALUES
('J.K. Rowling', 'British author, best known for Harry Potter series'),
('Stephen King', 'American author of horror and suspense novels'),
('Isaac Asimov', 'Science fiction author and biochemistry professor'),
('Malcolm Gladwell', 'Canadian journalist and author');

-- Sample Books


INSERT INTO books(title, isbn, author_id, category_id, quantity, available_quantity, publication_year, description) 
VALUES
-- AGRICULTURE (15)
('Principles of Crop Production', '978-0000000001', 5, 6, 5, 5, 2015, 'Basic principles of crop farming'),
('Soil Science Fundamentals', '978-0000000002', 5, 6, 4, 4, 2016, 'Soil fertility and management'),
('Modern Farming Techniques', '978-0000000003', 5, 6, 6, 6, 2018, 'Advanced agricultural practices'),
('Agricultural Economics', '978-0000000004', 9, 6, 3, 3, 2017, 'Economics of agriculture'),
('Irrigation and Water Management', '978-0000000005', 5, 6, 4, 4, 2014, 'Water use in agriculture'),
('Organic Farming Basics', '978-0000000006', 5, 6, 5, 5, 2019, 'Organic agriculture principles'),
('Agronomy for Beginners', '978-0000000007', 5, 6, 6, 6, 2013, 'Introductory agronomy'),
('Farm Machinery and Tools', '978-0000000008', 5, 6, 3, 3, 2012, 'Agricultural machines'),
('Pest Management in Crops', '978-0000000009', 5, 6, 4, 4, 2016, 'Crop pest control'),
('Seed Technology', '978-0000000010', 5, 6, 5, 5, 2015, 'Seed production and storage'),

-- ANIMAL HEALTH (10)
('Veterinary Medicine Basics', '978-0000000011', 11, 7, 6, 6, 2014, 'Introduction to veterinary medicine'),
('Animal Disease Control', '978-0000000012', 11, 7, 4, 4, 2016, 'Prevention of animal diseases'),
('Livestock Health Management', '978-0000000013', 6, 7, 5, 5, 2017, 'Health care of livestock'),
('Animal Nutrition', '978-0000000014', 7, 7, 6, 6, 2015, 'Feeding and nutrition'),
('Poultry Health Guide', '978-0000000015', 11, 7, 3, 3, 2018, 'Poultry disease management'),
('Dairy Animal Health', '978-0000000016', 6, 7, 4, 4, 2016, 'Health of dairy animals'),
('Zoonotic Diseases', '978-0000000017', 11, 7, 5, 5, 2019, 'Diseases transmitted from animals'),
('Animal Anatomy and Physiology', '978-0000000018', 11, 7, 6, 6, 2013, 'Animal body systems'),
('Veterinary Pharmacology', '978-0000000019', 11, 7, 4, 4, 2017, 'Drugs used in animals'),
('Animal Welfare and Ethics', '978-0000000020', 11, 7, 3, 3, 2020, 'Ethics in animal care'),

-- PARAVETERINARY (8)
('Paraveterinary Training Manual', '978-0000000021', 12, 8, 6, 6, 2015, 'Guide for paravets'),
('Basic Animal First Aid', '978-0000000022', 12, 8, 5, 5, 2016, 'Emergency animal care'),
('Livestock Handling Techniques', '978-0000000023', 12, 8, 4, 4, 2017, 'Safe handling of animals'),
('Animal Vaccination Guide', '978-0000000024', 12, 8, 6, 6, 2018, 'Vaccination procedures'),
('Farm Animal Management', '978-0000000025', 12, 8, 5, 5, 2014, 'Managing farm animals'),
('Paravet Clinical Practices', '978-0000000026', 12, 8, 4, 4, 2019, 'Clinical skills'),
('Disease Diagnosis for Paravets', '978-0000000027', 12, 8, 3, 3, 2020, 'Disease identification'),
('Extension Services in Livestock', '978-0000000028', 12, 8, 5, 5, 2016, 'Livestock extension'),

-- HORTICULTURE (7)
('Introduction to Horticulture', '978-0000000029', 10, 9, 6, 6, 2015, 'Plant cultivation basics'),
('Fruit Crop Production', '978-0000000030', 10, 9, 4, 4, 2016, 'Fruit farming'),
('Vegetable Gardening', '978-0000000031', 10, 9, 5, 5, 2017, 'Vegetable production'),
('Greenhouse Technology', '978-0000000032', 10, 9, 3, 3, 2018, 'Protected cultivation'),
('Ornamental Plants', '978-0000000033', 10, 9, 4, 4, 2014, 'Flower crops'),
('Plant Propagation', '978-0000000034', 10, 9, 6, 6, 2019, 'Plant multiplication'),
('Landscape Gardening', '978-0000000035', 10, 9, 5, 5, 2020, 'Garden design'),

-- COMPUTER & SLT (10)
('Introduction to Computer Science', '978-0000000036', 8, 10, 6, 6, 2015, 'Basics of computing'),
('Programming with Python', '978-0000000037', 14, 10, 5, 5, 2018, 'Python programming'),
('Data Structures and Algorithms', '978-0000000038', 14, 10, 6, 6, 2017, 'Algorithms fundamentals'),
('Operating Systems Concepts', '978-0000000039', 9, 10, 4, 4, 2016, 'OS principles'),
('Computer Networks', '978-0000000040', 9, 10, 5, 5, 2019, 'Networking basics'),
('Laboratory Techniques', '978-0000000041', 13, 11, 6, 6, 2014, 'Lab procedures'),
('Medical Laboratory Science', '978-0000000042', 13, 11, 4, 4, 2016, 'Medical lab practices'),
('Microbiology Techniques', '978-0000000043', 13, 11, 5, 5, 2018, 'Microbiology lab work'),
('Biochemistry Lab Manual', '978-0000000044', 13, 11, 3, 3, 2017, 'Biochemistry experiments'),
('Quality Control in Laboratories', '978-0000000045', 13, 11, 4, 4, 2020, 'Lab quality management');
