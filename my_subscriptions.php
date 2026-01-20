<?php
session_start();
require 'db.php';
require 'menubar.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's current subscription
$query = "
    SELECT us.*, sp.name, sp.price, sp.description 
    FROM user_subscriptions us 
    LEFT JOIN subscription_plans sp ON us.plan_id = sp.plan_id 
    WHERE us.user_id = ? AND us.status = 'active'
    ORDER BY us.start_date DESC 
    LIMIT 1
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$subscription = $stmt->get_result()->fetch_assoc();

// Fetch subscription history
$historyQuery = "
    SELECT us.*, sp.name, sp.price 
    FROM user_subscriptions us 
    LEFT JOIN subscription_plans sp ON us.plan_id = sp.plan_id 
    WHERE us.user_id = ? 
    ORDER BY us.start_date DESC
";
$historyStmt = $conn->prepare($historyQuery);
$historyStmt->bind_param("i", $user_id);
$historyStmt->execute();
$history = $historyStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Subscriptions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 80px;
        }

        .subscription-card {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .active-badge {
            background-color: #28a745;
        }

        .expired-badge {
            background-color: #dc3545;
        }

        .pending-badge {
            background-color: #ffc107;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <h2 class="mb-4">My Subscriptions</h2>

        <!-- Current Subscription -->
        <div class="row">
            <div class="col-md-8">
                <?php if ($subscription): ?>
                    <div class="subscription-card">
                        <h4><?php echo $subscription['name']; ?></h4>
                        <p><strong>Price:</strong> Rs. <?php echo $subscription['price']; ?>/month</p>
                        <p><strong>Status:</strong> <span class="badge active-badge"><?php echo ucfirst($subscription['status']); ?></span></p>
                        <p><strong>Start Date:</strong> <?php echo date('F j, Y', strtotime($subscription['start_date'])); ?></p>
                        <p><strong>End Date:</strong> <?php echo date('F j, Y', strtotime($subscription['end_date'])); ?></p>

                        <?php
                        $today = new DateTime();
                        $endDate = new DateTime($subscription['end_date']);
                        $daysLeft = $today->diff($endDate)->days;
                        ?>
                        <p><strong>Days Remaining:</strong> <span class="badge bg-info"><?php echo $daysLeft; ?> days</span></p>

                        <p><?php echo $subscription['description']; ?></p>
                        <a href="subscription.php" class="btn btn-primary">Upgrade Plan</a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <h5>No Active Subscription</h5>
                        <p>You don't have an active subscription. <a href="subscription.php">Subscribe now</a> to access premium content and features.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Quick Stats</div>
                    <div class="card-body">
                        <p><strong>Account Type:</strong> <?php echo $subscription ? 'Premium' : 'Free'; ?></p>
                        <p><strong>Member Since:</strong> <?php
                                                            $userQuery = "SELECT created_at FROM users WHERE user_id = ?";
                                                            $userStmt = $conn->prepare($userQuery);
                                                            $userStmt->bind_param("i", $user_id);
                                                            $userStmt->execute();
                                                            $userData = $userStmt->get_result()->fetch_assoc();
                                                            echo date('F j, Y', strtotime($userData['created_at']));
                                                            ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription History -->
        <div class="mt-5">
            <h4>Subscription History</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Price</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $history->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td>Rs. <?php echo $row['price']; ?></td>
                                <td><?php echo date('F j, Y', strtotime($row['start_date'])); ?></td>
                                <td><?php echo date('F j, Y', strtotime($row['end_date'])); ?></td>
                                <td>
                                    <span class="badge <?php echo $row['status'] === 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo $row['payment_status'] === 'completed' ? 'bg-success' : 'bg-warning'; ?>">
                                        <?php echo ucfirst($row['payment_status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>