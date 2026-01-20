<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Analysis & Design | Uttara-Times</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .member-photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #003049;
            margin-bottom: 10px;
        }

        .leader-photo {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #d62828;
            margin-bottom: 10px;
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

    <div class="container py-5">
        <a href="contact.php" class="btn btn-outline-secondary mb-3">&larr; Back</a>
        <h2 class="mb-4 text-center">System Analysis & Design</h2>

        <div class="card p-4 mx-auto text-center" style="max-width:800px;">
            <h4 class="mb-4">Our Team: <span class="text-primary">Axion</span></h4>

            <!-- Leader -->
            <div class="mb-5">
                <img src="Photos/System/1.jpg" alt="Leader Photo" class="leader-photo">
                <h5 class="mt-2">Mohibur Rahman Rifat</h5>
                <p class="text-muted">Team Leader<br>ID #22303130</p>
            </div>

            <!-- Other Members in Grid -->
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col">
                    <img src="Photos/System/2.jpg" alt="Member Photo" class="member-photo">
                    <h6>Fairrose Chahara Tohfa</h6>
                    <p class="text-muted">ID #22303107</p>
                </div>
                <div class="col">
                    <img src="Photos/System/3.jpg" alt="Member Photo" class="member-photo">
                    <h6>Sumaiya Sultana Tesha</h6>
                    <p class="text-muted">ID #22303220</p>
                </div>
                <div class="col">
                    <img src="Photos/System/4.jpg" alt="Member Photo" class="member-photo">
                    <h6>Mst. Sanzida</h6>
                    <p class="text-muted">ID #22303252</p>
                </div>
                <div class="col">
                    <img src="Photos/System/5.jpg" alt="Member Photo" class="member-photo">
                    <h6>Jannatul Ferdaus Merina</h6>
                    <p class="text-muted">ID #22303083</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-light text-center py-3 mt-5">
        Developed by MRR WebDev Solutions
    </footer>
</body>

</html>