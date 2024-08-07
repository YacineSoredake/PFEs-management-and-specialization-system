<?php
session_start();

include "../connectdb.php";
$user_id = $_SESSION['user_id'];

// Check if the user_id exists in demandesupervision table
$stmt_demandesupervision = mysqli_prepare($db, "SELECT id_etudient FROM demandesupervision WHERE id_etudient = ?");
mysqli_stmt_bind_param($stmt_demandesupervision, "i", $user_id);
mysqli_stmt_execute($stmt_demandesupervision);
$result_demandesupervision = mysqli_stmt_get_result($stmt_demandesupervision);

$stmt = mysqli_prepare($db, "SELECT nomprenom, specialite, image FROM prof");
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$stmt_state = mysqli_prepare($db,"SELECT etat from demandesupervision WHERE id_etudient= $user_id OR id_pair = $user_id");
mysqli_stmt_execute($stmt_state);
$resultstate=  mysqli_stmt_get_result($stmt_state);
$row_state = mysqli_fetch_assoc($resultstate);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor list</title>
    <link rel="stylesheet" href="teacher-list.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="preload" href="/PFEs/figma/pexels-yungsaac-1557251.jpg" as="image">
</head>
<body>
    <!-- Header Section -->
    <header>
       <img src="/PFEs/figma/MyChoice (1).svg" alt="">
       <div class="state-view">
       <?php 
       if(mysqli_num_rows($result_demandesupervision) > 0) {
        $state = $row_state['etat'];
        echo 'Request state :' . $state ;
       }
       ?>
       </div>
       <div class="profile">
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
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="teacher-div"> 
                <img class="icon" src="/backend/image/<?php echo $row['image']; ?>" alt="">
                <div class="teacher-info">
                    <div class="names">
                        Full name:
                        <p>
                        <?php echo $row['nomprenom']; ?>
                        </p>

                    </div>
                    <div class="spec">
                        Specilality :
                        <p>
                        <?php echo $row['specialite']; ?>
                        </p>
                       
                    </div>
                    <?php 
                // Check if the user_id exists in demande or demandesupervision table
                if(mysqli_num_rows($result_demandesupervision) > 0) {
                    // User has already submitted a request, disable the link
                    echo '<a class="demande disabled-link">Send request</a>';
                } else {
                    echo '<a class="demande" href="/PFEs/demandeEncadreur/demande-encadre.php?name='. urlencode($row['nomprenom']) . '&image=' . urlencode($row['image']).'">Send a request</a>';
                }
                ?>
                </div>
                
            </div>
        <?php endwhile; ?>
    </div>
   </div>
    
    

    <!-- Footer Section -->
    <footer>
        &#169; Gestion des PFEs et choix (isil/si)
    </footer>

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
</body>
</html>
