<?php
require_once 'config.php';

if (!isStudent()) {
    header('Location: student_login.php');
    exit();
}

$message = '';
$error = '';
$student_id = $_SESSION['user_id'];

// Handle borrow request
if (isset($_POST['request_book'])) {
    $book_id = sanitize($_POST['book_id']);
    
    // Check if student already has this book
    $check_issued = mysqli_query($conn, "SELECT id FROM issued_books WHERE student_id = $student_id AND book_id = $book_id AND status = 'issued'");
    if (mysqli_num_rows($check_issued) > 0) {
        $error = "You already have this book borrowed!";
    } else {
        // Check if student already requested this book
        $check_request = mysqli_query($conn, "SELECT id FROM book_requests WHERE student_id = $student_id AND book_id = $book_id AND status = 'pending'");
        if (mysqli_num_rows($check_request) > 0) {
            $error = "You have already requested this book!";
        } else {
            // Create borrow request
            $request_date = date('Y-m-d');
            $query = "INSERT INTO book_requests (book_id, student_id, request_date, status) VALUES ('$book_id', '$student_id', '$request_date', 'pending')";
            
            if (mysqli_query($conn, $query)) {
                $message = "Book request submitted successfully! Please wait for admin approval.";
            } else {
                $error = "Failed to submit request. Please try again.";
            }
        }
    }
}

// Get filter parameters
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$author = isset($_GET['author']) ? sanitize($_GET['author']) : '';

// Build query
$where = "WHERE b.available_quantity > 0";
if ($search) {
    $where .= " AND (b.title LIKE '%$search%' OR b.isbn LIKE '%$search%' OR b.description LIKE '%$search%')";
}
if ($category) {
    $where .= " AND b.category_id = '$category'";
}
if ($author) {
    $where .= " AND b.author_id = '$author'";
}

$books_query = "
    SELECT b.*, a.author_name, c.category_name 
    FROM books b
    JOIN authors a ON b.author_id = a.id
    JOIN categories c ON b.category_id = c.id
    $where
    ORDER BY b.title ASC
";
$books = mysqli_query($conn, $books_query);

// Get categories and authors for filters
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name");
$authors = mysqli_query($conn, "SELECT * FROM authors ORDER BY author_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-size: 1.5em;
            font-weight: bold;
        }

        .navbar-menu {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .navbar-menu a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .navbar-menu a:hover {
            background: rgba(255,255,255,0.2);
        }

        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .page-header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .page-header h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .filters {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 15px;
        }

        .filters input,
        .filters select {
            padding: 12px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 1em;
        }

        .filters input:focus,
        .filters select:focus {
            outline: none;
            border-color: #667eea;
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .book-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .book-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2em;
            margin-bottom: 15px;
        }

        .book-card h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.2em;
        }

        .book-info {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 8px;
        }

        .book-info strong {
            color: #333;
        }

        .book-description {
            color: #888;
            font-size: 0.85em;
            line-height: 1.5;
            margin: 15px 0;
            max-height: 60px;
            overflow: hidden;
        }

        .availability {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }

        .available-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            background: #d4edda;
            color: #155724;
        }

        .btn-borrow {
            padding: 8px 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9em;
        }

        .btn-borrow:hover {
            opacity: 0.9;
        }

        .btn-borrow:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .badge-requested {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            background: #fff3cd;
            color: #856404;
        }

        .badge-borrowed {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            background: #d1ecf1;
            color: #0c5460;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .empty-state h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #666;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .filters {
                grid-template-columns: 1fr;
            }

            .books-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">📚 Library System</div>
        <div class="navbar-menu">
            <a href="student_dashboard.php">Dashboard</a>
            <a href="browse_books.php">Browse Books</a>
            <a href="my_books.php">My Books</a>
            <a href="student_profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Browse Available Books</h1>
            
            <?php if ($message): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-top: 20px;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-top: 20px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="GET" class="filters" style="margin-top: 20px;">
                <input type="text" name="search" placeholder="Search by title, ISBN, or description..." value="<?php echo $search; ?>">
                
                <select name="category">
                    <option value="">All Categories</option>
                    <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $category == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo $cat['category_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <select name="author" onchange="this.form.submit()">
                    <option value="">All Authors</option>
                    <?php while ($auth = mysqli_fetch_assoc($authors)): ?>
                        <option value="<?php echo $auth['id']; ?>" <?php echo $author == $auth['id'] ? 'selected' : ''; ?>>
                            <?php echo $auth['author_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>
        </div>

        <?php if (mysqli_num_rows($books) > 0): ?>
            <div class="books-grid">
                <?php while ($book = mysqli_fetch_assoc($books)): 
                    // Check if student already borrowed this book
                    $check_borrowed = mysqli_query($conn, "SELECT id FROM issued_books WHERE student_id = $student_id AND book_id = {$book['id']} AND status = 'issued'");
                    $is_borrowed = mysqli_num_rows($check_borrowed) > 0;
                    
                    // Check if student already requested this book
                    $check_requested = mysqli_query($conn, "SELECT id FROM book_requests WHERE student_id = $student_id AND book_id = {$book['id']} AND status = 'pending'");
                    $is_requested = mysqli_num_rows($check_requested) > 0;
                ?>
                    <div class="book-card">
                        <div class="book-icon">📖</div>
                        <h3><?php echo $book['title']; ?></h3>
                        <div class="book-info"><strong>Author:</strong> <?php echo $book['author_name']; ?></div>
                        <div class="book-info"><strong>Category:</strong> <?php echo $book['category_name']; ?></div>
                        <div class="book-info"><strong>ISBN:</strong> <?php echo $book['isbn']; ?></div>
                        <?php if ($book['publication_year']): ?>
                            <div class="book-info"><strong>Year:</strong> <?php echo $book['publication_year']; ?></div>
                        <?php endif; ?>
                        <?php if ($book['description']): ?>
                            <div class="book-description"><?php echo $book['description']; ?></div>
                        <?php endif; ?>
                        <div class="availability">
                            <span class="available-badge"><?php echo $book['available_quantity']; ?> available</span>
                            
                            <?php if ($is_borrowed): ?>
                                <span class="badge-borrowed">Already Borrowed</span>
                            <?php elseif ($is_requested): ?>
                                <span class="badge-requested">Request Pending</span>
                            <?php else: ?>
                                <form method="POST" style="margin: 0;">
                                    <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                    <button type="submit" name="request_book" class="btn-borrow">Request to Borrow</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h2>No books found</h2>
                <p>Try adjusting your search or filter criteria</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>