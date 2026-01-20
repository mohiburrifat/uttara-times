<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header("Location: login.php");
    exit();
}
require 'db.php';
require 'menubar3.php';

// Handle Actions (Add, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_plan'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $duration = $_POST['duration'];
        $desc = $_POST['description'];

        $stmt = $conn->prepare("INSERT INTO subscription_plans (name, price, duration_days, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdis", $name, $price, $duration, $desc);
        $stmt->execute();
    } elseif (isset($_POST['delete_plan'])) {
        $id = $_POST['plan_id'];
        $conn->query("DELETE FROM subscription_plans WHERE plan_id = $id");
    }
}

$plans = $conn->query("SELECT * FROM subscription_plans ORDER BY price ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Plans | Editor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-4">
        <h3 class="mb-4">Manage Subscription Plans</h3>

        <div class="card mb-4">
            <div class="card-header">Add New Plan</div>
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label>Plan Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label>Price</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label>Duration (Days)</label>
                            <input type="number" name="duration" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label>Description</label>
                            <textarea name="description" class="form-control" required></textarea>
                        </div>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label>Duration (Days)</label>
                        <input type="number" name="duration" class="form-control" required>
                    </div>
                    <div class="col-md-5 mb-2">
                        <label>Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
            </div>
            <button type="submit" name="add_plan" class="btn btn-primary mt-2">Create Plan</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Existing Plans</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Description</th>
                        <th>Users</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($p = $plans->fetch_assoc()):
                        $pid = $p['plan_id'];
                        $count = $conn->query("SELECT COUNT(*) FROM user_subscriptions WHERE plan_id = $pid")->fetch_row()[0];
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td>$<?= $p['price'] ?></td>
                            <td><?= $p['duration_days'] ?> Days</td>
                            <td><?= htmlspecialchars($p['description']) ?></td>
                            <td><?= $count ?></td>
                            <td>
                                <form method="post" onsubmit="return confirm('Delete this plan?');">
                                    <input type="hidden" name="plan_id" value="<?= $pid ?>">
                                    <button type="submit" name="delete_plan" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</body>

</html>