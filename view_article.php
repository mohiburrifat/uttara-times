<?php
session_start();
require_once 'db.php';

/*-------------------------------------------------
 |  1. Validate and sanitise article ID
 *------------------------------------------------*/
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(404);
    exit('<h1>Article not found</h1>');
}
$article_id = (int) $_GET['id'];

/*-------------------------------------------------
 |  2. Get logged-in user data (if any)
 *------------------------------------------------*/
$user_id   = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role']    ?? null;

/*-------------------------------------------------
 |  3. Check per-article “comments enabled” flag
 *------------------------------------------------*/
$comments_enabled = true;                       // default
$setting_stmt = $conn->prepare(
    'SELECT comments_enabled
       FROM comment_settings
      WHERE article_id = ?'
);
$setting_stmt->bind_param('i', $article_id);
$setting_stmt->execute();
$setting_stmt->bind_result($enabled_val);
if ($setting_stmt->fetch()) {
    $comments_enabled = (bool) $enabled_val;
}
$setting_stmt->close();

/*-------------------------------------------------
 |  4. Handle a new comment
 *------------------------------------------------*/
$comment_submitted = false;
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    $comments_enabled &&
    isset($_POST['name'], $_POST['content'])
) {
    $name    = trim($_POST['name']);
    $content = trim($_POST['content']);

    if ($name !== '' && $content !== '') {
        $ins = $conn->prepare(
            "INSERT INTO comments (article_id, name, content, status)
                  VALUES (?, ?, ?, 'pending')"
        );
        $ins->bind_param('iss', $article_id, $name, $content);
        $ins->execute();
        $ins->close();
        $comment_submitted = true;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] === 'user') {
    require 'db.php';
    $article_id = (int)($_GET['id'] ?? 0);
    $user_id = $_SESSION['user_id'];
    $comment = trim($_POST['comment'] ?? '');
    if ($comment) {
        $stmt = $conn->prepare('INSERT INTO comments (article_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->bind_param('iis', $article_id, $user_id, $comment);
        $stmt->execute();
    }
    header('Location: view_article.php?id=' . $article_id);
    exit();
}

/*-------------------------------------------------
 |  5. Fetch the article
 *------------------------------------------------*/
$article_stmt = $conn->prepare(
    'SELECT a.title, a.content, a.image_url,
            a.created_at, a.updated_at, a.status,
            a.author_id, u.username
       FROM articles a
  INNER JOIN users u ON a.author_id = u.user_id
      WHERE a.article_id = ?'
);
$article_stmt->bind_param('i', $article_id);
$article_stmt->execute();
$article_stmt->store_result();

if ($article_stmt->num_rows === 0) {
    $article_stmt->close();
    http_response_code(404);
    exit('<h1>Article not found</h1>');
}

$article_stmt->bind_result(
    $title,
    $content,
    $image_url,
    $created_at,
    $updated_at,
    $status,
    $author_id,
    $author_username
);
$article_stmt->fetch();
$article_stmt->close();

/*-------------------------------------------------
 |  6. Access control
 *------------------------------------------------*/
$can_view = false;

if ($user_role === 'editor') {
    $can_view = true;
} elseif ($user_role === 'journalist') {
    $can_view = ($author_id == $user_id) || ($status === 'approved');
} elseif ($status === 'approved') {
    $can_view = true;
}

if (!$can_view) {
    http_response_code(404);
    exit('<h1>Article not found</h1>');
}

/*-------------------------------------------------
 |  7. Fetch approved comments
 *------------------------------------------------*/
$comments = [];
$cstmt = $conn->prepare(
    "SELECT name, content, created_at
       FROM comments
      WHERE article_id = ? AND status = 'approved'
   ORDER BY created_at DESC"
);
$cstmt->bind_param('i', $article_id);
$cstmt->execute();
$cstmt->bind_result($c_name, $c_content, $c_created);
while ($cstmt->fetch()) {
    $comments[] = [
        'name'       => $c_name,
        'content'    => $c_content,
        'created_at' => $c_created,
    ];
}
$cstmt->close();

/*-------------------------------------------------
 |  8. Build a robust “Back” URL
 *------------------------------------------------*/
$back_url = $_SERVER['HTTP_REFERER'] ?? 'index.php';   // graceful fallback
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>

    <!-- Bootstrap core -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f8f9fa;
            padding: 30px;
            max-width: 900px;
            margin: auto;
        }

        .article-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        .back-btn {
            margin-bottom: 20px;
        }

        .article-meta {
            color: #666;
            font-size: .9rem;
            margin-bottom: 20px;
        }

        .comment {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 15px;
            background: #fff;
        }

        .comment small {
            color: #777;
        }
    </style>
</head>

<body>

    <!-- BACK BUTTON -->
    <a href="<?= htmlspecialchars($back_url) ?>"
        class="btn btn-secondary back-btn">
        &larr; Back
    </a>

    <?php
    // Fetch Active Sidebar Ads for Article Page
    $sidebarAds = $conn->query("SELECT * FROM advertisements WHERE status='active' AND position='home_sidebar' ORDER BY start_date DESC"); // Or a specific 'article_sidebar' position if we created it, but reusing home_sidebar or article_bottom as per plan
    ?>

    <div class="row">
        <div class="col-lg-8">
            <!-- ARTICLE -->
            <h1><?= htmlspecialchars($title) ?></h1>

            <div class="article-meta">
                By <strong><?= htmlspecialchars($author_username) ?></strong> |
                Published on <?= date('F j, Y, g:i a', strtotime($created_at)) ?>
                <?php if ($updated_at !== $created_at): ?>
                    <br><small><em>Edited at <?= date('F j, Y, g:i a', strtotime($updated_at)) ?></em></small>
                <?php endif; ?>
            </div>

            <?php if ($image_url): ?>
                <img src="<?= htmlspecialchars($image_url) ?>"
                    alt="Article image"
                    class="article-image">
            <?php endif; ?>

            <div class="article-content" style="white-space:pre-wrap;">
                <?= nl2br(htmlspecialchars($content)) ?>
            </div>

            <!-- COMMENT FORM -->
            <div class="comment-box mt-5">
                <h3>Leave a Comment</h3>

                <?php if ($comment_submitted): ?>
                    <div class="alert alert-success">
                        Thank you! Your comment is awaiting moderation.
                    </div>
                <?php endif; ?>

                <?php if ($comments_enabled): ?>
                    <form method="post" class="mb-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input id="name" name="name" class="form-control"
                                maxlength="100" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Your Comment</label>
                            <textarea id="content" name="content"
                                class="form-control" rows="4" required></textarea>
                        </div>

                        <button class="btn btn-primary" type="submit">
                            Submit Comment
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Commenting is currently disabled for this article.
                    </div>
                <?php endif; ?>
            </div>

            <!-- COMMENT LIST -->
            <div class="comment-box">
                <h3 class="mt-5">Comments</h3>
                <?php if (empty($comments)): ?>
                    <p>No comments yet.</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <strong><?= htmlspecialchars($comment['name']) ?></strong><br>
                            <small><?= date(
                                        'F j, Y, g:i a',
                                        strtotime($comment['created_at'])
                                    ) ?></small>
                            <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- SIDEBAR ADS -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 2rem;">
                 <?php if ($sidebarAds && $sidebarAds->num_rows > 0): ?>
                    <h5 class="text-muted text-center mb-3">Sponsored</h5>
                    <?php while($sad = $sidebarAds->fetch_assoc()): ?>
                        <div class="mb-4 text-center">
                            <a href="<?= htmlspecialchars($sad['link_url']) ?>" target="_blank">
                                <img src="<?= htmlspecialchars($sad['image_url']) ?>" class="img-fluid rounded shadow-sm">
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>