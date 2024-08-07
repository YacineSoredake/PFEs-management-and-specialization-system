<?php
session_start();
include "../connectdb.php";

$message = null;
if (isset($_POST['confirm'])) {
    $id = $_GET['id'];
    $newPass = $_POST['newpas'];
    $query = "UPDATE user SET password = ? WHERE id = ?";
    $stmt = mysqli_prepare($db, $query);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "si",$newPass, $id);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // Update successful
        $_SESSION = array();
        session_destroy();
        header("Location: /login/login.php?mes=$message");
        exit();
    } else {
        echo "Failed to update user details.";
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
            <label for="">New password :</label>
            <input class="inpt-login" placeholder="enter ypur email" type="pass" name="newpas" required>
            <input class="login-btn" type="submit" name="confirm" value="Confirm"> 
        </form>
    </div>

</body>

</html>
