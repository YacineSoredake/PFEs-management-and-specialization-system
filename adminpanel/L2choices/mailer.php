<?php
include "../connectdb.php";
require '../PHPMailer\src\PHPMailer.php';
require '../PHPMailer\src\SMTP.php';
require '../PHPMailer\src\Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// query
$query = "SELECT * FROM user WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$query_3L = "SELECT *, e.nompre ,e.dateNaissance
            FROM choix c
            JOIN etudient e ON c.id_etudient = e.id";

$stm_3L = mysqli_prepare($db, $query_3L);
mysqli_stmt_execute($stm_3L);
$resultat_3L = mysqli_stmt_get_result($stm_3L);
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

if (isset($_POST['sendWithPHPMailer'])) {
    $recipientEmail = $_POST['recipientEmail'];
    try {
        // Your email and name
        $mail->addAddress($recipientEmail, 'Destinataire');
        $mail->isHTML(true);
        // Set email format to HTML
        $mail->Subject = 'L2 Students List'; // Email subject
        
        // Start building the HTML body
        $mail->Body = '
            <h2>Second year students choice</h2>
            <table>
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>Student</th>
                        <th>Choice</th>
                    </tr>
                </thead>
                <tbody>';
        
        // Iterate over the result set for level 3L students
        $rowNumber = 1;
        while ($row = mysqli_fetch_assoc($resultat_3L)) {
            $mail->Body .= "<tr>";
            $mail->Body .= "<td>" . $rowNumber++ . "</td>";
            $mail->Body .= "<td>" . $row['nompre'] . "</td>";
            $mail->Body .= "<td>" . $row['lechoix'] . "</td>";
            $mail->Body .= "</tr>";
        }
        
        // Finish building the HTML body
        $mail->Body .= '
                </tbody>
            </table>';

        // Send email
        $mail->send();
        echo 'Email has been sent';
        header('location:/adminpanel/L2choices/LtwoListe.php');
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
