<?php
session_start();

include "../connectdb.php";

$id = $_SESSION['user_id'];
$profquery = "SELECT * FROM prof WHERE id = ?";
$stmtprof = mysqli_prepare($db, $profquery);
mysqli_stmt_bind_param($stmtprof, "i", $id);
mysqli_stmt_execute($stmtprof);
$resultprof = mysqli_stmt_get_result($stmtprof);

$profRow = mysqli_fetch_assoc($resultprof);

$_SESSION['prof_id'] = $profRow['id'];
$_SESSION['prof_name'] = $profRow['nomprenom'];
$_SESSION['prof_grade'] = $profRow['grade'];
$_SESSION['prof_image'] = $profRow['image'];
$_SESSION['prof_specialite'] = $profRow['specialite'];
$_SESSION['prof_DOB'] = $profRow['dateNaissance'];
$_SESSION['iduser_of_prof'] =$profRow['id_user'];
$_SESSION['creation'] =$profRow['creation'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="homepage.css">
    <title> Home page</title>
</head>
<body>
    <header>
        <div class="right-header">
        <img src="/PFEs/figma/MyChoice (1).svg" alt="">
        </div>
        <div class="right-header">
            <div class="profile">
                <div class="chap">
                <img class="chap" src="/backend/image/<?php echo $_SESSION['prof_image']; ?>" alt="">
                </div> 
                <?php echo $_SESSION['prof_name']; ?>
            </div>
           
        </div>
     </header>
     <div class="body-container">
        <div class="menubar">
            <div>
                <nav>
                    <a href="/Profview/profile/profile.php">
                        <img style="height: 15px;" src="/PFEs/figma/profillle.svg" alt="">
                        My profil
                    </a>
                    <a href="/Profview/themes/listproftheme.php">
                    <img style="height: 15px;" src="/PFEs/figma/thesus.svg" alt="">
                        My Themes
                    </a>
                    <a href="/Profview/requestReceived/reqsup.php">
                    <img style="height: 15px;" src="/PFEs/figma//homme.svg" alt="">
                        Request
                    </a>
                    <a href="/Profview/homepage/homepage.php">
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
    <section>
            <h1>
            Welcome ! <?php echo $_SESSION['prof_name']; ?> to the platform <strong>MyChoice</strong> 
            </h1>
            <h3>
                the platform allows teachers to better manage their dissertation theme <br> and facilitates student teacher contact 
            </h3>
            <img style="height: 100px;" src="/PFEs/figma/talking-classroom-svgrepo-com.svg" alt="">
    </section>
    <img height="200px" src="/PFEs/figma/teacher (1).png" alt="">
    </div>
    </div>

    <footer>
    &#169; Gestion des PFEs et choix (isil/si)
    </footer>
</body>
</html>
