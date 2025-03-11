<?php
$host = "localhost";
$dbname = "reviews_db";
$username = "your_db_username";
$password = "your_db_password";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM reviews WHERE id = $id");
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch reviews
$result = $conn->query("SELECT * FROM reviews ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background: #f4f4f4; }
        a { text-decoration: none; padding: 5px 10px; color: white; background: red; border-radius: 5px; }
    </style>
</head>
<body>

    <h2>Admin Dashboard - Reviews</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Rating</th>
            <th>Comments</th>
            <th>Submitted At</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['rating'] ?> Stars</td>
                <td><?= htmlspecialchars($row['comments']) ?></td>
                <td><?= $row['submitted_at'] ?></td>
                <td>
                    <a href="edit_review.php?id=<?= $row['id'] ?>" style="background:blue;">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this review?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>

<?php $conn->close(); ?>