<?php
// setup_subscription_system.php
// Run this file once to set up all subscription and advertisement tables

require 'db.php';

$tables_created = [];
$errors = [];

try {
    // Create subscription_plans table
    $sql1 = "
    CREATE TABLE IF NOT EXISTS `subscription_plans` (
      `plan_id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(100) NOT NULL,
      `price` decimal(10,2) NOT NULL,
      `duration_days` int(11) NOT NULL,
      `description` text,
      `features` text,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      `status` enum('active','inactive') DEFAULT 'active',
      PRIMARY KEY (`plan_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";

    if ($conn->query($sql1)) {
        $tables_created[] = "subscription_plans";
    }

    // Create user_subscriptions table
    $sql2 = "
    CREATE TABLE IF NOT EXISTS `user_subscriptions` (
      `subscription_id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `plan_id` int(11) NOT NULL,
      `start_date` timestamp DEFAULT CURRENT_TIMESTAMP,
      `end_date` datetime,
      `status` enum('active','expired','cancelled') DEFAULT 'active',
      `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`subscription_id`),
      FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
      FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`plan_id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";

    if ($conn->query($sql2)) {
        $tables_created[] = "user_subscriptions";
    }

    // Create advertisements table
    $sql3 = "
    CREATE TABLE IF NOT EXISTS `advertisements` (
      `ad_id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(255) NOT NULL,
      `content` text NOT NULL,
      `image_url` varchar(255),
      `link_url` varchar(255),
      `position` varchar(50) DEFAULT 'home_top',
      `editor_id` int(11),
      `start_date` timestamp DEFAULT CURRENT_TIMESTAMP,
      `end_date` datetime,
      `status` enum('active','inactive') DEFAULT 'active',
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`ad_id`),
      FOREIGN KEY (`editor_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";

    if ($conn->query($sql3)) {
        $tables_created[] = "advertisements";
    }

    // Create popup_advertisements table
    $sql4 = "
    CREATE TABLE IF NOT EXISTS `popup_advertisements` (
      `popup_id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(255) NOT NULL,
      `content` text NOT NULL,
      `image_url` varchar(255),
      `link_url` varchar(255),
      `editor_id` int(11),
      `display_frequency` varchar(50) DEFAULT 'once_per_session',
      `start_date` timestamp DEFAULT CURRENT_TIMESTAMP,
      `end_date` datetime,
      `status` enum('active','inactive') DEFAULT 'active',
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`popup_id`),
      FOREIGN KEY (`editor_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";

    if ($conn->query($sql4)) {
        $tables_created[] = "popup_advertisements";
    }

    // Create user_logins table
    $sql5 = "
    CREATE TABLE IF NOT EXISTS `user_logins` (
      `login_id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `login_time` timestamp DEFAULT CURRENT_TIMESTAMP,
      `ip_address` varchar(45),
      `user_agent` text,
      `status` enum('success','failed') DEFAULT 'success',
      PRIMARY KEY (`login_id`),
      FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ";

    if ($conn->query($sql5)) {
        $tables_created[] = "user_logins";
    }

    // Add subscription_status column to users table if it doesn't exist
    $checkCol = $conn->query("SHOW COLUMNS FROM users LIKE 'subscription_status'");
    if ($checkCol->num_rows == 0) {
        $sql6 = "ALTER TABLE `users` ADD COLUMN `subscription_status` enum('free','subscribed','expired') DEFAULT 'free'";
        if ($conn->query($sql6)) {
            $tables_created[] = "users.subscription_status (column added)";
        }
    }

    // Insert default subscription plans
    $checkPlans = $conn->query("SELECT COUNT(*) as count FROM subscription_plans");
    $planCount = $checkPlans->fetch_assoc()['count'];

    if ($planCount == 0) {
        $sql7 = "
        INSERT INTO `subscription_plans` (`name`, `price`, `duration_days`, `description`, `features`, `status`) VALUES
        ('Basic Plan', 99.00, 30, 'Basic subscription with access to all articles', 'Access to articles, Ad-free reading', 'active'),
        ('Premium Plan', 199.00, 30, 'Premium subscription with exclusive content', 'Access to exclusive articles, Ad-free reading, Early access to new articles', 'active'),
        ('Annual Plan', 999.00, 365, 'Annual subscription with best value', 'All Premium features, Yearly discount, Priority support', 'active');
        ";

        if ($conn->query($sql7)) {
            $tables_created[] = "Default subscription plans (inserted)";
        }
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - Subscription System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Subscription System Setup</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($tables_created)): ?>
                    <div class="alert alert-success">
                        <h5>✓ Setup Completed Successfully!</h5>
                        <ul>
                            <?php foreach ($tables_created as $table): ?>
                                <li><?php echo $table; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <h5>✗ Errors Occurred:</h5>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="mt-4">
                    <h5>Next Steps:</h5>
                    <ol>
                        <li>Go to <a href="manage_plans.php">Manage Plans</a> to add/edit subscription plans (Editor only)</li>
                        <li>Go to <a href="manage_ads.php">Manage Ads</a> to add advertisements (Editor only)</li>
                        <li>Go to <a href="popup_advertisement.php">Manage Pop-ups</a> to add pop-up advertisements (Editor only)</li>
                        <li>Users can subscribe at <a href="subscription.php">Subscription Page</a></li>
                        <li>Users can view their subscriptions at <a href="my_subscriptions.php">My Subscriptions</a></li>
                    </ol>
                </div>

                <div class="mt-4">
                    <h5>System Features:</h5>
                    <ul>
                        <li>✓ Subscription Plans Management</li>
                        <li>✓ User Subscriptions Tracking</li>
                        <li>✓ Advertisement Management</li>
                        <li>✓ Pop-up Advertisement Management</li>
                        <li>✓ User Login Activity Tracking</li>
                        <li>✓ Subscription Status per User</li>
                    </ul>
                </div>

                <a href="index.php" class="btn btn-primary mt-3">Return to Home</a>
            </div>
        </div>
    </div>
</body>

</html>