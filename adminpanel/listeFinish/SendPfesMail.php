<?php
include "C:\Users\USER\OneDrive\Bureau\PFEs\connectdb.php";
require 'C:\Users\USER\OneDrive\Bureau\PFEs\PHPMailer\src\PHPMailer.php';
require 'C:\Users\USER\OneDrive\Bureau\PFEs\PHPMailer\src\SMTP.php';
require 'C:\Users\USER\OneDrive\Bureau\PFEs\PHPMailer\src\Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// query
$query = "SELECT *, t.intitule, t.summary, t.keyword, p.nomprenom
          FROM demande d 
          JOIN theme t ON d.id_theme = t.id
          JOIN prof p ON d.id_prof = p.id
          JOIN etudient e ON d.id_etudient = e.id
          WHERE e.niveau = '3L'";
$resultat = mysqli_query($db, $query);

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

if (isset($_POST['SEND3L'])) {
    $recipientEmail = $_POST['recipientEmail'];
    try {
        // Your email and name
        $mail->addAddress($recipientEmail, 'Destinataire');
        $mail->isHTML(true);
        // Set email format to HTML
        $mail->Subject = 'Affectation 3 licence'; // Email subject
        
        // Start building the HTML body
        $mail->Body = '
            <h2>List of End of Study Projects 3L</h2>
            <table>
                <thead>
                <tr>
                    <th>numero</th>
                    <th>ID Theme</th>
                    <th>Title</th>
                    <th>ID student</th>
                    <th>Student</th>
                    <th>Ranking</th>
                    <th>Affectation state</th>
                </tr>
                </thead>
                <tbody>';
        
        // Iterate over the result set for level 3L students
        $rowNumber = 1;
        while ($row = mysqli_fetch_assoc($resultat)) {
            $mail->Body .= "<tr>";
            $mail->Body .= "<td>" . $rowNumber++ . "</td>";
            $mail->Body .= "<td>" .  $row['id_theme'] . "</td>";
            $mail->Body .= "<td>" . $row['intitule'] . "</td>";
            $mail->Body .= "<td>" .  $row['id_etudient'] . "</td>";
            $mail->Body .= "<td>" . $row['nompre'] . "</td>";
            $mail->Body .= "<td>" .  $row['ranking'] . "</td>";
            $mail->Body .= "<td>" .  $row['etat'] . "</td>";
            $mail->Body .= "</tr>";
        }
        
        // Finish building the HTML body
        $mail->Body .= '
                </tbody>
            </table>';

        // Send email
        $mail->send();
        echo 'Email has been sent';
        header('location:/adminpanel/listeFinish/liste.php');
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
