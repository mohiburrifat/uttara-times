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
    if (isset($_POST['add_popup'])) {
        $content = $_POST['content'];
        $active = isset($_POST['is_active']) ? 1 : 0;
        
        $stmt = $conn->prepare("INSERT INTO popups (content, is_active) VALUES (?, ?)");
        $stmt->bind_param("si", $content, $active);
        $stmt->execute();
    } elseif (isset($_POST['toggle_active'])) {
        $id = $_POST['popup_id'];
        $val = $_POST['val'] == 1 ? 0 : 1;
        $conn->query("UPDATE popups SET is_active = $val WHERE popup_id = $id");
    } elseif (isset($_POST['delete_popup'])) {
        $id = $_POST['popup_id'];
        $conn->query("DELETE FROM popups WHERE popup_id = $id");
    }
}

$popups = $conn->query("SELECT * FROM popups ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Popups | Editor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h3 class="mb-4">Manage Popups</h3>
    
    <div class="card mb-4">
        <div class="card-header">Add New Popup</div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label>Popup Content (HTML allowed)</label>
                    <textarea name="content" class="form-control" rows="3" required placeholder="<h3>Welcome!</h3><p>Subscribe now!</p>"></textarea>
                    <div class="form-text">You can use basic HTML tags like &lt;h1&gt;, &lt;p&gt;, &lt;a&gt;, &lt;img&gt; etc.</div>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" id="activeCheck" checked>
                    <label class="form-check-label" for="activeCheck">Active immediately</label>
                </div>
                <button type="submit" name="add_popup" class="btn btn-primary">Create Popup</button>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">Existing Popups</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="50%">Content Preview</th>
                        <th>Created At</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($p = $popups->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars(mb_strimwidth(strip_tags($p['content']), 0, 100, '...')) ?></td>
                        <td><?= $p['created_at'] ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="popup_id" value="<?= $p['popup_id'] ?>">
                                <input type="hidden" name="val" value="<?= $p['is_active'] ?>">
                                <button type="submit" name="toggle_active" class="btn btn-sm btn-<?= $p['is_active']?'success':'secondary' ?>">
                                    <?= $p['is_active'] ? 'Yes' : 'No' ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <form method="post" onsubmit="return confirm('Delete this popup?');">
                                <input type="hidden" name="popup_id" value="<?= $p['popup_id'] ?>">
                                <button type="submit" name="delete_popup" class="btn btn-sm btn-danger">Delete</button>
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
