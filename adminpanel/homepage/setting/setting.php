<?php
session_start();
include "../connectdb.php";
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the user is logged in
if (!isset($_SESSION['usid'])) {
    header("Location: /login/login.php");
    exit();
}

// Check if the user has admin privileges
$user_id = $_SESSION['usid'];

$query = "SELECT * FROM admins WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) === 0) {
    // User is not an admin, redirect to the login page
    header("Location: /login/login.php");
    exit();
}

// Create a new PHPMailer instance
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

$query = "SELECT email, niveau,choix FROM user JOIN etudient ON user.id = etudient.id_user";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

if (isset($_POST['sendNotifdelai'])) {
    $datePFEs = $_POST['delaipfes'];
    $dateSpeciality = $_POST['delaiSpeciality'];

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['niveau'] === '3L' || $row['niveau'] === '2M') {
            $recipientEmail = $row['email'];

            try {
                // Your email and name
                $mail->addAddress($recipientEmail, 'Destinataire');
                $mail->isHTML(true);
                $mail->Subject = 'MyChoice Notification'; // Email subject
                $mail->Body = 'Final selection deadline for PFEs is on ' . $datePFEs;

                // Send email
                $mail->send();
                $mail->clearAddresses(); // Clear all recipients for the next iteration
            } catch (Exception $e) {
                echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }

        if ($row['niveau'] === '2L') {
            $recipientEmail = $row['email'];

            try {
                // Your email and name
                $mail->addAddress($recipientEmail, 'Destinataire');
                $mail->isHTML(true);
                $mail->Subject = 'MyChoice Notification'; // Email subject
                $mail->Body = '<a href="/login/login.php">Visit website</a> Final deadline for selecting your preferred specialty (ISIL/SI) is on ' . $dateSpeciality;

                // Send email
                $mail->send();
                $mail->clearAddresses(); // Clear all recipients for the next iteration
            } catch (Exception $e) {
                echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}

if (isset($_POST['sendPFEsResult'])) {
  while ($row = mysqli_fetch_assoc($result)) {
   if ($row['niveau']==='3L' || $row['niveau'] === '2M') {
    $recipientEmail = $row['email'];
    try {
        // Your email and name
        $mail->addAddress($recipientEmail, 'Destinataire');
        $mail->isHTML(true);
        $mail->Subject = 'MyChoice Notification'; // Email subject
        $mail->Body = 'PFEs theme assignment is complete. You check it now in MyChoice platform <br> MyChoice website : <a href="http://localhost:3000/login/login.php">Visit website</a>';
        // Send email
        $mail->send();
        $mail->clearAddresses(); // Clear all recipients for the next iteration
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
   }
  }
}

if (isset($_POST['sendSpecResult'])) {
    while ($row = mysqli_fetch_assoc($result)) {
     if ($row['niveau']==='2L') {
      $choice = $row['choix'];
      $recipientEmail = $row['email'];
      try {
          // Your email and name
          $mail->addAddress($recipientEmail, 'Destinataire');
          $mail->isHTML(true);
          $mail->Subject = 'MyChoice Notification'; // Email subject
          $mail->Body = 'Specialties assignment is complete. Your specialty for the 3rd license is ' . $row['choix'];
  
          // Send email
          $mail->send();
          $mail->clearAddresses(); // Clear all recipients for the next iteration
      } catch (Exception $e) {
          echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
     }
    }
  }

if (isset($_POST['updateSession'])) {
    if (isset($_POST['action']) && isset($_POST['levels'])) {
        $action = $_POST['action'];
        $levels = $_POST['levels'];
        $state = ($action === 'enable') ? 'active' : 'inactive';

        foreach ($levels as $level) {
            $query = "
                UPDATE user 
                SET state = ? 
                WHERE id IN (
                    SELECT id_user 
                    FROM etudient
                    WHERE niveau = ?
                )";
            $stmt = mysqli_prepare($db, $query);
            mysqli_stmt_bind_param($stmt, "ss", $state, $level);

            if (!mysqli_stmt_execute($stmt)) {
                echo "Failed to update users for level $level";
                exit();
            }
            mysqli_stmt_close($stmt);
        }
        echo "Users updated successfully";
    } else {
        echo "Invalid parameters";
    }
} 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Aldrich&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/adminpanel/homepage/admin.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Admin Panel</title>
</head>

<body>
    <div class="body-container">
        <div class="menubar">
            <div>
                <img src="/PFEs/figma/MyChoice (1).svg" alt="">
            </div>
            <div>
                <nav>
                    <a href="/adminpanel/homepage/admin.php">
                        <img style="height: 15px;" src="/PFEs/figma//homme.svg" alt="">
                        Dashboard
                    </a>
                    <a href="/adminpanel/listeFinish/ranking.php">
                        <img style="height: 15px;" src="/PFEs/figma/rankuung.svg" alt="">
                        Rankings
                    </a>
                    <a href="/adminpanel/listeFinish/liste.php">
                        <img style="height: 15px;" src="/PFEs/figma/assss.svg" alt="">
                        Assign PFEs
                    </a>
                    <a href="/adminpanel/L2choices/LtwoListe.php">
                        <img style="height: 15px;" src="/PFEs/figma/spec.svg" alt="">
                        Assign Specialties
                    </a>
                    <a href="/adminpanel/tables/user/userTab.php">
                        <img style="height: 15px;" src="/PFEs/figma/zazaz.svg" alt="">
                        Users
                    </a>
                    <a href="/adminpanel/homepage/setting/setting.php" style="color: #fdfdfd;">
                        <img style="height: 15px;" src="/PFEs/figma/seting.svg" alt="">
                        Settings
                    </a>
                    <a style="border-top: 1px solid white; justify-self:flex-end; " href="/logouttoAdmin.php">
                    <img style="height: 15px;" src="/PFEs/figma/logg.svg" alt="">
                        Logout
                    </a>
                </nav>
            </div>
        </div>
        <div class="container cnt">
            <header>
                <div class="profile">
                    Admin
                </div>
            </header>
            <h3><img height="30px" src="/PFEs/figma/seting.svg" alt="">
                Sessions settings</h3>
            <div class="option">
                <form action="" method="post">
                    <div class="from-dv">
                    <label for="action">Action</label>
                    <select class="style-inpt" name="action" id="action">
                        <option value="enable">Enable</option>
                        <option value="disable">Disable</option>
                    </select>
                    </div>
                    <div class="from-dv">
                    <label for="levels">Select Levels</label>
                    <select class="style-inpt" name="levels[]" id="levels" multiple>
                        <option value="2L">2L</option>
                        <option value="3L">3L</option>
                        <option value="2M">2M</option>
                    </select>
                    </div>

                    <input class="style-inpt btn" type="submit" name="updateSession" value="Update">
                </form>
            </div>
            <h3>
                <img height="30px" src="/PFEs/figma/noti.svg" alt="">
                Notify selection deadlines
            </h3>
            <div class="option">
                
                <form action="" method="post">
                    <label for="delaipfes">PFEs subject </label>
                    <input class="style-inpt" type="date" name="delaipfes" id="delaipfes">
                    <label for="delaiSpeciality">Specialty (ISIL/SI) </label>
                    <input class="style-inpt" type="date" name="delaiSpeciality" id="delaiSpeciality">
                    <input class="style-inpt btn" type="submit" name="sendNotifdelai" value="Inform">
                </form>
            </div>
            <h3>
            <img height="30px" src="/PFEs/figma/noti.svg" alt="">
                Notify results
            </h3>
            <div class="option">
                <form action="" method="post">
                    <label for="delaipfes">PFEs themes assigned </label>
                    <input class="style-inpt btn" type="submit" name="sendPFEsResult" value="Notify">
                </form>
                <form action="" method="post">
                    <label for="delaipfes">Send Specialties result</label>
                    <input class="style-inpt btn" type="submit" name="sendSpecResult" value="Send">
                </form>
            </div>
        </div>
    </div>
</body>
<script>
   
</script>

</html>
