<?php
session_start();
include "C:/Users/USER/OneDrive/Bureau/PFEs/connectdb.php";

$message=null;
$code = $_SESSION['codeConfrim'];
$email = $_SESSION['email'];

if (isset($_POST['confirm'])) {
    $eneteredCode = $_POST['codeConf'];
    if ($eneteredCode == $code) {
        $emailCheckQuery = "SELECT id FROM user WHERE email = ?";
        $emailCheckStmt = mysqli_prepare($db, $emailCheckQuery);
        mysqli_stmt_bind_param($emailCheckStmt, "s", $email);
        mysqli_stmt_execute($emailCheckStmt);
        $emailCheckResult = mysqli_stmt_get_result($emailCheckStmt);
        $row = mysqli_fetch_assoc($emailCheckResult);
        $id = $row['id'];
        header("location: ./enterNewPass.php?id=$id");
    }
    else {
        $message='Wrong code';
    }
}
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
            <label for="">Enter the code sent in the email : <?php echo $email; ?></label>
            <img src="/PFEs/figma/Line 1.png" alt="">
            <input class="inpt-login" placeholder="enter the code" type="text" name="codeConf" required>
            <input class="login-btn" type="submit" name="confirm" value="Confirm"> 
        </form>
    </div>

</body>

</html>