<!-- journalist_menu.php -->
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        background-color: #f8f9fa;
        overflow-x: hidden;
    }

    /* Top Navbar */
    .top-navbar {
        background-color: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        padding: 10px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 1040;
    }

    .hamburger {
        font-size: 24px;
        background: none;
        border: none;
        color: #333;
        cursor: pointer;
    }

    /* Sidebar */
    .sidebar {
        background-color: #1e1e2f;
        color: white;
        height: 100vh;
        width: 240px;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1030;
        padding-top: 60px;
        transition: transform 0.3s ease;
    }

    .sidebar-hidden {
        transform: translateX(-100%);
    }

    .sidebar a {
        color: #ddd;
        display: block;
        padding: 12px 20px;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .sidebar a:hover,
    .sidebar .active a {
        background-color: #343a40;
        color: #fff;
    }

    /* Adjust for small screens */
    @media (max-width: 767.98px) {
        .sidebar {
            transform: translateX(-100%);
        }
    }
</style>

<!-- Top Navbar -->
<div class="top-navbar">
    <button class="hamburger" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <h4 class="m-0">Journalist Dashboard</h4>
    <span class="text-muted d-none d-md-inline">
        <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>
    </span>
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
        sidebar.classList.toggle('sidebar-hidden');
    }
</script>