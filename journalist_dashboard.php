<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'journalist') {
    header('Location: login.php');
    exit();
}

$journalist_id = $_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username']);

// Count total articles by the journalist
$article_count = 0;
$stmt = $conn->prepare("SELECT COUNT(*) FROM articles WHERE author_id = ?");
$stmt->bind_param("i", $journalist_id);
$stmt->execute();
$stmt->bind_result($article_count);
$stmt->fetch();
$stmt->close();

// Count total approved comments on journalist's articles
$comment_count = 0;
$stmt = $conn->prepare("
    SELECT COUNT(*) FROM comments 
    WHERE article_id IN (
        SELECT article_id FROM articles WHERE author_id = ?
    ) AND status = 'approved'
");
$stmt->bind_param("i", $journalist_id);
$stmt->execute();
$stmt->bind_result($comment_count);
$stmt->fetch();
$stmt->close();

// Fetch latest 5 articles
$latest_articles = [];
$stmt = $conn->prepare("
    SELECT article_id, title, created_at, status 
    FROM articles 
    WHERE author_id = ? 
    ORDER BY created_at DESC 
    LIMIT 5
");
$stmt->bind_param("i", $journalist_id);
$stmt->execute();
$stmt->bind_result($id, $title, $created_at, $status);
while ($stmt->fetch()) {
    $latest_articles[] = [
        'id' => $id,
        'title' => $title,
        'created_at' => $created_at,
        'status' => $status
    ];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Journalist Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            overflow-x: hidden;
        }

        .top-navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1040;
        }

        .hamburger {
            font-size: 24px;
            background: none;
            border: none;
            color: #333;
        }

        .sidebar {
            background-color: #1e1e2f;
            color: white;
            height: 100vh;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            padding-top: 60px;
            transition: transform 0.3s ease;
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .sidebar a {
            color: #ddd;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar .active a {
            background-color: #343a40;
            color: #fff;
        }

        .main-content {
            margin-left: 240px;
            padding: 40px 20px;
            transition: margin-left 0.3s ease;
        }

        .content-expanded {
            margin-left: 0 !important;
        }

        @media (max-width: 767.98px) {
            .main-content {
                margin-left: 0;
            }
        }

        .card {
            margin-bottom: 20px;
        }

        .status-badge {
            font-size: 0.8rem;
        }
    </style>
</head>

<body>

    <!-- Top Navbar -->
    <div class="top-navbar">
        <button class="hamburger" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <h4 class="m-0">Journalist Dashboard</h4>
        <div class="dropdown d-none d-md-block">
            <a href="#" class="text-muted dropdown-toggle" id="journalistDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration:none;cursor:pointer;">
                <?php echo $username; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="journalistDropdown">
                <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>

    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">
        <h4 class="text-center text-light mt-3">Journalist Panel</h4>
        <ul class="list-unstyled mt-4">
            <li class="active"><a href="journalist_dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a></li>
            <li><a href="add_article.php"><i class="fas fa-plus me-2"></i> Add Article</a></li>
            <li><a href="view_articles.php"><i class="fas fa-newspaper me-2"></i> View All Articles</a></li>
            <li><a href="edit_profile.php"><i class="fas fa-user-edit me-2"></i> Edit Profile</a></li>
            <li><a href="j.manage_comment.php"><i class="fas fa-comment-dots me-2"></i> Manage Comment</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main id="mainContent" class="main-content">
        <h1>Welcome, <?php echo $username; ?> 👋</h1>
        <p class="lead">Your journalist overview panel.</p>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-newspaper fa-2x text-primary mb-2"></i>
                        <h5>Total Articles</h5>
                        <h2><?php echo $article_count; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-comments fa-2x text-success mb-2"></i>
                        <h5>Approved Comments</h5>
                        <h2><?php echo $comment_count; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest 5 Articles -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Latest Articles</h4>
                <?php if (empty($latest_articles)): ?>
                    <p>No articles posted yet.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach ($latest_articles as $article): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo htmlspecialchars($article['title']); ?></strong><br>
                                    <small class="text-muted"><?php echo date('M j, Y', strtotime($article['created_at'])); ?></small>
                                </div>
                                <span class="badge bg-<?php
                                                        echo $article['status'] === 'approved' ? 'success' : ($article['status'] === 'pending' ? 'warning' : 'secondary');
                                                        ?> status-badge text-uppercase">
                                    <?php echo $article['status']; ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Sidebar Toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('mainContent');
            sidebar.classList.toggle('sidebar-hidden');
            content.classList.toggle('content-expanded');
        }
    </script>

    <!-- Bootstrap JS for dropdown -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>