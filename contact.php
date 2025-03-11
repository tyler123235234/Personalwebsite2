<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $rating = intval($_POST["rating"]);
    $comments = htmlspecialchars(trim($_POST["comments"]));

    if (!$email) {
        echo "Invalid email format.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com'; // Replace with your Gmail address
        $mail->Password   = 'your-app-password'; // Use an App Password (not your Gmail password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('benbryant20070@gmail.com'); // Your recipient email

        // Content
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
    echo "Invalid request.";
}
?>