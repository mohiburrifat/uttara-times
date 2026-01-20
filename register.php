<?php
require 'db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    $role = 'user';
    if (!$username || !$password || !$confirm) {
        $errors[] = 'All fields are required.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    } else {
        $stmt = $conn->prepare('SELECT user_id FROM users WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'Username already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $username, $hash, $role);
            $stmt->execute();
            header('Location: login.php?registered=1');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | Uttara-Times</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card p-4">
                    <h3 class="mb-3 text-center">Create Account</h3>
                    <?php if ($errors): ?>
                        <div class="alert alert-danger">
                            <?= implode('<br>', $errors) ?>
                        </div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm" class="form-control" required>
                        </div>
                        <!-- Role selection removed: all new accounts are 'reader' -->
                        <button class="btn btn-primary w-100">Register</button>
                    </form>
                    <div class="mt-3 text-center">
                        Already have an account? <a href="login.php">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>