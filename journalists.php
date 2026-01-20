<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header("Location: login.php");
    exit();
}
require 'db.php';
require 'menubar3.php';

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_journalist'])) {
    $delete_id = intval($_POST['delete_id']);
    if ($delete_id !== $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ? AND role = 'journalist'");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Journalists | Uttara-Times</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 30px;
            color: #343a40;
        }

        h3 {
            margin-bottom: 20px;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f9ff;
            cursor: pointer;
        }

        .table th {
            background-color: #e9f1fb;
        }

        .btn-danger.btn-sm i {
            margin-right: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3 class="text-center">All Journalists</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->prepare("SELECT user_id, username, email, created_at FROM users WHERE role = 'journalist' ORDER BY created_at DESC");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this journalist?');" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?= $row['user_id'] ?>">
                                    <button type="submit" name="delete_journalist" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>