<?php
// subscriptionplan.php
session_start();
require_once 'db.php';
require_once 'menubar.php';

// Fetch subscription plans from the database
$query = "SELECT * FROM subscription_plans WHERE status = 'active' ORDER BY price ASC";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Plans</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .plans-container {
            padding: 40px 0;
        }

        .plan-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .plan-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .plan-card.featured {
            border: 3px solid #667eea;
            transform: scale(1.05);
        }

        .plan-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .plan-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
        }

        .plan-price {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 10px 0;
        }

        .plan-duration {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .plan-body {
            padding: 30px 20px;
        }

        .plan-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .plan-features {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .plan-features li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            color: #555;
        }

        .plan-features li:last-child {
            border-bottom: none;
        }

        .plan-features i {
            color: #667eea;
            margin-right: 10px;
            width: 20px;
        }

        .plan-footer {
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
        }

        .btn-subscribe {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            font-weight: 600;
            border-radius: 50px;
            width: 100%;
        }

        .btn-subscribe:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            text-decoration: none;
        }

        .title-section {
            text-align: center;
            color: white;
            margin-bottom: 50px;
        }

        .title-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .badge-featured {
            position: absolute;
            top: -35px;
            right: -35px;
            width: 100px;
            height: 100px;
            background: #ffc107;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #333;
            transform: rotate(45deg);
            font-size: 0.8rem;
        }
    </style>
</head>

<body>
    <div class="container plans-container">
        <div class="title-section">
            <h2>Choose Your Perfect Plan</h2>
            <p style="font-size: 1.1rem;">Start enjoying premium content today</p>
        </div>

        <div class="row g-4">
            <?php
            $index = 0;
            while ($row = $result->fetch_assoc()):
                $is_featured = $index === 1;
            ?>
                <div class="col-md-4 col-lg-4">
                    <div class="plan-card <?php echo $is_featured ? 'featured' : ''; ?>">
                        <?php if ($is_featured): ?>
                            <div class="badge-featured">BEST<br>OFFER</div>
                        <?php endif; ?>

                        <div class="plan-header">
                            <h3 class="plan-name"><?php echo htmlspecialchars($row['name']); ?></h3>
                            <div class="plan-price">Rs. <?php echo number_format($row['price'], 0); ?></div>
                            <div class="plan-duration"><?php echo $row['duration_days']; ?> Days</div>
                        </div>

                        <div class="plan-body">
                            <p class="plan-description"><?php echo htmlspecialchars($row['description']); ?></p>

                            <ul class="plan-features">
                                <?php if ($row['features']): ?>
                                    <?php $features = explode(',', $row['features']); ?>
                                    <?php foreach ($features as $feature): ?>
                                        <li>
                                            <i class="fas fa-check-circle"></i>
                                            <?php echo trim($feature); ?>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li><i class="fas fa-check-circle"></i> Premium Access</li>
                                    <li><i class="fas fa-check-circle"></i> Ad-Free Reading</li>
                                    <li><i class="fas fa-check-circle"></i> Exclusive Content</li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <div class="plan-footer">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <form method="POST" action="subscription.php">
                                    <input type="hidden" name="plan_id" value="<?php echo $row['plan_id']; ?>">
                                    <button type="submit" class="btn btn-subscribe">Subscribe Now</button>
                                </form>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-subscribe">Subscribe Now</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php
                $index++;
            endwhile;
            ?>
        </div>

        <?php if (!$result->num_rows): ?>
            <div class="alert alert-info text-center" style="color: white; border: 2px solid white;">
                <h5>No subscription plans available yet.</h5>
                <p>Please check back later or contact our support team.</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>