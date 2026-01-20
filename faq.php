<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FAQ | Uttara-Times</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(90deg, #003049, #1a4c77);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }

        h2 {
            font-weight: 700;
            color: #003049;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        .accordion-button {
            font-weight: 600;
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
    </style>
</head>

<body class="bg-light">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand text-white mx-auto" href="index.php">Uttara-Times</a>
        </div>
    </nav>
    <div style="height:4.5rem;"></div>

    <!-- FAQ Section -->
    <div class="container py-5">
        <a href="index.php" class="btn btn-outline-secondary mb-3 back-btn">&larr; Back</a>
        <h2 class="mb-4 text-center">Frequently Asked Questions (FAQ)</h2>

        <div class="accordion" id="faqAccordion">

            <!-- Q1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq1">

                </h2>
                <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">

                </div>
            </div>

            <!-- Q2 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq2">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                        What is Uttara Times?
                    </button>
                </h2>
                <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Uttara Times is a digital news platform designed to provide readers with fast, reliable, and interactive access to news. It was built as part of a web-based news management project for Ajker Patrika, modernizing traditional manual publishing processes.
                    </div>
                </div>
            </div>

            <!-- Q3 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq3">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                        How does the system work?
                    </button>
                </h2>
                <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Journalists submit articles through the portal → Editors review and approve/reject submissions → Approved articles are published in real-time → Readers can view, comment, and engage with the content.
                    </div>
                </div>
            </div>

            <!-- Q4 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq4">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                        Who can use this system?
                    </button>
                </h2>
                <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <ul>
                            <li><strong>Journalists:</strong> Submit and manage their news articles.</li>
                            <li><strong>Editors:</strong> Review, edit, and approve/reject articles before publishing.</li>
                            <li><strong>Readers:</strong> Access published articles, leave comments, and interact with content.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Q5 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq5">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                        What technologies were used to build the project?
                    </button>
                </h2>
                <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        The system was developed using PHP for backend logic, MySQL for database management, and HTML, CSS, JavaScript, and Bootstrap for the frontend interface. It was tested on a XAMPP server.
                    </div>
                </div>
            </div>

            <!-- Q6 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq6">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                        How is security ensured?
                    </button>
                </h2>
                <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="faq6" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        The platform uses hashed passwords, SQL injection prevention, and comment moderation to keep user data and editorial integrity safe. Only approved content gets published to the public site.
                    </div>
                </div>
            </div>

            <!-- Q7 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq7">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                        Can readers directly publish comments?
                    </button>
                </h2>
                <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="faq7" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        No, reader comments are stored with a pending status until reviewed and approved by an editor/admin to prevent spam and inappropriate content.
                    </div>
                </div>
            </div>

            <!-- Q8 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq8">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
                        Is the system mobile-friendly?
                    </button>
                </h2>
                <div id="collapse8" class="accordion-collapse collapse" aria-labelledby="faq8" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Yes, the system is fully responsive and can be accessed on desktops, tablets, and smartphones.
                    </div>
                </div>
            </div>

            <!-- Q9 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq9">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse9" aria-expanded="false" aria-controls="collapse9">
                        Why was this project created?
                    </button>
                </h2>
                <div id="collapse9" class="accordion-collapse collapse" aria-labelledby="faq9" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        This project was developed to help newspapers like Ajker Patrika and Uttara Times move away from manual workflows and adopt a modern, efficient, and interactive digital publishing system.
                    </div>
                </div>
            </div>

            <!-- Q10 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq10">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse10" aria-expanded="false" aria-controls="collapse10">
                        What are the future plans for this system?
                    </button>
                </h2>
                <div id="collapse10" class="accordion-collapse collapse" aria-labelledby="faq10" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Planned features include:
                        <ul>
                            <li>A dedicated mobile app for Android and iOS.</li>
                            <li>Push notifications for breaking news.</li>
                            <li>Advanced analytics dashboards for editors.</li>
                            <li>Multi-language support (Bangla & English).</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Q11 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="faq11">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse11" aria-expanded="false" aria-controls="collapse11">
                        How does this system benefit society?
                    </button>
                </h2>
                <div id="collapse11" class="accordion-collapse collapse" aria-labelledby="faq11" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        By digitizing local journalism, it ensures faster news delivery, better audience engagement, reduced paper waste, and helps smaller newspapers stay competitive in the digital era.
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <footer class="bg-dark text-light text-center py-3 mt-5" style="position:relative;z-index:10;">
    Developed by MRR WebDev Solutions
    </footer>
</body>

</html>