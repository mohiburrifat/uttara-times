<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header("Location: login.php");
    exit();
}
require 'db.php';
require 'menubar3.php';

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

$totalArticles     = scalar($conn, "SELECT COUNT(*) FROM articles");
$pendingArticles   = scalar($conn, "SELECT COUNT(*) FROM articles WHERE status = 'pending'");
$totalJournalists  = scalar($conn, "SELECT COUNT(*) FROM users WHERE role = 'journalist'");
$totalTags         = scalar($conn, "SELECT COUNT(*) FROM tags");
$totalComments     = scalar($conn, "SELECT COUNT(*) FROM comments");
$totalPlans        = scalar($conn, "SELECT COUNT(*) FROM subscription_plans");
$activeAds         = scalar($conn, "SELECT COUNT(*) FROM advertisements WHERE status = 'active'");
$activePopups      = scalar($conn, "SELECT COUNT(*) FROM popups WHERE is_active = 1");
$articlesPerJourno = $totalJournalists ? round($totalArticles / $totalJournalists, 1) : 0;

$trendRows  = rows(
    $conn,
    "SELECT DATE_FORMAT(created_at,'%b %Y') AS m , COUNT(*) AS c
     FROM articles
     WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
     GROUP BY m
     ORDER BY MIN(created_at)"
);
$trendLabels = array_column($trendRows, 'm');
$trendData   = array_column($trendRows, 'c');

$statusRows  = rows($conn, "SELECT status,COUNT(*) AS c FROM articles GROUP BY status");
$statusLabels = array_column($statusRows, 'status');
$statusData   = array_column($statusRows, 'c');

$topRows     = rows(
    $conn,
    "SELECT u.username, COUNT(*) AS c
     FROM users u
     JOIN articles a ON a.author_id = u.user_id
     WHERE u.role = 'journalist' AND a.status='approved'
     GROUP BY u.username
     ORDER BY c DESC
     LIMIT 5"
);
$topLabels   = array_column($topRows, 'username');
$topData     = array_column($topRows, 'c');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Editor Dashboard | Uttara-Times</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/countup.js@2.6.2/dist/countUp.umd.js"></script>

    <style>
        :root {
            --bg: #f8f9fa;
            --card-bg: #fff;
            --dark: #343a40;
        }

        body {
            background: var(--bg);
            font-family: 'Segoe UI', Tahoma, sans-serif;
            color: var(--dark);
        }

        .card-dash {
            border: none;
            border-radius: 14px;
            padding: 22px;
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        .card-dash .icon {
            font-size: 2.2rem;
            opacity: .15;
            position: absolute;
            bottom: -10px;
            right: -10px;
        }

        .card-dash h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.9rem;
        }

        .card-dash span {
            font-size: .9rem;
            color: rgba(255, 255, 255, .85);
        }

        .canvas-card {
            background: var(--card-bg);
            border: none;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
        }

        .bg-1 {
            background: linear-gradient(135deg, #6fb1fc 0%, #4364f7 100%);
        }

        .bg-2 {
            background: linear-gradient(135deg, #ffc107 0%, #ff8b01 100%);
        }

        .bg-3 {
            background: linear-gradient(135deg, #28a745 0%, #00c853 100%);
        }

        .bg-4 {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .bg-5 {
            background: linear-gradient(135deg, #e83e8c 0%, #b8136b 100%);
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <h3 class="mb-4 text-center">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h3>

        <div class="row g-3 mb-4">
            <?php
            $cards = [
                ['Total&nbsp;Articles',        'articles.php',         'bg-1', 'fa-newspaper', $totalArticles],
                ['Pending&nbsp;Articles',      'pending_articles.php', 'bg-2', 'fa-clock',     $pendingArticles],
                ['Journalists',               'journalists.php',      'bg-3', 'fa-users',     $totalJournalists],
                ['Comments',                  'manage_comment.php',   'bg-4', 'fa-comments',  $totalComments],
                ['Tags',                      'tag.php',              'bg-5', 'fa-tags',     $totalTags],
                ['Plans',                     'manage_plans.php',     'bg-1', 'fa-crown',    $totalPlans], 
                ['Active Ads',                'manage_ads.php',       'bg-2', 'fa-ad',       $activeAds],
                ['Active Popups',             'manage_popups.php',    'bg-3', 'fa-window-maximize', $activePopups]
            ];
            $index = 0;
            foreach ($cards as [$label, $link, $bg, $icon, $val]): ?>
                <div class="col-6 col-lg-3">
                    <a href="<?= $link ?>" class="text-decoration-none">
                        <div class="card-dash <?= $bg ?> text-center">
                            <div class="icon"><i class="fas <?= $icon ?>"></i></div>
                            <h4 id="cnt<?= $index ?>">0</h4>
                            <span><?= $label ?></span>
                        </div>
                    </a>
                </div>
            <?php $index++;
            endforeach; ?>
        </div>


        <div class="col-lg-6">
            <div class="canvas-card">
                <h6 class="mb-3">Status Distribution</h6>
                <canvas id="statusChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="canvas-card mb-4">
        <h6 class="mb-3">Top 5 Journalists (Approved Articles)</h6>
        <canvas id="topChart" height="120"></canvas>
    </div>
    </div>

    <script>
        const vals = <?= json_encode(array_column($cards, 4)); ?>;
        vals.forEach((v, i) => {
            new countUp.CountUp('cnt' + i, v, {
                duration: 1.6,
                separator: ','
            }).start();
        });

        const trendLabels = <?= json_encode($trendLabels) ?>,
            trendData = <?= json_encode($trendData) ?>,
            statusLabels = <?= json_encode($statusLabels) ?>,
            statusData = <?= json_encode($statusData) ?>,
            topLabels = <?= json_encode($topLabels) ?>,
            topData = <?= json_encode($topData) ?>;

        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    data: trendData,
                    fill: false,
                    borderWidth: 2,
                    pointRadius: 3
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

        new Chart(document.getElementById('topChart'), {
            type: 'bar',
            data: {
                labels: topLabels,
                datasets: [{
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