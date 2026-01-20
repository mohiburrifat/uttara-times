<?php
require 'db.php';

// 1. Modify users table role enum
try {
    $conn->query("ALTER TABLE users MODIFY COLUMN role ENUM('journalist','editor','user') NOT NULL");
    echo "Users table updated.\n";
} catch (Exception $e) {
    echo "Error updating users table (might already exist): " . $e->getMessage() . "\n";
}

// 2. Create subscription_plans table
$sql = "CREATE TABLE IF NOT EXISTS subscription_plans (
    plan_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    duration_days INT NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "subscription_plans table created.\n";
} else {
    echo "Error creating subscription_plans: " . $conn->error . "\n";
}

// 3. Create user_subscriptions table
$sql = "CREATE TABLE IF NOT EXISTS user_subscriptions (
    subscription_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    plan_id INT NOT NULL,
    start_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    end_date DATETIME NOT NULL,
    status ENUM('active','expired') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES subscription_plans(plan_id) ON DELETE CASCADE
)";
if ($conn->query($sql) === TRUE) {
    echo "user_subscriptions table created.\n";
} else {
    echo "Error creating user_subscriptions: " . $conn->error . "\n";
}

// 4. Create advertisements table
$sql = "CREATE TABLE IF NOT EXISTS advertisements (
    ad_id INT AUTO_INCREMENT PRIMARY KEY,
    image_url VARCHAR(255) NOT NULL,
    link_url VARCHAR(255),
    position VARCHAR(50) DEFAULT 'home_top',
    start_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    end_date DATETIME,
    status ENUM('active','inactive') DEFAULT 'active'
)";
if ($conn->query($sql) === TRUE) {
    echo "advertisements table created.\n";
} else {
    echo "Error creating advertisements: " . $conn->error . "\n";
}

// 5. Create popups table
$sql = "CREATE TABLE IF NOT EXISTS popups (
    popup_id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "popups table created.\n";
} else {
    echo "Error creating popups: " . $conn->error . "\n";
}

$conn->close();
?>
