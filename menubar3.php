<?php
/*  editor_menubar.php  */
if (session_status() === PHP_SESSION_NONE) session_start();
$username = htmlspecialchars($_SESSION['username'] ?? 'Editor');
$role     = 'Editor';
$avatar   = 'https://ui-avatars.com/api/?name=' . urlencode($username) . '&background=random&size=256';
?>
<!-- ░░ FONT AWESOME ░░ -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<!-- ░░ NAVBAR ░░ -->
<nav class="e-nav">
    <button id="burger" class="e-burger" aria-label="Menu"><i class="fas fa-bars"></i></button>
    <span class="e-title">Uttara-Times</span>
    <div class="e-avatar-wrap">
        <img src="<?= $avatar ?>" alt="<?= $username ?>" id="avatar" class="e-avatar">
        <div id="dropdown" class="e-dropdown">
            <div class="px-3 py-2 fw-semibold"><?= $username ?></div>
            <hr class="my-1">
            <a href="logout.php" class="dropdown-item px-3 py-2 text-danger">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a>
        </div>
    </div>
</nav>

<!-- ░░ DRAWER ░░ -->
<aside id="drawer" class="e-drawer">
    <a href="profile.php" class="e-card text-decoration-none">
        <div class="e-ring"><img src="<?= $avatar ?>" alt="<?= $username ?>"></div>
        <div>
            <div class="e-name"><?= $username ?></div><span class="e-badge"><?= $role ?></span>
        </div>
    </a>
    <ul id="navList" class="e-nav-list">
        <?php
        $links = [
            ["editor_dashboard.php", "fas fa-tachometer-alt", "Dashboard"],
            ["articles.php", "fas fa-newspaper", "Articles"],
            ["pending_articles.php", "fas fa-clock", "Pending"],
            ["journalists.php", "fas fa-users", "Journalists"],
            ["add_journalist.php", "fas fa-user-plus", "Add Journalist"],
            ["manage_comment.php", "fas fa-comments", "Comments"],
            ["comment_settings.php", "fas fa-comment", "Comment Settings"],
            ["manage_plans.php", "fas fa-crown", "Subscription Plans"],
            ["manage_ads.php", "fas fa-ad", "Advertisements"],
            ["manage_popups.php", "fas fa-window-maximize", "Popups"],
            ["tag.php", "fas fa-tags", "Tags"],
            ["logout.php", "fas fa-sign-out-alt", "Logout"]
        ];
        foreach ($links as [$href, $icon, $label]): ?>
            <li><a href="<?= $href ?>"><i class="<?= $icon ?> me-2"></i><?= $label ?></a></li>
        <?php endforeach; ?>
    </ul>
    <footer class="e-footer">© 2025 MRR</footer>
</aside>
<div id="mask" class="e-mask"></div>

<!-- ░░ STYLES ░░ -->
<style>
    :root {
        --drawer: #dfe4ff;
        --brand: #5089c6;
        --accent: #4364f7;
        --txt: #334155;
        --muted: #64748b;
        --navH: 60px;
        --gap: 20px;
        --drawerW: 260px;
    }

    * {
        box-sizing: border-box
    }

    .e-nav {
        height: var(--navH);
        background: var(--drawer);
        display: flex;
        align-items: center;
        gap: .8rem;
        padding: 0 1rem;
        position: sticky;
        top: 0;
        z-index: 1101;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
    }

    .e-title {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        font-weight: 600;
        font-size: 1.15rem;
        color: var(--brand);
        white-space: nowrap;
    }

    .e-burger {
        border: 0;
        background: transparent;
        font-size: 1.3rem;
        color: var(--txt);
        cursor: pointer;
    }

    .e-burger i {
        transition: transform 0.3s ease;
    }

    .e-burger.active i.fa-times {
        transform: rotate(90deg);
    }

    .e-avatar-wrap {
        margin-left: auto;
        position: relative;
        cursor: pointer;
    }

    .e-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 0 0 2px #fff;
    }

    .e-dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 46px;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        min-width: 170px;
        box-shadow: 0 8px 18px rgba(0, 0, 0, .12);
    }

    .e-dropdown .dropdown-item {
        color: var(--txt);
    }

    .e-dropdown .dropdown-item:hover {
        background: #f8f9fa;
    }

    .e-drawer {
        position: fixed;
        left: 0;
        top: 0;
        height: 100%;
        width: var(--drawerW);
        background: var(--drawer);
        transform: translateX(-100%);
        transition: transform .35s cubic-bezier(.4, 0, .2, 1);
        z-index: 1000;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        padding-top: var(--navH);
    }

    .e-drawer.open {
        transform: none;
    }

    .e-card {
        margin: 0rem 1rem 1rem;
        border-radius: 1.25rem;
        padding: 1rem;
        display: flex;
        gap: .9rem;
        align-items: center;
        background: rgba(255, 255, 255, .14);
        backdrop-filter: blur(15px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, .06), inset 0 0 0 1px rgba(255, 255, 255, .35);
    }

    .e-ring {
        position: relative;
        flex-shrink: 0;
    }

    .e-ring::before {
        content: '';
        position: absolute;
        inset: -3px;
        border-radius: 50%;
        padding: 3px;
        background: conic-gradient(var(--accent), #6fb1fc, var(--accent));
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask-composite: exclude;
        -webkit-mask-composite: xor;
        animation: spin 5s linear infinite;
    }

    .e-ring img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        border: 3px solid #fff;
        object-fit: cover;
    }

    @keyframes spin {
        to {
            transform: rotate(1turn)
        }
    }

    .e-name {
        font-weight: 600;
        font-size: 1rem;
        color: #fff;
    }

    .e-badge {
        margin-top: 2px;
        font-size: .72rem;
        padding: 2px 8px;
        border-radius: 12px;
        color: #e2e8f0;
        background: rgba(0, 0, 0, .25);
        letter-spacing: .3px;
    }

    .e-nav-list {
        list-style: none;
        padding: 0 1rem 1rem;
        margin: 0;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: .55rem;
    }

    .e-nav-list a {
        display: flex;
        align-items: center;
        gap: .85rem;
        padding: .66rem 1rem;
        border-radius: 1.85rem;
        font-weight: 500;
        color: var(--txt);
        text-decoration: none;
    }

    .e-nav-list a:hover {
        background: linear-gradient(135deg, #6fb1fc 0%, var(--accent) 100%);
        color: #fff;
    }

    .e-nav-list a.active {
        background: var(--accent);
        color: #fff;
    }

    .e-footer {
        padding: .65rem;
        text-align: center;
        font-size: .78rem;
        color: var(--muted);
    }

    .e-mask {
        display: none;
        position: fixed;
        left: 0;
        right: 0;
        top: var(--navH);
        height: calc(100% - var(--navH));
        background: rgba(0, 0, 0, .45);
        z-index: 900;
    }

    .e-mask.show {
        display: block;
    }

    @media(min-width:992px) {
        .e-mask {
            display: none !important
        }

        body.shifted {
            margin-left: var(--drawerW);
            transition: margin-left .35s cubic-bezier(.4, 0, .2, 1);
        }
    }
</style>

<!-- ░░ SCRIPT ░░ -->
<script>
    const burger = document.getElementById('burger');
    const drawer = document.getElementById('drawer');
    const mask = document.getElementById('mask');
    const avatar = document.getElementById('avatar');
    const dropdown = document.getElementById('dropdown');
    const navList = document.getElementById('navList');
    const DESKTOP = 992;

    const isDesktop = () => innerWidth >= DESKTOP;

    function setDesktopState() {
        if (isDesktop()) {
            drawer.classList.add('open');
            document.body.classList.add('shifted');
            mask.classList.remove('show');
            burger.classList.remove('active');
            burger.querySelector('i').classList.add('fa-bars');
            burger.querySelector('i').classList.remove('fa-times');
        }
    }
    setDesktopState();
    addEventListener('resize', setDesktopState);

    burger.onclick = () => {
        const open = drawer.classList.toggle('open');
        burger.classList.toggle('active');
        burger.querySelector('i').classList.toggle('fa-bars');
        burger.querySelector('i').classList.toggle('fa-times');
        if (isDesktop()) {
            document.body.classList.toggle('shifted', open);
        } else {
            mask.classList.toggle('show', open);
        }
    };

    mask.onclick = () => {
        drawer.classList.remove('open');
        burger.classList.remove('active');
        burger.querySelector('i').classList.add('fa-bars');
        burger.querySelector('i').classList.remove('fa-times');
        mask.classList.remove('show');
    };

    avatar.onclick = e => {
        e.stopPropagation();
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    };

    addEventListener('click', e => {
        if (!dropdown.contains(e.target)) dropdown.style.display = 'none';
    });

    const saveActive = url => {
        sessionStorage.setItem('eActive', url);
        updateActive(url);
    };
    const updateActive = url => navList.querySelectorAll('a').forEach(a => a.classList.toggle('active', a.getAttribute('href') === url));
    const stored = sessionStorage.getItem('eActive');
    if (stored) updateActive(stored);
    else {
        const cur = location.pathname.split('/').pop();
        const link = navList.querySelector(`a[href='${cur}']`);
        if (link) link.classList.add('active');
    }
    navList.onclick = e => {
        const a = e.target.closest('a');
        if (a) saveActive(a.getAttribute('href'));
    };
</script>