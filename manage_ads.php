<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    header("Location: login.php");
    exit();
}
require 'db.php';
require 'menubar3.php';

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_ad'])) {
        $link = $_POST['link_url'];
        $pos = $_POST['position'];

        // Image Upload
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $filename = uniqid('ad_') . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO advertisements (image_url, link_url, position) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $target_file, $link, $pos);
            $stmt->execute();
        }
    } elseif (isset($_POST['toggle_status'])) {
        $id = $_POST['ad_id'];
        $status = $_POST['status'] === 'active' ? 'inactive' : 'active';
        $conn->query("UPDATE advertisements SET status = '$status' WHERE ad_id = $id");
    } elseif (isset($_POST['delete_ad'])) {
        $id = $_POST['ad_id'];
        $conn->query("DELETE FROM advertisements WHERE ad_id = $id");
    }
}

$ads = $conn->query("SELECT * FROM advertisements ORDER BY start_date DESC"); // Assuming created_at or ad_id
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Ads | Editor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-4">
        <h3 class="mb-4">Manage Advertisements</h3>

        <div class="card mb-4">
            <div class="card-header">Add New Advertisement</div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label>Advertisement Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label>Link URL</label>
                            <input type="url" name="link_url" class="form-control">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label>Position</label>
                            <select name="position" class="form-control" required>
                                <option value="home_top">Homepage Top</option>
                                <option value="home_middle">Homepage Middle</option>
                                <option value="home_bottom">Homepage Bottom</option>
                                <option value="sidebar">Sidebar</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label>Advertisement Content</label>
                        <textarea name="content" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label>Upload Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" name="add_ad" class="btn btn-primary">Add Advertisement</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-2">
                <label>Image</label>
                <input type="file" name="image" class="form-control" required accept="image/*">
            </div>
            <div class="col-md-4 mb-2">
                <label>Link URL</label>
                <input type="url" name="link_url" class="form-control" placeholder="http://...">
            </div>
            <div class="col-md-4 mb-2">
                <label>Position</label>
                <select name="position" class="form-control">
                    <option value="home_top">Home Top Banner</option>
                    <option value="home_sidebar">Home Sidebar</option>
                    <option value="article_bottom">Article Bottom</option>
                </select>
            </div>
        </div>
        <button type="submit" name="add_ad" class="btn btn-primary">Add Advertisement</button>
        </form>
    </div>
    </div>

    <div class="card">
        <div class="card-header">Existing Advertisements</div>
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($ad = $ads->fetch_assoc()): ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($ad['image_url']) ?>" style="height:50px;"></td>
                            <td><a href="<?= htmlspecialchars($ad['link_url']) ?>" target="_blank">Link</a></td>
                            <td><?= htmlspecialchars($ad['position']) ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="ad_id" value="<?= $ad['ad_id'] ?>">
                                    <input type="hidden" name="status" value="<?= $ad['status'] ?>">
                                    <button type="submit" name="toggle_status" class="btn btn-sm btn-<?= $ad['status'] == 'active' ? 'success' : 'secondary' ?>">
                                        <?= $ad['status'] ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form method="post" onsubmit="return confirm('Delete this ad?');">
                                    <input type="hidden" name="ad_id" value="<?= $ad['ad_id'] ?>">
                                    <button type="submit" name="delete_ad" class="btn btn-sm btn-danger">Delete</button>
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