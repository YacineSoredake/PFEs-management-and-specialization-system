<?php 

include "C:\Users\USER\OneDrive\Bureau\PFEs\connectdb.php";
require 'C:\Users\USER\OneDrive\Bureau\PFEs\PHPMailer\src\PHPMailer.php';
require 'C:\Users\USER\OneDrive\Bureau\PFEs\PHPMailer\src\SMTP.php';
require 'C:\Users\USER\OneDrive\Bureau\PFEs\PHPMailer\src\Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'mychoice22000@gmail.com'; // Your SMTP username
$mail->Password = 'exvw ykdv jlul dytv'; // Your SMTP password
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('mychoice22000@gmail.com', 'RESPONSABLE');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $intitule = isset($_POST['intitule']) ? $_POST['intitule'] : "";
    $resume = isset($_POST['resume']) ? $_POST['resume'] : "";
    $student = isset($_POST['student']) ? $_POST['student'] : "";
    $prof = isset($_POST['prof']) ? $_POST['prof'] : "";
    $partner_id = isset($_POST['id_pair']) ? $_POST['id_pair'] : null;

    // getting prof_id and recipientEmail
    $prof_query = "SELECT p.id, p.id_user, u.email FROM prof p JOIN user u ON p.id_user = u.id  WHERE p.nomprenom = ?";
    $stmt = mysqli_prepare($db, $prof_query);
    mysqli_stmt_bind_param($stmt, "s", $prof);
    mysqli_stmt_execute($stmt);
    $prof_result = mysqli_stmt_get_result($stmt);

    if ($prof_row = mysqli_fetch_assoc($prof_result)) {
        $prof_id = $prof_row['id'];
        $recipientEmail = $prof_row['email'];
    } else {
        echo "Professor not found: $prof";
        exit;
    }

    // getting student_id
    $student_query = "SELECT id FROM etudient WHERE nompre = '$student'";
    $student_result = mysqli_query($db, $student_query);

    if ($student_row = mysqli_fetch_assoc($student_result)) {
        $etudient_id = $student_row['id'];
    } else {
        echo "Student ID not found for student: $student";
        exit;
    }

    try {
        // Your email and name
        $mail->addAddress($recipientEmail, 'Destinataire');
        $mail->isHTML(true);
        // Set email format to HTML
        $mail->Subject = 'New request received'; 
        
        // Start building the HTML body
        $mail->Body = "You have a new request from $student";

        // Send email
        $mail->send();

    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }

    $req = "INSERT INTO demandesupervision(intitule, resume,id_prof,id_etudient,id_pair) VALUES('$intitule', '$resume','$prof_id','$etudient_id',?)";
    $stmt = mysqli_prepare($db, $req);
    mysqli_stmt_bind_param($stmt, "i", $partner_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        header("Location: /PFEs/done/demandeDone.php");
        exit;
    } else {
        echo "Error inserting demande.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db); 
}
?>
