<?php

/********************************************************************
 *  editor_dashboard.php — Uttara-Times
 *******************************************************************/
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header("Location: login.php");
    exit();
}
require 'db.php';

/* ---------- tiny helpers ---------- */
function scalar($conn, $sql, $types = '', $params = [])
{
    $stmt = $conn->prepare($sql);
    if ($types) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_row()[0] ?? 0;
}
function rows($conn, $sql, $types = '', $params = [])
{
    $stmt = $conn->prepare($sql);
    if ($types) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/* ---------- headline stats ---------- */
$totalArticles     = scalar($conn, "SELECT COUNT(*) FROM articles");
$pendingArticles   = scalar($conn, "SELECT COUNT(*) FROM articles WHERE status = 'pending'");
$rejectedArticles  = scalar($conn, "SELECT COUNT(*) FROM articles WHERE status = 'rejected'");
$totalJournalists  = scalar($conn, "SELECT COUNT(*) FROM users WHERE role = 'journalist'");
$totalTags         = scalar($conn, "SELECT COUNT(*) FROM tags");
$totalComments     = scalar($conn, "SELECT COUNT(*) FROM comments");

/* ---------- charts data ---------- */
/* 1) Monthly article trend (last 6 months) */
$trendRows = rows(
    $conn,
    "SELECT DATE_FORMAT(created_at, '%b %Y') AS m, COUNT(*) AS c
     FROM articles
     WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
     GROUP BY m
     ORDER BY MIN(created_at) ASC"
);
$trendLabels = array_column($trendRows, 'm');
$trendData   = array_column($trendRows, 'c');

/* 2) Status distribution */
$statusRows = rows(
    $conn,
    "SELECT status, COUNT(*) AS c FROM articles GROUP BY status"
);
$statusLabels = array_column($statusRows, 'status');
$statusData   = array_column($statusRows, 'c');

/* 3) Top 5 journalists */
$topRows = rows(
    $conn,
    "SELECT u.username, COUNT(*) AS c
     FROM users u
     JOIN articles a ON a.author_id = u.user_id
     WHERE u.role = 'journalist' AND a.status = 'approved'
     GROUP BY u.username
     ORDER BY c DESC
     LIMIT 5"
);
$topLabels = array_column($topRows, 'username');
$topData   = array_column($topRows, 'c');

/* ---------- latest pending articles ---------- */
$latestPending = rows(
    $conn,
    "SELECT article_id, title, DATE_FORMAT(created_at,'%d %b %Y') AS d
     FROM articles
     WHERE status = 'pending'
     ORDER BY created_at DESC
     LIMIT 5"
);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Editor Dashboard | Uttara-Times</title>

    <!-- CSS & JS libs -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- design tokens -->
    <style>
        :root {
            --primary: #6fb1fc;
            --background: #f8f9fa;
            --card-bg: #fff;
            --text-dark: #343a40;
            --text-muted: #6c757d;
            --sidebar-bg: #e9f1fb;
            --brand: #5089c6
        }

        body {
            background: var(--background);
            font-family: 'Segoe UI', Tahoma, sans-serif;
            color: var(--text-dark)
        }

        .navbar {
            background: var(--sidebar-bg);
            box-shadow: 0 2px 4px rgba(0, 0, 0, .05);
            padding: .6rem 1rem;
            position: sticky;
            top: 0;
            z-index: 1100
        }

        .navbar-brand {
            color: var(--brand);
            font-weight: bold
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 250px;
            background: var(--sidebar-bg);
            transform: translateX(-100%);
            transition: .3s;
            z-index: 1000
        }

        .sidebar.show {
            transform: none
        }

        @media(min-width:768px) {
            .sidebar {
                transform: none
            }

            .main {
                margin-left: 250px
            }
        }

        .card {
            background: var(--card-bg);
            border: none;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
            margin-bottom: 20px
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--brand)
        }

        .stat-label {
            color: var(--text-muted)
        }

        .table-actions a {
            margin-right: .5rem
        }
    </style>
</head>

<body>
    <!-- top bar -->
    <nav class="navbar"><button class="btn p-0 border-0" id="toggleSidebar"><i class="fas fa-bars"></i></button><span class="navbar-brand">Uttara-Times</span></nav>

    <!-- drawer -->
    <div class="sidebar" id="sidebar">
        <div class="p-3 bg-primary text-white text-center">Editor Dashboard</div>
        <div class="p-3 bg-light d-flex align-items-center">
            <img class="rounded-circle me-2" src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['username']) ?>&background=random" width="40" height="40" alt>
            <div><strong><?= htmlspecialchars($_SESSION['username']) ?></strong><br><small class="text-muted">Editor</small></div>
        </div>
        <ul class="list-unstyled">
            <li><a class="d-block px-3 py-2" href="editor_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
            <li><a class="d-block px-3 py-2" href="articles.php"><i class="fas fa-newspaper me-2"></i>Articles</a></li>
            <li><a class="d-block px-3 py-2" href="pending_articles.php"><i class="fas fa-clock me-2"></i>Pending Approval</a></li>
            <li><a class="d-block px-3 py-2" href="journalists.php"><i class="fas fa-users me-2"></i>Journalists</a></li>
            <li><a class="d-block px-3 py-2" href="add_journalist.php"><i class="fas fa-user-plus me-2"></i>Add Journalist</a></li>
            <li><a class="d-block px-3 py-2" href="manage_comment.php"><i class="fas fa-comments me-2"></i>Manage Comments</a></li>
            <li><a class="d-block px-3 py-2" href="comment_settings.php"><i class="fas fa-comment me-2"></i>Comment Settings</a></li>
            <li><a class="d-block px-3 py-2" href="tag.php"><i class="fas fa-tags me-2"></i>Tags</a></li>
            <li><a class="d-block px-3 py-2" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
    </div>
    <div class="overlay position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-none" id="overlay"></div>

    <!-- main -->
    <div class="main p-4">
        <h3 class="mb-4">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h3>

        <!-- stat cards -->
        <div class="row g-3 mb-4">
            <?php
            $cards = [
                ['Total Articles',        'articles.php',         'fa-newspaper', $totalArticles],
                ['Pending Articles',      'pending_articles.php', 'fa-clock',     $pendingArticles],
                ['Rejected Articles',     'rejected.php',         'fa-times',     $rejectedArticles],
                ['Journalists',           'journalists.php',      'fa-users',     $totalJournalists],
                ['Tags',                  'tag.php',              'fa-tags',      $totalTags],
                ['Comments',              'manage_comment.php',   'fa-comments',  $totalComments]
            ];
            foreach ($cards as [$label, $link, $icon, $num]): ?>
                <div class="col-6 col-lg-4 col-xl-2">
                    <a href="<?= $link ?>" class="text-decoration-none">
                        <div class="card text-center h-100">
                            <i class="fas <?= $icon ?> fa-2x mb-2"></i>
                            <div class="stat-number"><?= $num ?></div>
                            <div class="stat-label"><?= $label ?></div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- charts row -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card">
                    <h6 class="mb-3">Article Submissions (Last 6 Months)</h6>
                    <canvas id="trendChart" height="150"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <h6 class="mb-3">Status Distribution</h6>
                    <canvas id="statusChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- top journalists -->
        <div class="card mb-4">
            <h6 class="mb-3">Top 5 Journalists (Approved Articles)</h6>
            <canvas id="topChart" height="120"></canvas>
        </div>

        <!-- latest pending -->
        <div class="card">
            <h6 class="mb-3">Latest Pending Articles</h6>
            <?php if ($latestPending): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($latestPending as $row): ?>
                                <tr>
                                    <td><?= $row['title'] ?></td>
                                    <td><?= $row['d'] ?></td>
                                    <td class="text-end table-actions">
                                        <a href="approve_article.php?id=<?= $row['article_id'] ?>" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a>
                                        <a href="view_article.php?id=<?= $row['article_id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No pending articles.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- scripts -->
    <script>
        /* drawer */
        const sidebar = document.getElementById('sidebar'),
            overlay = document.getElementById('overlay'),
            btn = document.getElementById('toggleSidebar');
        btn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('d-none')
        });
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.add('d-none')
        });

        /* charts data injected from PHP */
        const trendLabels = <?= json_encode($trendLabels) ?>;
        const trendData = <?= json_encode($trendData) ?>;
        const statusLabels = <?= json_encode($statusLabels) ?>;
        const statusData = <?= json_encode($statusData) ?>;
        const topLabels = <?= json_encode($topLabels) ?>;
        const topData = <?= json_encode($topData) ?>;

        /* line chart – trend */
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Articles',
                    data: trendData,
                    fill: false,
                    borderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        /* doughnut – status */
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        /* horizontal bar – top journalists */
        new Chart(document.getElementById('topChart'), {
            type: 'bar',
            data: {
                labels: topLabels,
                datasets: [{
                    label: 'Articles',
                    data: topData,
                    barThickness: 18
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>