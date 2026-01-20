<?php
session_start();
require 'db.php';
require 'menubar3.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tags | Uttara-Times</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f8fa;
        }

        .container {
            margin-top: 40px;
        }

        .tag-block {
            background: #ffffff;
            border: 1px solid #e3e3e3;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .tag-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #007bff;
        }

        .article-link {
            text-decoration: none;
            color: #333;
            display: block;
            margin-top: 10px;
        }

        .article-link:hover {
            color: #007bff;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Back button -->


        <h2 class="mb-4 text-center">Tags and Related Articles</h2>

        <?php
        $tagQuery = $conn->query("SELECT * FROM tags ORDER BY name ASC");
        while ($tag = $tagQuery->fetch_assoc()):
            $tag_id   = $tag['tag_id']; // Adjust if your PK field is different
            $tag_name = htmlspecialchars($tag['name']);

            // Get related articles for this tag
            $stmt = $conn->prepare("
                SELECT a.article_id, a.title
                FROM articles a
                JOIN article_tags at ON a.article_id = at.article_id
                WHERE at.tag_id = ? AND a.status = 'approved'
            ");
            $stmt->bind_param("i", $tag_id);
            $stmt->execute();
            $result = $stmt->get_result();
        ?>
            <div class="tag-block">
                <div class="tag-title"><?= $tag_name ?></div>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($article = $result->fetch_assoc()): ?>
                        <a class="article-link" href="view_article.php?id=<?= $article['article_id'] ?>">
                            <?= htmlspecialchars($article['title']) ?>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-muted mt-2">No articles found for this tag.</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

</body>

</html>