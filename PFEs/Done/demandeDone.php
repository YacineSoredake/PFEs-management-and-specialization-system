<?php
session_start();

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> request Success</title>
<link rel="stylesheet" href="done.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
<header>
        <img src="/PFEs/figma/MyChoice (1).svg" alt="">
        <div class="right-header">
            <div class="profile">
            <div class="chap">
            <img class="chap" src="/backend/image/<?php echo $_SESSION['user_image'];?>" alt="">
            </div>       
             <?php echo $_SESSION['user_nompre'];?>
            </div>
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
        <section>
            <h2>Your request was sent successfully</h2>
            <img class="img-done" src="/PFEs/figma/done-ring-round-svgrepo-com.svg" alt="">
            <a class="Home-link" href="/PFEs/homepage/homePage.php">Return to home page</a>
        </section>
     </div>
     </div> 
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
