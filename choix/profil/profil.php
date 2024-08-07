<?php
session_start();
include "../connectdb.php";

// Assuming the student's ID is stored in $_SESSION['user_id']
$id_user = $_SESSION['iduser_of_etudient'];

$requete = "SELECT * FROM user WHERE id = ?";
$stmt = $db->prepare($requete);
$stmt->bind_param('i', $id_user);
$stmt->execute();
$resultat = $stmt->get_result();

// display the result in table format
if ($resultat->num_rows > 0) {
    $row = $resultat->fetch_assoc();
} 

$id = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Add your head content here -->
    <title>Profil</title>
    <link rel="stylesheet" href="profil.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

</head>
<body>
<header>
    <img src="/PFEs/figma/MyChoice (1).svg" alt="">
    <div class="profile" id="profile">
        <img class="chap" src="/backend/image/<?php echo $_SESSION['user_image'];?>" alt="">
        <?php echo $_SESSION['user_nompre'];?>
    </div>
</header>

<div class="body-container">
<div class="menubar">
        <div class="afficher">
            open menu
        </div>
        <div>
        <nav>
                    <a href="/choix/profil/profil.php">
                        <img style="height: 15px;" src="/PFEs/figma/profillle.svg" alt="">
                        My profil
                    </a>
                    <a href="/choix/homePage/home.php">
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
    <div class="dashboard">
          <div class="greeting">
          <div><big style="font-size: 35px;"> Hello, <?php echo $_SESSION['user_nompre'];?></big> </div>
               <div style="line-height: 30px;">
                <hrey>date of registration :</hrey> <?php echo $_SESSION['creation'] ?> 
                <br> <hrey>Email :</hrey> <?php echo $row['email'];?>
                <br> <hrey>Level :</hrey> <?php echo $_SESSION['user_niveau'] ?> 
                <br> <hrey>Speciality :</hrey> <?php echo $_SESSION['user_specialite'] ?> 
                <br> <hrey>Reg-code :</hrey> <?php echo $_SESSION['user_id'];?>
                <br> <hrey>date of birth :</hrey> <?php echo $_SESSION['user_DOB'];?>
                <a class="edit-info" href="./edit_profil.php?id=<?php echo $id_user; ?>">Edit information <img height="25px" src="/PFEs/figma/edit.svg" alt=""></a>
            </div>
          </div>
          <img src="/PFEs/figma/Line 2.svg" alt="">
          <div class="images">
          <img class="aa" style="height: 180px;width: 180px; border-radius:50%;" src="/backend/image/<?php echo $_SESSION['user_image'];?>" alt="">
          </div>    
       </div>
    </div>
   
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var button = document.querySelector('.afficher');
            var navbar = document.querySelector('nav');
    
            button.addEventListener('click', () => {
                // Toggle the 'show' class on the navbar
                navbar.classList.toggle('show');
                // Change the button text to 'Close' when the menu is displayed
                button.innerHTML = (navbar.classList.contains('show')) ? 'close menu' : 'open menu';
            });
        });
    </script>
    <?php
if(isset($etat)) {
    if($etat == "accepted") {
        echo '<script>document.querySelector(".state").classList.add("state-green");</script>';
    } elseif($etat == "on hold") {
        echo '<script>document.querySelector(".state").classList.add("state-yellow");</script>';
    }
}
?>
</div>
<footer>
        &#169; Gestion des PFEs et choix (isil/si)
    </footer>
</body>
</html>
