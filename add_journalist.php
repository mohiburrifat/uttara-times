<?php
session_start();

// Check if editor is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header("Location: login.php");
    exit();
}

require 'db.php';
require 'menubar3.php';

$message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'journalist')");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Journalist added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Journalist | Uttara-Times</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
            margin: 0;
            padding: 30px;
        }

        .card {
            background: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 25px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .form-label {
            font-weight: 600;
        }

        .btn-gradient {
            background: linear-gradient(to right, #6fb1fc, #4e90e2);
            border: none;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            transition: 0.3s ease;
        }

        .btn-gradient:hover {
            opacity: 0.9;
        }

        .page-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .alert {
            max-width: 600px;
            margin: 0 auto 20px auto;
        }
    </style>
</head>

<body>

    <h2 class="page-title">Add New Journalist</h2>
    <?= $message ?>
    <div class="card">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input required type="text" name="username" class="form-control" id="username" placeholder="Enter username">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input required type="email" name="email" class="form-control" id="email" placeholder="Enter email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input required type="password" name="password" class="form-control" id="password" placeholder="Enter password">
            </div>
            <button type="submit" class="btn btn-gradient">Add Journalist</button>
        </form>
    </div>

</body>

</html>