<?php
session_start();
require_once 'db.php';
require 'menubar3.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(403);
    echo "<h1>Access Denied</h1>";
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SERVER['HTTP_REFERER'])) {
    $_SESSION['previous_page'] = $_SERVER['HTTP_REFERER'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'], $_POST['new_status'])) {
    $comment_id = intval($_POST['comment_id']);
    $new_status = $_POST['new_status'] === 'approved' ? 'approved' : 'pending';

    $query = "SELECT a.author_id 
              FROM comments c 
              JOIN articles a ON c.article_id = a.article_id 
              WHERE c.comment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $stmt->bind_result($author_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_role === 'editor' || ($user_role === 'journalist' && $author_id == $user_id)) {
        $update = $conn->prepare("UPDATE comments SET status = ? WHERE comment_id = ?");
        $update->bind_param("si", $new_status, $comment_id);
        $update->execute();
        $update->close();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($user_role === 'editor') {
    $sql = "SELECT c.comment_id, c.name, c.content, c.status, c.created_at, a.title 
            FROM comments c 
            JOIN articles a ON c.article_id = a.article_id 
            ORDER BY c.created_at DESC";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT c.comment_id, c.name, c.content, c.status, c.created_at, a.title 
            FROM comments c 
            JOIN articles a ON c.article_id = a.article_id 
            WHERE a.author_id = ? 
            ORDER BY c.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Comments | Uttara-Times</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
            padding: 30px;
        }

        .comment-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            background-color: #ffffff;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
        }

        .comment-meta {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .status-approved {
            color: green;
        }

        .status-pending {
            color: orange;
        }

        h3 {
            margin-bottom: 25px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h3>Manage Comments</h3>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="comment-box">
            <div class="comment-meta">
                <strong><?= htmlspecialchars($row['name']) ?></strong> |
                On article: <em><?= htmlspecialchars($row['title']) ?></em><br>
                Posted: <?= date('F j, Y, g:i a', strtotime($row['created_at'])) ?> |
                Status: <span class="status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span>
            </div>
            <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>

            <form method="POST" class="d-inline">
                <input type="hidden" name="comment_id" value="<?= $row['comment_id'] ?>">
                <input type="hidden" name="new_status" value="<?= $row['status'] === 'approved' ? 'pending' : 'approved' ?>">
                <button type="submit" class="btn btn-sm btn-outline-primary">
                    Set as <?= $row['status'] === 'approved' ? 'Pending' : 'Approved' ?>
                </button>
            </form>
        </div>
    <?php endwhile; ?>
</body>

</html>