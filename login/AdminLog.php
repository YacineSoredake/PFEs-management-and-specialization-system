<?php
session_start();

include "C:/Users/USER/OneDrive/Bureau/PFEs/connectdb.php";
$message = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']); // Use mysqli_real_escape_string to prevent SQL injection
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Use prepared statement to prevent SQL injection
    $adminQuery = "SELECT id FROM admins WHERE email = ? AND password = ?";
    $stmtAdmin = mysqli_prepare($db, $adminQuery);
    mysqli_stmt_bind_param($stmtAdmin, "ss", $email, $password);
    mysqli_stmt_execute($stmtAdmin);
    $resultAdmin = mysqli_stmt_get_result($stmtAdmin);
    $rowAdmin = mysqli_fetch_assoc($resultAdmin);

    if (mysqli_num_rows($resultAdmin) > 0) {
 
        $_SESSION['usid'] = $rowAdmin['id'];
        header("location: /adminpanel/homepage/admin.php");
        exit;
    } else {
        $message = "Invalid email or password.";
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
        <h5 class="belowTitle">
            FOR ADMINISTRATION
        </h5>
        <img class="mychoice" src="/PFEs/figma/MyChoice.svg" alt="">
        </div>

        <form action="" method="post">
            <input class="inpt-login" placeholder="e-mail adress" type="text" name="email" required>
            <input class="inpt-login" placeholder="Password" type="password" name="password" required>
            <input class="login-btn" type="submit" name="login" value="Log in">
            <img src="/PFEs/figma/Line 1.png" alt="">
            <input class="regsiter-btn" type="submit" value="MyAdmin"> 
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
