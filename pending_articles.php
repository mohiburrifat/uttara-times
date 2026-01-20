<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header("Location: login.php");
    exit();
}

require 'db.php';
require 'menubar3.php';

if (isset($_GET['action'], $_GET['article_id'])) {
    $action = $_GET['action'];
    $article_id = (int) $_GET['article_id'];

    if ($action === 'change_status' && isset($_GET['status'])) {
        $new_status = $_GET['status'];
        $allowed_statuses = ['draft', 'pending', 'approved', 'declined'];

        if (in_array($new_status, $allowed_statuses)) {
            $stmt = $conn->prepare("UPDATE articles SET status = ? WHERE article_id = ?");
            $stmt->bind_param('si', $new_status, $article_id);
            $stmt->execute();
        }
    } elseif ($action === 'delete') {
        $conn->begin_transaction();
        try {
            $stmt1 = $conn->prepare("DELETE FROM article_tags WHERE article_id = ?");
            $stmt1->bind_param('i', $article_id);
            $stmt1->execute();

            $stmt2 = $conn->prepare("DELETE FROM articles WHERE article_id = ?");
            $stmt2->bind_param('i', $article_id);
            $stmt2->execute();

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
        }
    }

    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pending Articles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 30px;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-approved {
            background-color: #28a745;
            color: #fff;
        }

        .badge-declined {
            background-color: #dc3545;
            color: #fff;
        }

        @media (max-width: 767.98px) {
            body {
                padding: 8px;
            }

            h2 {
                font-size: 1.3rem;
            }

            .table-responsive {
                font-size: 0.95rem;
            }

            .table th,
            .table td {
                padding: 0.4rem 0.3rem;
            }

            .btn,
            .btn-group,
            .dropdown-menu {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 575.98px) {
            .table-responsive {
                font-size: 0.89rem;
            }

            .table th,
            .table td {
                padding: 0.25rem 0.15rem;
            }

            h2 {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="mb-4 text-center">Pending Articles</h2>
        <input type="text" id="articleSearch" class="form-control mb-3" placeholder="Search by title or author...">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th class="text-start">Title</th>
                        <th class="text-start">Author</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->prepare("
                        SELECT a.article_id, a.title, a.created_at, a.status, u.username 
                        FROM articles a 
                        LEFT JOIN users u ON a.author_id = u.user_id 
                        WHERE a.status NOT IN ('approved', 'declined')
                        ORDER BY a.created_at DESC
                    ");
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $serial = 1;
                    while ($row = $result->fetch_assoc()):
                        $badgeClass = match ($row['status']) {
                            'pending' => 'badge-pending',
                            'approved' => 'badge-approved',
                            'declined' => 'badge-declined',
                            default => 'bg-secondary'
                        };
                    ?>
                        <tr>
                            <td><?= $serial++ ?></td>
                            <td class="text-start"><?= htmlspecialchars($row['title']) ?></td>
                            <td class="text-start"><?= htmlspecialchars($row['username'] ?? 'Unknown') ?></td>
                            <td>
                                <span class="badge <?= $badgeClass ?> status-badge" data-id="<?= $row['article_id'] ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <a href="view_article.php?id=<?= $row['article_id'] ?>" class="btn btn-sm btn-info mb-1">View</a>
                                <div class="btn-group mb-1">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">Status</button>
                                    <ul class="dropdown-menu">
                                        <?php foreach (['draft', 'pending', 'approved', 'declined'] as $statusOption): ?>
                                            <?php if ($statusOption !== $row['status']): ?>
                                                <li><a class="dropdown-item change-status" href="#" data-id="<?= $row['article_id'] ?>" data-status="<?= $statusOption ?>">
                                                        <?= ucfirst($statusOption) ?>
                                                    </a></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <a href="#" class="btn btn-sm btn-danger delete-article" data-id="<?= $row['article_id'] ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $('#articleSearch').on('keyup', function() {
            const query = $(this).val().toLowerCase();
            $('table tbody tr').each(function() {
                const title = $(this).find('td:nth-child(2)').text().toLowerCase();
                const author = $(this).find('td:nth-child(3)').text().toLowerCase();
                $(this).toggle(title.includes(query) || author.includes(query));
            });
        });

        $('.change-status').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const status = $(this).data('status');

            $.get('articles.php', {
                action: 'change_status',
                article_id: id,
                status: status
            }, function() {
                const badge = $(`.status-badge[data-id="${id}"]`);
                badge.text(status.charAt(0).toUpperCase() + status.slice(1));
                badge.removeClass('badge-pending badge-approved badge-declined bg-secondary');

                switch (status) {
                    case 'pending':
                        badge.addClass('badge-pending');
                        break;
                    case 'approved':
                        badge.addClass('badge-approved');
                        break;
                    case 'declined':
                        badge.addClass('badge-declined');
                        break;
                    default:
                        badge.addClass('bg-secondary');
                }
            });
        });

        $('.delete-article').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this article?')) {
                $.get('articles.php', {
                    action: 'delete',
                    article_id: id
                }, function() {
                    location.reload();
                });
            }
        });
    </script>
</body>

</html>