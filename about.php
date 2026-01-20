<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Us | Uttara-Times</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(90deg, #003049, #1a4c77);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: 1px;
        }

        /* Card Styling */
        .about-card {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        .about-card:hover {
            transform: translateY(-5px);
        }

        h2 {
            font-weight: 700;
            color: #003049;
        }

        .back-btn {
            border-radius: 50px;
            padding: 0.4rem 1.2rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #003049;
            color: #fff;
        }

        .about-card p {
            line-height: 1.8;
            text-align: justify;
            color: #444;
            font-size: 1.05rem;
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand text-white mx-auto" href="index.php">Uttara-Times</a>
        </div>
    </nav>

    <!-- Spacer -->
    <div style="height:4.5rem;"></div>

    <!-- About Section -->
    <div class="container py-5">
        <a href="index.php" class="btn btn-outline-secondary mb-4 back-btn">&larr; Back</a>
        <h2 class="mb-4 text-center">About <span style="color:#d62828;">Uttara-Times</span></h2>

        <div class="card about-card p-4 mx-auto" style="max-width: 750px;">
            <p>
                <strong>Uttara Times</strong> is a modern digital newspaper built with the vision of bringing local news to readers in a faster, more transparent, and interactive way.
                <br><br>
                Our team developed a <em>Web-Based News Management System</em> tailored for newspapers like <strong>Ajker Patrika</strong> and <strong>Uttara Times</strong>, addressing the limitations of traditional manual workflows.
                <br><br>
                The system is powered by <span class="text-primary fw-semibold">PHP (backend), MySQL (database)</span>, and <span class="text-primary fw-semibold">HTML, CSS, JavaScript with Bootstrap (frontend)</span>, ensuring a responsive and secure platform accessible across devices.
                <br><br>
                With <strong>role-based access control</strong>, journalists can submit articles, editors can review and approve content, and readers can interact through comments and tags. Articles seamlessly flow from <em>submission → editorial approval → real-time publishing</em>, eliminating delays of manual processes.
                <br><br>
                For added security, the system implements <strong>password hashing, SQL injection prevention, and comment moderation</strong>, ensuring credibility and trust.
                <br><br>
                By digitizing the entire news cycle, <strong>Uttara Times</strong> empowers journalists and editors to work more efficiently, while offering readers a reliable, engaging, and eco-friendly alternative to traditional print media.
            </p>
        </div>
    </div>

    <footer class="bg-dark text-light text-center py-3 mt-5" style="position:relative;z-index:10;">
    Developed by MRR WebDev Solutions
    </footer>
</body>

</html>