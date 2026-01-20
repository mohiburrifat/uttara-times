<?php
session_start();
require 'db.php'; // Assumes you have a separate db connection file

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate input
    if (empty($username) || empty($password) || empty($role)) {
        header("Location: login.php?error=" . urlencode("Please fill in all fields."));
        exit();
    }

    // Prepare the query to check the user based on username and role
    $stmt = $conn->prepare("SELECT user_id, username, password_hash, role FROM users WHERE username = ? AND role = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verify user
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check if password matches
        if (password_verify($password, $user['password_hash'])) {
            // Login successful
            $_SESSION['user_id'] = $user['user_id']; // Use user_id from the database
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Log the login activity (safely check if table exists)
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $tableCheck = $conn->query("SHOW TABLES LIKE 'user_logins'");
            if ($tableCheck && $tableCheck->num_rows > 0) {
                $login_stmt = $conn->prepare("INSERT INTO user_logins (user_id, ip_address, user_agent, status) VALUES (?, ?, ?, 'success')");
                $login_stmt->bind_param("iss", $user['user_id'], $ip_address, $user_agent);
                $login_stmt->execute();
            }

            // Redirect based on the role
            if ($role === 'editor') {
                header("Location: editor_dashboard.php");
            } elseif ($role === 'journalist') {
                header("Location: journalist_dashboard.php");
            } elseif ($role === 'user') {
                header("Location: user_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            // Log failed login
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            $login_stmt = $conn->prepare("INSERT INTO user_logins (user_id, ip_address, user_agent, status) VALUES (?, ?, ?, 'failed')");
            $login_stmt->bind_param("iss", $user['user_id'], $ip_address, $user_agent);
            $login_stmt->execute();

            header("Location: login.php?error=" . urlencode("Incorrect password."));
            exit();
        }
    } else {
        header("Location: login.php?error=" . urlencode("User not found or role mismatch."));
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
