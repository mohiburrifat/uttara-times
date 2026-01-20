<?php
// Editor profile page for Uttara-Times
$editor = [
    'name' => 'MR Rifat',
    'role' => 'Chief Editor',
    'email' => 'editor@uttaratimes.com',
    'bio' => 'MR Rifat is the Chief Editor of Uttara-Times, dedicated to upholding journalistic integrity and fostering a culture of truth and transparency. With over 10 years of experience in digital media, he leads the editorial team to deliver accurate and impactful news to the community.',
    'avatar' => 'https://ui-avatars.com/api/?name=MR+Rifat&background=003049&color=fff&size=128',
    'joined' => 'January 2015',
    'location' => 'Uttara, Dhaka, Bangladesh',
    'skills' => ['Leadership', 'Public Speaking', 'Journalism', 'Digital Strategy', 'Photography', 'Research'],
    'social' => [
        'facebook' => 'https://facebook.com/',
        'twitter' => 'https://twitter.com/',
        'linkedin' => 'https://linkedin.com/in/',
        'github' => 'https://github.com/'
    ],
    'achievements' => [
        '10+ years in Journalism & Digital Media',
        'Led Uttara-Times to 1M monthly readers',
        'Organized 50+ community awareness programs',
        'Awarded “Excellence in Journalism” 2022'
    ],
    'motto' => '“Truth is the foundation of freedom, and journalism is its voice.”'
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editor Profile | Uttara-Times</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }

        .profile-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
            max-width: 650px;
            margin: 60px auto 0 auto;
            padding: 2.5rem 2rem 2rem 2rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #003049;
            margin-bottom: 1rem;
        }

        .profile-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #003049;
        }

        .profile-role {
            font-size: 1.1rem;
            color: #1a4c77;
            font-weight: 500;
        }

        .profile-info {
            margin-top: 1.2rem;
        }

        .profile-info i {
            color: #003049;
            width: 22px;
        }

        .profile-bio {
            margin-top: 1.2rem;
            color: #444;
            font-size: 1.05rem;
        }

        .section-title {
            margin-top: 1.8rem;
            font-weight: 600;
            color: #003049;
            font-size: 1.1rem;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.3rem;
        }

        .skills span {
            background: #e9f2f9;
            color: #003049;
            border-radius: 30px;
            padding: 5px 12px;
            font-size: 0.9rem;
            margin: 3px;
            display: inline-block;
        }

        .social-links a {
            font-size: 1.2rem;
            margin: 0 8px;
            color: #003049;
            transition: 0.3s;
        }

        .social-links a:hover {
            color: #1a73e8;
        }

        .motto {
            font-style: italic;
            color: #555;
            margin-top: 1rem;
            border-left: 3px solid #003049;
            padding-left: 12px;
        }

        .back-btn {
            border-radius: 50px;
            padding: 0.4rem 1.2rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .back-btn:hover {
            background: #003049;
            color: #fff;
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top shadow" style="background-color:#003049;">
        <div class="container-fluid">
            <a class="navbar-brand text-white mx-auto" href="index.php">Uttara-Times</a>
        </div>
    </nav>
    <div style="height:4.5rem;"></div>
    <div class="container">
    <button type="button" onclick="window.history.back();" class="btn btn-outline-secondary back-btn">&larr; Back</button>
        <div class="profile-card text-center">
            <img src="<?= $editor['avatar'] ?>" alt="Editor Avatar" class="profile-avatar mb-2">
            <div class="profile-title"><?= $editor['name'] ?></div>
            <div class="profile-role mb-2"><i class="fas fa-user-tie me-1"></i><?= $editor['role'] ?></div>

            <div class="profile-info text-start mx-auto" style="max-width:420px;">
                <div class="mb-2"><i class="fas fa-envelope"></i> <span><?= $editor['email'] ?></span></div>
                <div class="mb-2"><i class="fas fa-calendar-alt"></i> <span>Joined: <?= $editor['joined'] ?></span></div>
                <div class="mb-2"><i class="fas fa-map-marker-alt"></i> <span><?= $editor['location'] ?></span></div>
            </div>

            <div class="profile-bio text-start mt-3"><?= $editor['bio'] ?></div>

            <!-- Skills -->
            <div class="section-title text-start">Skills</div>
            <div class="skills text-start">
                <?php foreach ($editor['skills'] as $skill): ?>
                    <span><?= $skill ?></span>
                <?php endforeach; ?>
            </div>

            <!-- Achievements -->
            <div class="section-title text-start">Achievements</div>
            <ul class="text-start">
                <?php foreach ($editor['achievements'] as $ach): ?>
                    <li><?= $ach ?></li>
                <?php endforeach; ?>
            </ul>

            <!-- Motto -->
            <div class="section-title text-start">Motto</div>
            <div class="motto"><?= $editor['motto'] ?></div>

            <!-- Social Links -->
            <div class="section-title text-start">Connect</div>
            <div class="social-links">
                <a href="<?= $editor['social']['facebook'] ?>"><i class="fab fa-facebook"></i></a>
                <a href="<?= $editor['social']['twitter'] ?>"><i class="fab fa-twitter"></i></a>
                <a href="<?= $editor['social']['linkedin'] ?>"><i class="fab fa-linkedin"></i></a>
                <a href="<?= $editor['social']['github'] ?>"><i class="fab fa-github"></i></a>
            </div>
        </div>
    </div>
    <footer class="bg-dark text-light text-center py-3 mt-5" style="position:relative;z-index:10;">
        Developed by MRR WebDev Solutions
    </footer>
</body>

</html>