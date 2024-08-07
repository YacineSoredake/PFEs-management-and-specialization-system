<?php
session_start();
include "../connectdb.php";
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer(true);

// Server settings
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'mychoice22000@gmail.com'; // Your SMTP username
$mail->Password = 'exvw ykdv jlul dytv'; // Your SMTP password
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->setFrom('mychoice22000@gmail.com', 'RESPONSABLE');

$message = null;
if (isset($_POST['confirm'])) {
    $email= $_POST['email'];
    $_SESSION['email']=$email;

$emailCheckQuery = "SELECT id FROM user WHERE email = ?";
$emailCheckStmt = mysqli_prepare($db, $emailCheckQuery);
mysqli_stmt_bind_param($emailCheckStmt, "s", $email);
mysqli_stmt_execute($emailCheckStmt);
$emailCheckResult = mysqli_stmt_get_result($emailCheckStmt);

if (mysqli_num_rows($emailCheckResult) > 0) {
    $recipientEmail = $email;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 7; $i++) {
        $randomString .= $characters[rand(0, $charactersLength -1)];
        $_SESSION['codeConfrim']=$randomString;
    }
    try {
        // Your email and name
        $mail->addAddress($recipientEmail, 'Destinataire');
        $mail->isHTML(true);
        $mail->Subject = 'MyChoice Notification'; // Email subject
        $mail->Body = 'Your confirmation code ' . $randomString;

        // Send email
        $mail->send();
        $mail->clearAddresses(); // Clear all recipients for the next iteration
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    header("location: ./confirmEmail.php");
} else {
    $message = 'This email exist does not exist';
}}
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="/login/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <form action="" method="post">
            <label style="color: red;" for=""><?php echo $message; ?></label>
            <label for="">Please enter your email so we can confirm your identity</label>
            <img src="/PFEs/figma/Line 1.png" alt="">
            <input class="inpt-login" placeholder="enter ypur email" type="text" name="email" required>
            <input class="login-btn" type="submit" name="confirm" value="Confirm"> 
        </form>
    </div>

</body>

</html>
