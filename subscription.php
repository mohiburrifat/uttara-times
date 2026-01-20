<?php
session_start();
require 'db.php';

// Fetch all plans
$plans = $conn->query("SELECT * FROM subscription_plans ORDER BY price ASC");

// Handle Subscription (Mock)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plan_id'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $plan_id = intval($_POST['plan_id']);

    // Fetch plan details to calculate end date
    $stmt = $conn->prepare("SELECT duration_days FROM subscription_plans WHERE plan_id = ?");
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $plan = $res->fetch_assoc();
        $days = $plan['duration_days'];
        $end_date = date('Y-m-d H:i:s', strtotime("+$days days"));

        // Deactivate old active subscriptions? Or just add new one. Let's add new one.
        // Optional: Update old active to expired
        $conn->query("UPDATE user_subscriptions SET status = 'expired' WHERE user_id = $user_id AND status = 'active'");

        $ins = $conn->prepare("INSERT INTO user_subscriptions (user_id, plan_id, end_date, status) VALUES (?, ?, ?, 'active')");
        $ins->bind_param("iis", $user_id, $plan_id, $end_date);

        if ($ins->execute()) {
            $success = "You have successfully subscribed!";
        } else {
            $error = "Something went wrong.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Subscriptions | Uttara-Times</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #003049;
            --accent: #d62828;
            --bg: #fdf0d5;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
            color: #333;
        }

        .hero-section {
            padding: 80px 0;
            text-align: center;
            background: var(--primary);
            color: white;
            margin-bottom: -50px;
            padding-bottom: 100px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .plan-card {
            border: none;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .plan-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .plan-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .price {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 10px;
        }

        .price span {
            font-size: 1rem;
            color: #777;
            font-weight: 400;
        }

        .duration {
            background: #f8f9fa;
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            font-weight: 500;
            margin-bottom: 20px;
            color: #555;
        }

        .description {
            color: #666;
            margin-bottom: 30px;
            flex-grow: 1;
            line-height: 1.6;
        }

        .btn-subscribe {
            background: var(--primary);
            color: white;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            border: none;
            width: 100%;
            transition: 0.3s;
        }

        .btn-subscribe:hover {
            background: #001d2d;
            transform: scale(1.05);
        }

        .popular-badge {
            position: absolute;
            top: 20px;
            right: -35px;
            background: var(--accent);
            color: white;
            padding: 5px 40px;
            transform: rotate(45deg);
            font-size: 0.8rem;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>

    <div class="hero-section">
        <div class="container">
            <div class="mb-3">
                <a href="index.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>
            <h1 class="display-4 fw-bold mb-3">Upgrade Your Experience</h1>
            <p class="lead opacity-75">Unlock exclusive content and features with our premium subscription plans.</p>
        </div>
    </div>

    <div class="container pb-5" style="margin-top: -50px;">
        <?php if (isset($success)): ?>
            <div class="alert alert-success shadow-sm border-0 mb-5 text-center"><?= $success ?></div>
        <?php endif; ?>

        <div class="row justify-content-center g-4">
            <?php if ($plans->num_rows > 0): ?>
                <?php $i = 0;
                while ($plan = $plans->fetch_assoc()): $i++; ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card plan-card">
                            <?php if ($i == 2): ?>
                                <div class="popular-badge">POPULAR</div>
                            <?php endif; ?>

                            <div class="plan-name"><?= htmlspecialchars($plan['name']) ?></div>
                            <div class="price">$<?= number_format($plan['price'], 0) ?><span>/period</span></div>
                            <div class="duration"><?= $plan['duration_days'] ?> Days Access</div>
                            <div class="description"><?= nl2br(htmlspecialchars($plan['description'])) ?></div>

                            <?php if (isset($_SESSION['user_id'])): ?>
                                <form method="post" class="mt-auto">
                                    <input type="hidden" name="plan_id" value="<?= $plan['plan_id'] ?>">
                                    <button class="btn btn-subscribe">Choose Plan</button>
                                </form>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-subscribe mt-auto">Login to Subscribe</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-4">No subscription plans available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>