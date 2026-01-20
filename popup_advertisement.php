<?php
// popup_advertisement.php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    addPopupAdvertisement($title, $content);
}

// Fetch existing pop-up advertisements
$query = "SELECT * FROM popup_advertisements";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pop-up Advertisements</title>
</head>

<body>
    <h1>Manage Pop-up Advertisements</h1>
    <form method="POST">
        <input type="text" name="title" placeholder="Advertisement Title" required>
        <textarea name="content" placeholder="Advertisement Content" required></textarea>
        <button type="submit">Add Advertisement</button>
    </form>
    <table>
        <tr>
            <th>Title</th>
            <th>Content</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['content']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>