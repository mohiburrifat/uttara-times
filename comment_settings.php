<?php
session_start();
require_once 'db.php';
require 'menubar3.php';

// Access control: Only editors
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    http_response_code(403);
    echo "<h1>Access denied</h1>";
    exit();
}

// Handle enable/disable action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_id'], $_POST['action'])) {
    $article_id = intval($_POST['article_id']);
    $enable = ($_POST['action'] === 'enable') ? 1 : 0;

    $check_stmt = $conn->prepare("SELECT 1 FROM comment_settings WHERE article_id = ?");
    $check_stmt->bind_param("i", $article_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE comment_settings SET comments_enabled = ? WHERE article_id = ?");
        $stmt->bind_param("ii", $enable, $article_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO comment_settings (article_id, comments_enabled) VALUES (?, ?)");
        $stmt->bind_param("ii", $article_id, $enable);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: comment_settings.php");
    exit();
}

// Fetch articles with comment settings and author
$query = "
    SELECT a.article_id, a.title, u.username, COALESCE(cs.comments_enabled, 1) AS comments_enabled
    FROM articles a
    JOIN users u ON a.author_id = u.user_id
    LEFT JOIN comment_settings cs ON a.article_id = cs.article_id
    ORDER BY a.created_at DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Comment Settings | Uttara-Times</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
            padding: 30px;
        }

        h3 {
            margin-bottom: 25px;
            text-align: center;
        }

        table {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
        }
    </style>
</head>

<body>
    <h3>Comment Settings</h3>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Article Title</th>
                <th>Author</th>
                <th>Comments</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $row['comments_enabled'] ? 'Enabled' : 'Disabled' ?></td>
                    <td>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="article_id" value="<?= $row['article_id'] ?>">
                            <?php if ($row['comments_enabled']): ?>
                                <button type="submit" name="action" value="disable" class="btn btn-danger btn-sm">Disable</button>
                            <?php else: ?>
                                <button type="submit" name="action" value="enable" class="btn btn-success btn-sm">Enable</button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>