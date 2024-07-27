<?php
session_start();

include "C:/Users/USER/OneDrive/Bureau/PFEs/connectdb.php";

$id = $_SESSION['user_id'];

$req = "SELECT choix FROM etudient WHERE id = ?";
$stmt = mysqli_prepare($db, $req);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$choix = $row['choix'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="home.css">
    <link rel="preload" href="/PFEs/figma/pexels-yungsaac-1557251.jpg" as="image">
    <title>Home page</title>
</head>
<body>
    <header>
        <img src="/PFEs/figma/MyChoice (1).svg" alt="">
        <div class="right-header">
            <div class="profile">
                <div class="chap">
                    <img class="chap" src="/backend/image/<?php echo $_SESSION['user_image']; ?>" alt="">
                </div>       
                <?php echo $_SESSION['user_nompre']; ?>
            </div>
        </div>
    </header>
    <div class="body-container">
        <div class="menubar">
            <div>
                <nav>
                    <a href="/choix/profil/profil.php">
                        <img style="height: 15px;" src="/PFEs/figma/profillle.svg" alt="">
                        My profile
                    </a>
                    <a href="/choix/homePage/home.php">
                        <img style="height: 15px;" src="/PFEs/figma//homme.svg" alt="">
                        Home
                    </a>
                    <a style="border-top: 1px solid white; justify-self:flex-end;" href="/logout.php">
                        <img style="height: 15px;" src="/PFEs/figma/logg.svg" alt="">
                        Logout
                    </a>
                </nav>
            </div>
        </div>
        <div class="container">
            <section>
                <div class="left">
                    <h1>Welcome to the platform <br> MyChoice</h1>
                    <h4>The MyChoice platform<br>allows you to choose your specialty in 3rd year with ease</h4>
                    <div class="link-start">
                        <?php if ($choix): ?>
                            <span class="start ep"> 
                                <img height="20px" src="/PFEs/figma/no.svg" alt=""> 
                                You already submitted your choice
                            </span>
                        <?php else: ?>
                            <a class="start" href="/choix/chosePage/chosepage.php">Get started</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="right">
                    <img class="student" src="/PFEs/figma/girl-graduation-student-svgrepo-com.svg" alt="">
                </div>
            </section>
        </div>
    </div>
    <footer>
        &#169; Gestion des PFEs et choix (isil/si)
    </footer>
</body>
</html>
