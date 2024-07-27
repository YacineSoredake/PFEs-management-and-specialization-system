<?php
session_start();

include "C:/Users/USER/OneDrive/Bureau/PFEs/connectdb.php";
$message = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']); // Use mysqli_real_escape_string to prevent SQL injection
    $password = mysqli_real_escape_string($db, $_POST['password']);


    $stmt = mysqli_prepare($db, "SELECT id, role, state FROM user WHERE email = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Check if there is a matching user
    if ($row = mysqli_fetch_assoc($result)) {
        $userId = $row['id'];
        $role = $row['role'];
        $state = $row['state'];

        // Check if the user account is inactive
        if ($state == "inactive") {
            $message = "Your session is close.";
        } else {
            // Check if the user ID exists in the "prof" table
            $profQuery = "SELECT * FROM prof WHERE id_user = ?";
            $stmtProf = mysqli_prepare($db, $profQuery);
            mysqli_stmt_bind_param($stmtProf, "i", $userId);
            mysqli_stmt_execute($stmtProf);
            $resultProf = mysqli_stmt_get_result($stmtProf);
            $profRow = mysqli_fetch_assoc($resultProf);

            // Check if the user ID exists in the "etudient" table
            $etudientQuery = "SELECT * FROM etudient WHERE id_user = ?";
            $stmtEtudient = mysqli_prepare($db, $etudientQuery);
            mysqli_stmt_bind_param($stmtEtudient, "i", $userId);
            mysqli_stmt_execute($stmtEtudient);
            $resultEtudient = mysqli_stmt_get_result($stmtEtudient);
            $etudientRow = mysqli_fetch_assoc($resultEtudient);

            if (mysqli_num_rows($resultProf) > 0) {
                // User is a professor
                $_SESSION['user_id'] = $profRow['id'];
                header("location: /Profview/homepage/homepage.php");
                exit;
            } elseif (mysqli_num_rows($resultEtudient) > 0) {
                // User is a student
                $level = $etudientRow['niveau'];

                $_SESSION['user_id'] = $etudientRow['id'];
                $_SESSION['user_niveau'] = $etudientRow['niveau'];
                $_SESSION['user_nompre'] = $etudientRow['nompre'];
                $_SESSION['user_specialite'] = $etudientRow['specialite'];
                $_SESSION['user_DOB'] = $etudientRow['datenaissance'];
                $_SESSION['user_image'] = $etudientRow['image'];
                $_SESSION['iduser_of_etudient'] = $etudientRow['id_user'];
                $_SESSION['partner'] = $etudientRow['id_partner'];
                $_SESSION['creation'] = $etudientRow['creation'];

                if ($level == "2L") {
                    header("location: /choix/homepage/home.php");
                } elseif ($level == "3L" || $level == "2M") {
                    header("location: /PFEs/homepage/homepage.php");
                }
                exit;
            } else {
                // No role assigned
                if ($role == "teacher") {
                    header("location: /register/register-teacher.php?id=$userId");
                    exit;
                } elseif ($role == "student") {
                    header("location: /register/register-student.php?id=$userId");
                    exit;
                }
            }
        }
    } else {
        $message = "Invalid email or password.";
    }

    // Close the statements
    mysqli_stmt_close($stmt);
    if (isset($stmtProf)) {
        mysqli_stmt_close($stmtProf);
    }
    if (isset($stmtEtudient)) {
        mysqli_stmt_close($stmtEtudient);
    }
    if (isset($stmtAdmin)) {
        mysqli_stmt_close($stmtAdmin);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    
     <p id="error-message" class="error-message"><?php echo $message; ?></p>

    <div class="container">
        <div class="image-Container">
        <img class="chap"  src="/PFEs/figma/toga 1.svg" alt="">
        <img class="mychoice" src="/PFEs/figma/MyChoice.svg" alt="">
        </div>

        <form action="login.php" method="post">
            <input class="inpt-login" placeholder="e-mail adress" type="text" name="email" required>
            <input class="inpt-login" placeholder="Password" type="password" name="password" required>
            <input class="login-btn" type="submit" name="login" value="Log in">
            <a href="/forgetPassword/EnterEmail.php">forget password ?</a>
            <img src="/PFEs/figma/Line 1.png" alt="">
            <input class="regsiter-btn" type="submit" value="MyChoice"> 
        </form>
    </div>
    <script>
    // Hide the error message after 5 seconds
    setTimeout(function() {
        document.getElementById('error-message').style.display = 'none';
    }, 1500);


    </script>
</body>

</html>
