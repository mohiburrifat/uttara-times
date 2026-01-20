<?php
$servername = "localhost";
$username = "root"; // Adjust if necessary
$password = ""; // Your password (default for XAMPP is an empty string)
$dbname = "austro_asian_times"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper Functions for Subscription System

/**
 * Add a new subscription plan
 * @param string $name Plan name
 * @param float $price Plan price
 * @param int $duration_days Duration in days
 * @param string $description Plan description
 * @param string $features Comma-separated features
 * @return bool Success status
 */
function addSubscriptionPlan($name, $price, $duration_days, $description, $features = "")
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO subscription_plans (name, price, duration_days, description, features, status) VALUES (?, ?, ?, ?, ?, 'active')");
    $stmt->bind_param("sdiss", $name, $price, $duration_days, $description, $features);
    return $stmt->execute();
}

/**
 * Add a new user subscription
 * @param int $user_id User ID
 * @param int $plan_id Plan ID
 * @return bool Success status
 */
function addUserSubscription($user_id, $plan_id)
{
    global $conn;

    // Get plan duration
    $stmt = $conn->prepare("SELECT duration_days FROM subscription_plans WHERE plan_id = ?");
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        return false;
    }

    $plan = $result->fetch_assoc();
    $end_date = date('Y-m-d H:i:s', strtotime('+' . $plan['duration_days'] . ' days'));

    // Deactivate old subscriptions
    $conn->query("UPDATE user_subscriptions SET status = 'expired' WHERE user_id = $user_id AND status = 'active'");

    // Add new subscription
    $insert_stmt = $conn->prepare("INSERT INTO user_subscriptions (user_id, plan_id, end_date, status, payment_status) VALUES (?, ?, ?, 'active', 'completed')");
    $insert_stmt->bind_param("iis", $user_id, $plan_id, $end_date);

    if ($insert_stmt->execute()) {
        // Update user subscription status
        $conn->query("UPDATE users SET subscription_status = 'subscribed' WHERE user_id = $user_id");
        return true;
    }
    return false;
}

/**
 * Get active subscription for user
 * @param int $user_id User ID
 * @return array|null Subscription details
 */
function getUserActiveSubscription($user_id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT us.*, sp.name, sp.price, sp.duration_days, sp.description, sp.features
        FROM user_subscriptions us
        LEFT JOIN subscription_plans sp ON us.plan_id = sp.plan_id
        WHERE us.user_id = ? AND us.status = 'active' AND us.end_date > NOW()
        LIMIT 1
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/**
 * Check if user has active subscription
 * @param int $user_id User ID
 * @return bool True if user has active subscription
 */
function hasActiveSubscription($user_id)
{
    $subscription = getUserActiveSubscription($user_id);
    return $subscription !== null;
}

/**
 * Add advertisement
 * @param string $title Ad title
 * @param string $content Ad content
 * @param string $position Ad position
 * @param int $editor_id Editor ID
 * @return bool Success status
 */
function addAdvertisement($title, $content, $position = 'home_top', $editor_id = null)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO advertisements (title, content, position, editor_id, status) VALUES (?, ?, ?, ?, 'active')");
    $stmt->bind_param("sssi", $title, $content, $position, $editor_id);
    return $stmt->execute();
}

/**
 * Add pop-up advertisement
 * @param string $title Ad title
 * @param string $content Ad content
 * @param int $editor_id Editor ID
 * @return bool Success status
 */
function addPopupAdvertisement($title, $content, $editor_id = null)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO popup_advertisements (title, content, editor_id, status) VALUES (?, ?, ?, 'active')");
    $stmt->bind_param("ssi", $title, $content, $editor_id);
    return $stmt->execute();
}

/**
 * Get active advertisements by position
 * @param string $position Ad position
 * @return mysqli_result Active advertisements
 */
function getActiveAdvertisements($position = 'home_top')
{
    global $conn;
    $stmt = $conn->prepare("
        SELECT * FROM advertisements 
        WHERE status = 'active' AND position = ? 
        AND (end_date IS NULL OR end_date > NOW())
        ORDER BY created_at DESC
    ");
    $stmt->bind_param("s", $position);
    $stmt->execute();
    return $stmt->get_result();
}

/**
 * Get active pop-up advertisements
 * @return mysqli_result Active pop-up ads
 */
function getActivePopupAdvertisements()
{
    global $conn;
    $result = $conn->query("
        SELECT * FROM popup_advertisements 
        WHERE status = 'active' 
        AND (end_date IS NULL OR end_date > NOW())
        ORDER BY created_at DESC
    ");
    return $result;
}

/**
 * Log user login activity
 * @param int $user_id User ID
 * @param string $status Login status (success/failed)
 * @return bool Success status
 */
function logUserLogin($user_id, $status = 'success')
{
    global $conn;
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $stmt = $conn->prepare("INSERT INTO user_logins (user_id, ip_address, user_agent, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $ip_address, $user_agent, $status);
    return $stmt->execute();
}

/**
 * Update subscription status (check for expired subscriptions)
 * @param int $user_id User ID
 * @return void
 */
function updateSubscriptionStatus($user_id)
{
    global $conn;

    // Check for expired subscriptions
    $conn->query("UPDATE user_subscriptions SET status = 'expired' WHERE user_id = $user_id AND end_date <= NOW() AND status = 'active'");

    // Update user subscription status
    $active = $conn->query("SELECT COUNT(*) as count FROM user_subscriptions WHERE user_id = $user_id AND status = 'active'");
    $result = $active->fetch_assoc();

    if ($result['count'] > 0) {
        $conn->query("UPDATE users SET subscription_status = 'subscribed' WHERE user_id = $user_id");
    } else {
        $conn->query("UPDATE users SET subscription_status = 'free' WHERE user_id = $user_id");
    }
}
