<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is included

// Database connection
$host = "localhost"; // Change if your database is hosted elsewhere
$dbname = "reviews_db";
$username = "your_db_username";
$password = "your_db_password";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $rating = intval($_POST["rating"]);
    $comments = htmlspecialchars(trim($_POST["comments"]));

    if (!$email) {
        echo "Invalid email format.";
        exit;
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO reviews (name, email, rating, comments) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $email, $rating, $comments);

    if ($stmt->execute()) {
        $stmt->close();

        // Send email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your-email@gmail.com'; // Replace with your Gmail
            $mail->Password   = 'your-app-password'; // Use an App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom($email, $name);
            $mail->addAddress('benbryant20070@gmail.com');

            $mail->isHTML(false);
            $mail->Subject = "New Review Submission";
            $mail->Body    = "You have received a new review:\n\n"
                           . "Name: $name\n"
                           . "Email: $email\n"
                           . "Rating: $rating Stars\n"
                           . "Comments:\n$comments\n";

            $mail->send();
            echo "success";
        } catch (Exception $e) {
            echo "Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: Could not save to database.";
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>