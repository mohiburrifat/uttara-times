<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

$subLink = 'subscription.php';


$tagsResult = $conn->query("SELECT * FROM tags");
$journalistsResult = $conn->query("SELECT user_id, username FROM users WHERE role = 'journalist'");

// Filters
$tagFilter = isset($_GET['tag_id']) ? intval($_GET['tag_id']) : null;
$authorFilter = isset($_GET['author_id']) ? intval($_GET['author_id']) : null;
$dateOrder = isset($_GET['date']) && $_GET['date'] === 'oldest' ? 'ASC' : 'DESC';

// Base query
$query = "
    SELECT a.article_id, a.title, a.content, a.image_url, a.created_at, a.updated_at,
           u.username AS author
    FROM articles a
    LEFT JOIN users u ON a.author_id = u.user_id
";

// Apply filters
$conditions = ["a.status = 'approved'"];
if ($tagFilter) {
    $query .= " JOIN article_tags at ON a.article_id = at.article_id";
    $conditions[] = "at.tag_id = $tagFilter";
}
if ($authorFilter) {
    $conditions[] = "a.author_id = $authorFilter";
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " ORDER BY a.updated_at $dateOrder LIMIT 12";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Uttara-Times</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 4.5rem;
            font-family: 'Outfit', sans-serif;
        }

        .navbar {
            background-color: #003049 !important;
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
            font-size: 1.5rem;
        }

        .offcanvas {
            background-color: #f8f9fa;
        }

        .article-card {
            background: #fff;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .article-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }

        .article-image {
            height: 220px;
            object-fit: cover;
            width: 100%;
            transition: transform 0.5s ease;
        }

        .article-card:hover .article-image {
            transform: scale(1.03);
        }

        .article-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .article-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 0.8rem;
            line-height: 1.3;
        }

        .article-meta {
            font-size: 0.85rem;
            color: #888;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .article-excerpt {
            color: #555;
            font-size: 0.95rem;
            flex-grow: 1;
            margin-bottom: 1.5rem;
        }

        .read-more-btn {
            color: #003049;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            align-self: flex-start;
        }

        .read-more-btn:hover {
            color: #d62828;
            text-decoration: underline;
        }

        .ad-banner-top {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .sponsored-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #aaa;
            margin-bottom: 8px;
            display: block;
            text-align: center;
        }
    </style>
</head>


<body>

    <?php
    // Display pop-up advertisements (with error handling for tables that may not exist yet)
    if ($conn) {
        $tableExists = $conn->query("SHOW TABLES LIKE 'popup_advertisements'");
        if ($tableExists && $tableExists->num_rows > 0) {
            $popupAdsResult = $conn->query("SELECT * FROM popup_advertisements WHERE status = 'active'");
            if ($popupAdsResult && $popupAdsResult->num_rows > 0) {
                while ($popupAd = $popupAdsResult->fetch_assoc()) {
                    echo '<div class="popup-advertisement" style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 10px; border-radius: 5px;" id="popup-' . $popupAd['popup_id'] . '">'
                        . htmlspecialchars($popupAd['content']) .
                        '</div>';
                }
            }
        }
    }
    ?>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top shadow">
        <div class="container-fluid">
            <button class="btn btn-outline-light me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sideMenu">
                &#9776;
            </button>
            <a class="navbar-brand text-white" href="index.php">Uttara-Times</a>
            <a href="login.php" class="btn btn-outline-light text-white">Login</a>
        </div>
    </nav>

    <!-- Top Center Links under Navbar -->
    <div class="container-fluid py-2 border-bottom" style="background:#fff;">
        <div class="d-flex justify-content-center align-items-center gap-4">
            <a href="faq.php" class="small fw-bold text-decoration-none text-secondary text-uppercase"><i class="fas fa-question-circle me-1"></i> FAQ</a>
            <a href="about.php" class="small fw-bold text-decoration-none text-secondary text-uppercase"><i class="fas fa-info-circle me-1"></i> About Us</a>
            <a href="contact.php" class="small fw-bold text-decoration-none text-secondary text-uppercase"><i class="fas fa-users me-1"></i> Our Team</a>
            <a href="<?= $subLink ?>" class="small fw-bold text-decoration-none text-danger text-uppercase"><i class="fas fa-crown me-1"></i> Subscribe</a>
        </div>
    </div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top shadow">
        <div class="container-fluid">
            <button class="btn btn-outline-light me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sideMenu">
                &#9776;
            </button>
            <a class="navbar-brand text-white" href="index.php">Uttara-Times</a>
            <a href="login.php" class="btn btn-outline-light text-white">Login</a>
        </div>
    </nav>

    <!-- SIDEMENU FILTER -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sideMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Filter Options</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form method="GET" action="index.php" class="d-grid gap-3">
                <!-- Tag Filter -->
                <div>
                    <label class="form-label">Tag</label>
                    <select name="tag_id" class="form-select">
                        <option value="">-- All Tags --</option>
                        <?php while ($tag = $tagsResult->fetch_assoc()): ?>
                            <option value="<?= $tag['tag_id'] ?>" <?= $tagFilter == $tag['tag_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tag['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Journalist Filter -->
                <div>
                    <label class="form-label">Journalist</label>
                    <select name="author_id" class="form-select">
                        <option value="">-- All Journalists --</option>
                        <?php while ($author = $journalistsResult->fetch_assoc()): ?>
                            <option value="<?= $author['user_id'] ?>" <?= $authorFilter == $author['user_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($author['username']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <label class="form-label">Sort By Date</label>
                    <select name="date" class="form-select">
                        <option value="newest" <?= $dateOrder === 'DESC' ? 'selected' : '' ?>>Newest First</option>
                        <option value="oldest" <?= $dateOrder === 'ASC' ? 'selected' : '' ?>>Oldest First</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </form>
        </div>
    </div>

    <!-- ADS AND POPUPS LOGIC -->
    <?php
    // Fetch Active Ads
    $ads = $conn->query("SELECT * FROM advertisements WHERE status='active' ORDER BY start_date DESC");
    $topAd = null;
    $sidebarAds = [];
    while ($ad = $ads->fetch_assoc()) {
        if ($ad['position'] === 'home_top' && !$topAd) {
            $topAd = $ad;
        } elseif ($ad['position'] === 'home_sidebar') {
            $sidebarAds[] = $ad;
        }
    }

    // Fetch Active Popup
    $popupRes = $conn->query("SELECT * FROM popups WHERE is_active=1 ORDER BY created_at DESC LIMIT 1");
    $popup = $popupRes->fetch_assoc();
    ?>

    <!-- Top Banner Ad -->
    <!-- Top Banner Ad -->
    <?php if ($topAd): ?>
        <div class="container mt-4 mb-2 text-center">
            <a href="<?= htmlspecialchars($topAd['link_url']) ?>" target="_blank" class="d-block ad-banner-top">
                <img src="<?= htmlspecialchars($topAd['image_url']) ?>" class="img-fluid w-100" style="max-height: 200px; object-fit: cover;">
            </a>
            <span class="sponsored-label mt-1">Advertisement</span>
        </div>
    <?php endif; ?>

    <!-- Display advertisements on homepage -->
    <?php
    $adsResult = $conn->query("SELECT * FROM advertisements");
    if ($adsResult) {
        while ($ad = $adsResult->fetch_assoc()) {
            if (isset($ad['content']) && !empty($ad['content'])) {
                echo '<div class="advertisement">' . htmlspecialchars($ad['content']) . '</div>';
            }
        }
    }
    ?>

    <!-- ARTICLE GRID & SIDEBAR -->
    <div class="container mt-4">
        <div class="row">
            <!-- Main Content -->
            <div class="<?= count($sidebarAds) > 0 ? 'col-lg-9' : 'col-12' ?>">
                <div class="row g-4">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($article = $result->fetch_assoc()): ?>
                            <div class="col-12 col-md-6 col-lg-<?= count($sidebarAds) > 0 ? '6' : '4' ?>">
                                <div class="article-card">
                                    <?php if (!empty($article['image_url'])): ?>
                                        <div style="overflow:hidden">
                                            <img src="<?= htmlspecialchars($article['image_url']) ?>" class="article-image" alt="Article Image">
                                        </div>
                                    <?php endif; ?>
                                    <div class="article-content">
                                        <div class="article-meta">
                                            <i class="far fa-user me-1"></i> <?= htmlspecialchars($article['author']) ?> &nbsp;&bull;&nbsp; <i class="far fa-clock me-1"></i> <?= date('M d', strtotime($article['updated_at'])) ?>
                                        </div>
                                        <a href="view_article.php?id=<?= $article['article_id'] ?>" class="text-decoration-none">
                                            <h2 class="article-title"><?= htmlspecialchars($article['title']) ?></h2>
                                        </a>
                                        <p class="article-excerpt"><?= nl2br(htmlspecialchars(mb_strimwidth($article['content'], 0, 120, '...'))) ?></p>
                                        <a href="view_article.php?id=<?= $article['article_id'] ?>" class="read-more-btn">Read Full Article <i class="fas fa-arrow-right ms-2"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center text-muted">No articles found with current filters.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar Ads -->
            <?php if (count($sidebarAds) > 0): ?>
                <div class="col-lg-3">
                    <div class="sticky-top" style="top: 6rem;">
                        <div class="d-flex align-items-center mb-3">
                            <span class="text-secondary small fw-bold text-uppercase border-bottom border-2 border-danger pb-1">Sponsored</span>
                        </div>
                        <?php foreach ($sidebarAds as $sad): ?>
                            <div class="mb-4 text-center">
                                <a href="<?= htmlspecialchars($sad['link_url']) ?>" target="_blank" class="d-block shadow-sm rounded overflow-hidden">
                                    <img src="<?= htmlspecialchars($sad['image_url']) ?>" class="img-fluid w-100">
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Popup Modal -->
    <?php if ($popup): ?>
        <div class="modal fade" id="promoModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Special Offer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <?= $popup['content'] // Raw content as it may contain HTML 
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('promoModal'));
                myModal.show();
            });
        </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <footer class="bg-dark text-light text-center py-3 mt-5" style="position:relative;z-index:10;">
        Developed by MRR WebDev Solutions
    </footer>
</body>

</html>