<?php
$host = "localhost";
$dbname = "reviews_db";
$username = "your_db_username";
$password = "your_db_password";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM reviews WHERE id = $id");
    $review = $result->fetch_assoc();

    if (!$review) {
        echo "Review not found!";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $rating = intval($_POST['rating']);
    $comments = htmlspecialchars($_POST['comments']);

    $stmt = $conn->prepare("UPDATE reviews SET name=?, email=?, rating=?, comments=? WHERE id=?");
    $stmt->bind_param("ssisi", $name, $email, $rating, $comments, $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating review.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review</title>
</head>
<body>

    <h2>Edit Review</h2>
    <form action="edit_review.php" method="POST">
        <input type="hidden" name="id" value="<?= $review['id'] ?>">
        
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($review['name']) ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($review['email']) ?>" required><br>

        <label>Rating:</label>
        <select name="rating" required>
            <option value="1" <?= ($review['rating'] == 1) ? 'selected' : '' ?>>1</option>
            <option value="2" <?= ($review['rating'] == 2) ? 'selected' : '' ?>>2</option>
            <option value="3" <?= ($review['rating'] == 3) ? 'selected' : '' ?>>3</option>
            <option value="4" <?= ($review['rating'] == 4) ? 'selected' : '' ?>>4</option>
            <option value="5" <?= ($review['rating'] == 5) ? 'selected' : '' ?>>5</option>
        </select><br>

        <label>Comments:</label>
        <textarea name="comments" required><?= htmlspecialchars($review['comments']) ?></textarea><br>

        <button type="submit">Update</button>
    </form>

</body>
</html>