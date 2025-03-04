<?php
// Include PHPMailer classes using relative path
require __DIR__ . '/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/phpmailer/src/SMTP.php';
require 'C:\Users\zidi\Desktop\personal_website\vendor\autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// استدعاء المتغيرات
$mailHost = $_ENV['MAIL_HOST'];
$mailUser = $_ENV['MAIL_USERNAME'];
$mailPass = $_ENV['MAIL_PASSWORD'];
$mailPort = $_ENV['MAIL_PORT'];
$receiving_email_address = $_ENV['MAIL_TARGET'];



// Replace 'samizidi98@gmail.com' with your actual receiving email address


// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data from POST request
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Validate the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    try {
        // Server settings
        $mail->isSMTP();                                           // Set mailer to use SMTP
        $mail->Host = $mailHost;                              // Set the SMTP server to Gmail
        $mail->SMTPAuth = true;                                      // Enable SMTP authentication
        $mail->Username = $mailUser;               // Your Gmail address
        $mail->Password = $mailPass;                     // Your Gmail app password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = $mailPort;                                           // TCP port to connect to for Gmail

        // Recipients
        $mail->setFrom($email, $name);                               // Sender's email and name
        $mail->addAddress($receiving_email_address);                 // Add the recipient

        // Content
        $mail->isHTML(false);                                        // Set email format to plain text
        $mail->Subject = "New message from: " . $name;
        $mail->Body    = "----------------------------Message from My Personal webSite----------------------------\n".
                         "Name: " . $name . "\n" .
                         "Email: " . $email . "\n" .
                         "Subject: " . $subject . "\n\n" .
                         "Message: \n" . $message;

        // Attempt to send the email
        if ($mail->send()) {
            // Only show success message if email is sent successfully
            echo "OK";
        } else {
            // Display the error message from PHPMailer if the email fails to send
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } catch (Exception $e) {
        // If an exception occurs, show the error message
        echo "Message could not be sent. Error: {$e->getMessage()}";
    }
}
?>
