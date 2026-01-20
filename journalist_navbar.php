<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Guest";
?>

<!-- Bootstrap & Font Awesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    body {
        font-family: 'Georgia', serif;
        /* Newspaper-like font */
        background-color: #f4f4f4;
        margin: 0;
        overflow-x: hidden;
    }

    /* 📰 Top Navbar */
    .top-navbar {
        background: #b71c1c;
        /* Deep Red */
        color: #fff;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 1040;
    }

    .top-navbar h4 {
        color: #fff;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .hamburger {
        font-size: 24px;
        background: none;
        border: none;
        color: #fff;
    }

    /* 📰 Sidebar */
    .sidebar {
        background: #2c2c2c;
        /* Dark Gray */
        color: white;
        height: 100vh;
        width: 240px;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1030;
        padding-top: 60px;
        transition: transform 0.3s ease;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.15);
    }

    .sidebar-hidden {
        transform: translateX(-100%);
    }

    .sidebar h4 {
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #f5f5f5;
    }

    .sidebar a {
        color: #ddd;
        display: block;
        padding: 12px 20px;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.3s ease;
        margin: 5px 12px;
    }

    .sidebar a:hover {
        background: #b71c1c;
        color: #fff;
        transform: translateX(5px);
    }

    .sidebar .active a {
        background: #880e0e;
        color: #fff;
        font-weight: bold;
    }

    /* 📰 Main Content */
    .main-content {
        margin-left: 240px;
        padding: 40px 20px;
        transition: margin-left 0.3s ease;
    }

    .content-expanded {
        margin-left: 0 !important;
    }

    /* 📰 Mobile Friendly */
    @media (max-width: 767.98px) {
        .main-content {
            margin-left: 0;
            padding: 20px;
        }

        .sidebar {
            width: 200px;
        }
    }

    /* Username style */
    .username {
        font-weight: 500;
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 12px;
        border-radius: 20px;
    }
</style>

<!-- Top Navbar -->
<div class="top-navbar">
    <button class="hamburger" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <h4 class="m-0">📰 Journalist Dashboard</h4>
    <span class="username d-none d-md-inline"><?php echo $username; ?></span>
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

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('mainContent');
        sidebar.classList.toggle('sidebar-hidden');
        if (content) {
            content.classList.toggle('content-expanded');
        }
    }
</script>