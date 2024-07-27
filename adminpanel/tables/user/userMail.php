<?php
include "C:\Users\USER\OneDrive\Bureau\PFEs\connectdb.php";
require 'C:\Users\USER\OneDrive\Bureau\PFEs\PHPMailer\src\PHPMailer.php';
require 'C:\Users\USER\OneDrive\Bureau\PFEs\PHPMailer\src\SMTP.php';
require 'C:\Users\USER\OneDrive\Bureau\PFEs\PHPMailer\src\Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

//Server settings
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'mychoice22000@gmail.com'; // Your SMTP username
$mail->Password = 'exvw ykdv jlul dytv'; // Your SMTP password
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('mychoice22000@gmail.com', 'RESPONSABLE');

if (isset($_POST['SENDINFO'])) {
    $recipientEmail = $_POST['recipientEmail'];
    $emailUser = $_POST['email'];
    $passwordUser = $_POST['password'];
    try {
        // Your email and name
        $mail->addAddress($recipientEmail, 'Destinataire');
        $mail->isHTML(true);
        // Set email format to HTML
        $mail->Subject = 'User information (must change) '; // Email subject
        
        // Start building the HTML body
        $mail->Body = 'Here is your information to get access to Mychoice website: <br> Email: ' . $emailUser . '<br> Password: ' . $passwordUser;

        // Send email
        $mail->send();
        echo 'Email has been sent';
        header('location:/adminpanel/tables/user/userTab.php');
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
