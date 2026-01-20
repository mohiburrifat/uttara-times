<?php

/********************************************************************
 *  Editor Dashboard – Uttara-Times
 *  Drop-in replacement (July 2025 version without “Rejected / Today / Week”).
 *  Adjust the two variables below if your schema differs.
 ********************************************************************/
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header("Location: login.php");
    exit();
}

require 'db.php';
require 'menubar3.php';

/* ─── CHANGE THESE IF YOUR SCHEMA DIFFERS ───────────────────────── */
$dateColumn  = 'created_at';  // timestamp column used for “This Month”
$draftStatus = 'draft';       // status value for draft articles
/* ───────────────────────────────────────────────────────────────── */

function getCount(mysqli $conn, string $sql): int
{
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return (int) ($stmt->get_result()->fetch_row()[0] ?? 0);
}

/* ─── numeric stats still needed ───────────────────────────────── */
$counts = [
    'total'       => getCount($conn, "SELECT COUNT(*) FROM articles"),
    'approved'    => getCount($conn, "SELECT COUNT(*) FROM articles WHERE status = 'approved'"),
    'pending'     => getCount($conn, "SELECT COUNT(*) FROM articles WHERE status = 'pending'"),
    'draft'       => getCount($conn, "SELECT COUNT(*) FROM articles WHERE status = '$draftStatus'"),
    'month'       => getCount($conn, "SELECT COUNT(*) FROM articles WHERE status = 'approved'
                                      AND DATE_FORMAT($dateColumn,'%Y-%m') = '" . date('Y-m') . "'"),
    'journalists' => getCount($conn, "SELECT COUNT(*) FROM users WHERE role = 'journalist'"),
    'tags'        => getCount($conn, "SELECT COUNT(*) FROM tags")
];

/*  label,           link,                                   icon,          value,            colour class  */
$stats = [
    ["Total Articles",        "articles.php",                       "fa-newspaper",     $counts['total'],       "primary"],
    ["Approved Articles",     "articles.php?status=approved",       "fa-check-circle",  $counts['approved'],    "success"],
    ["Pending Articles",      "pending_articles.php",               "fa-clock",         $counts['pending'],     "warning"],
    ["Draft Articles",        "articles.php?status=$draftStatus",   "fa-pen-to-square", $counts['draft'],       "secondary"],
    ["Published This Month",  "articles.php?filter=this-month",     "fa-calendar-days", $counts['month'],       "info"],
    ["Journalists",           "journalists.php",                    "fa-users",         $counts['journalists'], "purple"],
    ["Tags",                  "tag.php",                            "fa-tags",          $counts['tags'],        "secondary"]
];
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Editor Dashboard | Uttara-Times</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --purple: #6f42c1;
            --shadow-sm: 0 1px 4px rgba(0, 0, 0, .05);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, .1);
        }

        body {
            background: #f5f7fa;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #343a40;
            padding: 1.5rem;
        }

        .stat-card {
            background: #fff;
            border: 0;
            border-radius: .85rem;
            box-shadow: var(--shadow-sm);
            transition: transform .2s ease, box-shadow .2s ease;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .icon-wrap {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            color: #fff;
            font-size: 1.4rem;
        }

        /* extend Bootstrap palette with our purple */
        .bg-purple {
            background: var(--purple) !important;
        }

        .text-purple {
            color: var(--purple) !important;
        }
    </style>
</head>

<body>
    <!-- centred greeting -->
    <h3 class="mb-4 text-center">Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'Editor') ?>!</h3>

    <!-- ====== cards ====== -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php foreach ($stats as [$label, $link, $icon, $count, $color]): ?>
            <div class="col">
                <a href="<?= $link ?>" class="text-decoration-none text-reset">
                    <div class="stat-card p-3 text-center h-100">
                        <div class="icon-wrap bg-<?= $color ?>">
                            <i class="fa-solid <?= $icon ?>"></i>
                        </div>
                        <h2 class="mb-0 fw-semibold text-<?= $color ?>"><?= $count ?></h2>
                        <small class="text-muted"><?= $label ?></small>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ====== charts ====== -->
    <div class="row mt-4 gy-4">
        <div class="col-lg-7">
            <div class="card p-4 shadow-sm">
                <h6 class="mb-3 fw-semibold">Article Overview</h6>
                <canvas id="overviewBar" height="120"></canvas>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card p-4 shadow-sm">
                <h6 class="mb-3 fw-semibold">Status Breakdown</h6>
                <canvas id="statusPie" height="120"></canvas>
            </div>
        </div>
    </div>

    <script>
        /* ===== Bar chart (Removed Rejected/Today/Week) ===== */
        new Chart(document.getElementById('overviewBar'), {
            type: 'bar',
            data: {
                labels: [
                    'Total', 'Approved', 'Pending', 'Draft',
                    'This Month', 'Journalists', 'Tags'
                ],
                datasets: [{
                    data: [
                        <?= implode(',', [
                            $counts['total'],
                            $counts['approved'],
                            $counts['pending'],
                            $counts['draft'],
                            $counts['month'],
                            $counts['journalists'],
                            $counts['tags']
                        ]) ?>
                    ],
                    backgroundColor: [
                        '#0d6efd', '#198754', '#ffc107', '#6c757d',
                        '#0dcaf0', '#6f42c1', '#6c757d'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        /* ===== Doughnut chart (Approved / Pending / Draft) ===== */
        new Chart(document.getElementById('statusPie'), {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Draft'],
                datasets: [{
                    data: [
                        <?= $counts['approved'] ?>,
                        <?= $counts['pending'] ?>,
                        <?= $counts['draft'] ?>
                    ],
                    backgroundColor: ['#198754', '#ffc107', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                cutout: '60%'
            }
        });
    </script>
</body>

</html>