<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us | Uttara-Times</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h2 {
            font-weight: 700;
            color: #003049;
        }

        .btn-custom {
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
        }
    </style>
</head>

<body class="bg-light">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top shadow" style="background-color:#003049;">
        <div class="container-fluid">
            <a class="navbar-brand text-white mx-auto" href="index.php">Uttara-Times</a>
        </div>
    </nav>
    <div style="height:4.5rem;"></div>



    <!-- Thanks + Sections -->
    <div class="container text-center my-5">
        <a href="index.php" class="btn btn-outline-secondary back-btn mb-4">&larr; Back</a>
        <p class="lead text-center">✨ Thanks for Visiting our Website! ✨</p>
        <p class="text-center text-muted" style="font-size:1.1rem;">
            We’re thrilled you stopped by! Your visit brightens our day and inspires us to keep creating, sharing, and improving.
            Dive in, explore our stories, and feel free to leave a little love 💛—because every click, comment, and smile from you fuels our passion.
            Remember, you’re always welcome here! 🌟
        </p>

        <div class="row justify-content-center g-3">
            <div class="col-md-4">
                <button class="btn btn-custom btn-primary w-100" onclick="askFaculty('web')">Web Engineering Team</button>
            </div>
            <div class="col-md-4">
                <button class="btn btn-custom btn-danger w-100" onclick="askFaculty('system')">System Analysis and Design Team</button>
            </div>
        </div>
        <div id="questionBox" class="mt-4" style="display:none;">
            <h5>Who is the Course Faculty?</h5>
            <input type="text" id="facultyAnswer" class="form-control mb-3" placeholder="Enter faculty name">
            <button class="btn btn-success" onclick="checkAnswer()">Submit</button>
            <p id="errorMsg" class="text-danger mt-2" style="display:none;">❌ Incorrect answer. Try again.</p>
        </div>
    </div>

    <footer class="bg-dark text-light text-center py-3 mt-5" style="position:relative;z-index:10;">
        Developed by MRR WebDev Solutions
    </footer>

    <script>
        let selectedSection = "";

        function askFaculty(section) {
            selectedSection = section;
            document.getElementById("questionBox").style.display = "block";
            document.getElementById("facultyAnswer").value = "";
            document.getElementById("errorMsg").style.display = "none";
        }

        function checkAnswer() {
            const answer = document.getElementById("facultyAnswer").value.trim().toLowerCase();
            if (selectedSection === "web" && answer === "suzad mohammod".toLowerCase()) {
                window.location.href = "web_engineering.php";
            } else if (selectedSection === "system" && answer === "rubaya ferdows".toLowerCase()) {
                window.location.href = "system_analysis.php";
            } else {
                document.getElementById("errorMsg").style.display = "block";
            }
        }
    </script>

</body>

</html>