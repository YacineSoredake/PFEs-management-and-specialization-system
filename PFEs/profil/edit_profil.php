<?php
session_start();

// Check if the user has admin privileges
include "C:\Users\USER\OneDrive\Bureau\PFEs\connectdb.php";

$id = $_GET['id'];

$requete = "SELECT * FROM user WHERE id = ?";
$stmt = $db->prepare($requete);
$stmt->bind_param('i', $id);
$stmt->execute();
$resultat = $stmt->get_result();

// display the result in table format
if ($resultat->num_rows > 0) {
    $row = $resultat->fetch_assoc();
}
$message='';
// update query
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'email' and 'password' parameters are set in the POST request
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input data
        $newEmail = mysqli_real_escape_string($db, $_POST['email']);
        $newPassword = mysqli_real_escape_string($db, $_POST['password']);

        // Check if the email already exists in the database, excluding the current user
        $emailCheckQuery = "SELECT id FROM user WHERE email = ? AND id != ?";
        $emailCheckStmt = mysqli_prepare($db, $emailCheckQuery);
        mysqli_stmt_bind_param($emailCheckStmt, "si", $newEmail, $id);
        mysqli_stmt_execute($emailCheckStmt);
        $emailCheckResult = mysqli_stmt_get_result($emailCheckStmt);

        if (mysqli_num_rows($emailCheckResult) > 0) {
            $message =  "The email address is already in use.";
        } else {
            // Prepare the UPDATE query to update email and password for the user
            $query = "UPDATE user SET email = ?, password = ? WHERE id = ?";
            $stmt = mysqli_prepare($db, $query);

            // Bind the parameters
            mysqli_stmt_bind_param($stmt, "ssi", $newEmail, $newPassword, $id);

            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                // Update successful
                header("Location: /PFEs/profil/profil.php");
                exit();
            } else {
                // Update failed
                echo "Failed to update user details.";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($emailCheckStmt);
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
    <link rel="stylesheet" href="./profil.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>List ESP</title>
</head>

<body>
<header>
    <img src="/PFEs/figma/MyChoice (1).svg" alt="">
    <div class="profile" id="profile">
        <img class="chap" src="/backend/image/<?php echo $_SESSION['user_image'];?>" alt="">
        <?php echo $_SESSION['user_nompre'];?>
    </div>
</header>

<div style="height: 85vh;" class="body-container">
<div class="menubar">
        <div class="afficher">
            open menu
        </div>
        <div>
        <nav>
                    <a href="/PFEs/profil/profil.php">
                        <img style="height: 15px;" src="/PFEs/figma/profillle.svg" alt="">
                        My profil
                    </a>
                    <a href="/PFEs/wishlist/wishlist.php">
                     <img style="height: 15px;" src="/PFEs/figma/wish.svg" alt="">
                        My Wishlist
                    </a>
                    <a href="/PFEs/listeDesEncadreur/teachers_list.php">
                     <img style="height: 15px;" src="/PFEs/figma/techhher.svg" alt="">
                        Find a supervisor
                    </a>
                    <a href="/PFEs/liste-sujets/liste-sujets.php">
                    <img style="height: 15px;" src="/PFEs/figma/thesus.svg" alt="">
                        Themes available
                    </a>
                    <a href="/PFEs/homepage/homePage.php">
                    <img style="height: 15px;" src="/PFEs/figma//homme.svg" alt="">
                        Home
                    </a>
                    <a style="border-top: 1px solid white; justify-self:flex-end; " href="/logout.php">
                    <img style="height: 15px;" src="/PFEs/figma/logg.svg" alt="">
                        Logout
                    </a>
                </nav>
        </div>
    </div>

<div class="container">
    
    <form class="dashboard uu" action="" method="post">
    
    <h2 style="text-decoration: underline;"><img style="height: 30px;" src="/PFEs/figma/account-settings-svgrepo-com.svg" alt=""> Account info :</h2>
    <h3>
        <?php echo $message; ?>
    </h3>
        <label for="">Email :</label>
        <input class="inputo" type="text" name="email" value="<?php echo $row['email'];?>" >

        <label for="">Password :</label>
        <input class="inputo" type="text" name="password" value="<?php echo $row['password'];?>">

        <input class="edit-info-btn" type="submit" value="Edit information">
    </form>

</div>

</div>

     <footer>
        &#169; Gestion des PFEs et choix (isil/si)
    </footer>

</body>

</html>
