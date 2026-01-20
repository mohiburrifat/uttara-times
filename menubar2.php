<?php
/* editor_menubar.php */
if (session_status() === PHP_SESSION_NONE) session_start();
$username = htmlspecialchars($_SESSION['username'] ?? 'Editor');
$avatar   = "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=random&size=128";
?>
<!-- ▒▒ NAVBAR ▒▒ -->
<nav class="navbar">
    <!-- 3-bar hamburger -->
    <button class="navbar-toggler shadow-sm" id="toggleSidebar">
        <span class="hamburger">
            <span></span><span></span><span></span>
        </span>
    </button>

    <!-- centered brand -->
    <span class="navbar-brand">Uttara-Times</span>

    <!-- profile circle -->
    <div class="profile-wrapper position-relative">
        <img src="<?= $avatar ?>" alt="avatar" id="profileAvatar"
            class="avatar rounded-circle shadow-sm">
        <div id="profileMenu" class="profile-menu shadow-lg">
            <div class="px-3 py-2 fw-semibold"><?= $username ?></div>
            <hr class="my-1">
            <a href="logout.php" class="dropdown-item px-3 py-2 text-danger">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a>
        </div>
    </div>
</nav>

<!-- ▒▒ SIDEBAR ▒▒ -->
<div class="sidebar" id="sidebar">
    <div class="user-profile">
        <img src="<?= $avatar ?>" alt="avatar">
        <div>
            <div><?= $username ?></div>
            <small class="text-muted">Editor</small>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li><a href="editor_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
        <li><a href="articles.php"><i class="fas fa-newspaper me-2"></i>Articles</a></li>
        <li><a href="pending_articles.php"><i class="fas fa-clock me-2"></i>Pending Approval</a></li>
        <li><a href="journalists.php"><i class="fas fa-users me-2"></i>Journalists</a></li>
        <li><a href="add_journalist.php"><i class="fas fa-user-plus me-2"></i>Add Journalist</a></li>
        <li><a href="manage_comment.php"><i class="fas fa-comments me-2"></i>Manage Comments</a></li>
        <li><a href="comment_settings.php"><i class="fas fa-comment me-2"></i>Comment Settings</a></li>
        <li><a href="tag.php"><i class="fas fa-tags me-2"></i>Tags</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
    </ul>

    <!-- sidebar footer -->
    <div class="sidebar-footer text-center small">
        © 2025 MRR
    </div>
</div>

<!-- overlay -->
<div class="overlay" id="overlay"></div>

<!-- ▒▒ STYLES ▒▒ -->
<style>
    :root {
        --primary: #6fb1fc;
        --secondary: #c0e0f7;
        --background: #f8f9fa;
        --card-bg: #ffffff;
        --text-dark: #343a40;
        --text-muted: #6c757d;
        --sidebar-bg: #e9f1fb;
        --brand-color: #5089c6;
        --accent: #4364f7
    }

    button,
    a {
        transition: all .25s ease-out
    }

    /* navbar */
    .navbar {
        background: var(--sidebar-bg);
        box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
        padding: .6rem 1rem;
        position: sticky;
        top: 0;
        z-index: 1100;
        display: flex;
        align-items: center;
        justify-content: space-between
    }

    .navbar-brand {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        font-weight: bold;
        color: var(--brand-color);
        font-size: 1.3rem
    }

    .navbar-toggler {
        background: none;
        border: none;
        padding: .25rem .4rem;
        border-radius: .4rem
    }

    /* animated hamburger */
    .hamburger {
        display: inline-block;
        position: relative;
        width: 24px;
        height: 18px
    }

    .hamburger span {
        position: absolute;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--text-dark);
        border-radius: 2px;
        transition: transform .35s, opacity .35s, top .35s
    }

    .hamburger span:nth-child(1) {
        top: 0
    }

    .hamburger span:nth-child(2) {
        top: 7.5px
    }

    .hamburger span:nth-child(3) {
        top: 15px
    }

    .navbar-toggler.active .hamburger span:nth-child(1) {
        transform: rotate(45deg);
        top: 7.5px
    }

    .navbar-toggler.active .hamburger span:nth-child(2) {
        opacity: 0
    }

    .navbar-toggler.active .hamburger span:nth-child(3) {
        transform: rotate(-45deg);
        top: 7.5px
    }

    /* profile */
    .avatar {
        width: 40px;
        height: 40px;
        cursor: pointer
    }

    .profile-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 48px;
        background: var(--card-bg);
        border-radius: 10px;
        overflow: hidden;
        min-width: 170px;
        z-index: 1200
    }

    .profile-menu .dropdown-item {
        color: var(--text-dark);
        text-decoration: none
    }

    .profile-menu .dropdown-item:hover {
        background: var(--background)
    }

    /* sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        width: 255px;
        background: var(--sidebar-bg);
        transform: translateX(-100%);
        transition: transform .35s cubic-bezier(.4, 0, .2, 1);
        z-index: 1000;
        display: flex;
        flex-direction: column;
        overflow-y: auto
    }

    .sidebar.show {
        transform: none
    }

    @media(min-width:768px) {
        .sidebar {
            transform: none
        }
    }

    .user-profile {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f1f5fb
    }

    .user-profile img {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        margin-right: .8rem
    }

    .sidebar-menu {
        flex: 1;
        list-style: none;
        margin: 0;
        padding: 0
    }

    .sidebar-menu li a {
        display: block;
        padding: .75rem 1.2rem;
        color: var(--text-dark);
        text-decoration: none;
        border-left: 4px solid transparent
    }

    .sidebar-menu li a:hover {
        background: #dbeafe;
        border-left-color: var(--accent);
        color: var(--accent)
    }

    .sidebar-footer {
        padding: .6rem;
        background: #e3ebfb;
        color: var(--text-muted)
    }

    /* overlay */
    .overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .4);
        z-index: 900
    }

    .overlay.show {
        display: block
    }

    @media(min-width:768px) {
        .overlay {
            display: none !important
        }
    }
</style>

<!-- ▒▒ SCRIPT ▒▒ -->
<script>
    const sidebar = document.getElementById('sidebar'),
        overlay = document.getElementById('overlay'),
        burger = document.getElementById('toggleSidebar'),
        avatar = document.getElementById('profileAvatar'),
        menu = document.getElementById('profileMenu');

    /* toggle sidebar + animate hamburger */
    burger.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
        burger.classList.toggle('active');
    });

    /* hide sidebar when overlay clicked */
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        burger.classList.remove('active');
    });

    /* profile dropdown */
    avatar.addEventListener('click', e => {
        e.stopPropagation();
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    });
    document.addEventListener('click', e => {
        if (!menu.contains(e.target)) menu.style.display = 'none';
    });
</script>