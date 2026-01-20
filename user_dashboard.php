<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}
require 'db.php';
require 'menubar.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch user's comments
$stmt = $conn->prepare('SELECT c.comment, a.title, c.created_at FROM comments c JOIN articles a ON c.article_id = a.article_id WHERE c.user_id = ? ORDER BY c.created_at DESC');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$comments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Panel | Uttara-Times</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f8f9fa; }
        .card { border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-radius: 12px; transition: transform 0.2s; }
        .card:hover { transform: translateY(-2px); }
        .welcome-card { background: linear-gradient(135deg, #003049, #02476b); color: white; }
        .list-group-item { border: none; border-bottom: 1px solid #f1f1f1; padding: 15px 20px; }
        .list-group-item:last-child { border-bottom: none; }
        .sub-active { background: #e6fffa; border-left: 4px solid #38b2ac; padding: 20px; border-radius: 8px; }
        .sub-inactive { background: #fff5f5; border-left: 4px solid #fc8181; padding: 20px; border-radius: 8px; }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card p-4 welcome-card mb-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-4x opacity-50"></i>
                    </div>
                    <h3 class="fw-bold"><?= htmlspecialchars($username) ?></h3>
                    <p class="opacity-75 mb-0">Member since <?= date('Y') ?></p>
                </div>

                <div class="card p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-crown text-warning me-2"></i> Subscription</h5>
                    <?php
                    // Check active subscription - Reusing previous logic
                    $subStmt = $conn->prepare("SELECT p.name, us.end_date, us.status FROM user_subscriptions us JOIN subscription_plans p ON us.plan_id = p.plan_id WHERE us.user_id = ? AND us.status = 'active' ORDER BY us.end_date DESC LIMIT 1");
                    $subStmt->bind_param("i", $user_id);
                    $subStmt->execute();
                    $subRes = $subStmt->get_result();
                    if ($subRes->num_rows > 0) {
                        $sub = $subRes->fetch_assoc();
                        echo "<div class='sub-active'>
                                <h5 class='mb-1 text-success fw-bold'>" . htmlspecialchars($sub['name']) . "</h5>
                                <small class='text-muted'>Valid until " . date('M d, Y', strtotime($sub['end_date'])) . "</small>
                              </div>";
                    } else {
                        echo "<div class='sub-inactive'>
                                <h6 class='mb-1 text-danger'>No Active Plan</h6>
                                <small class='text-muted'>Unlock premium features today!</small>
                              </div>";
                    }
                    ?>
                    <a href="subscription.php" class="btn btn-dark w-100 mt-3 rounded-pill">Manage Subscription</a>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-4">
                    <h5 class="fw-bold mb-4"><i class="fas fa-comments text-primary me-2"></i> Recent Comments</h5>
                    <?php if ($comments): ?>
                        <ul class="list-group">
                            <?php foreach ($comments as $c): ?>
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 text-primary fw-bold"><?= htmlspecialchars($c['title']) ?></h6>
                                        <small class="text-muted"><i class="far fa-clock me-1"></i> <?= date('M d, Y', strtotime($c['created_at'])) ?></small>
                                    </div>
                                    <p class="mb-0 text-secondary">"<?= htmlspecialchars($c['comment']) ?>"</p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="far fa-comment-dots fa-3x mb-3 opacity-25"></i>
                            <p>You haven't posted any comments yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>