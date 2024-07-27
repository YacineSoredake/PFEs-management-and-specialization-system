<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="preload" href="/PFEs/figma/pexels-yungsaac-1557251.jpg" as="image">
</head>
<body>
    <header>
       <img src="/PFEs/figma/MyChoice (1).svg" alt="">
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

    <div class="contain">
     
       <div class="dashboard">
          <div class="greeting">
          <div><small id="current-date"></small></div>
               <div style="line-height: 30px;"> <big>Welcome back , <?php echo $_SESSION['user_nompre'];?> !</big> <br> <small>Always stay updated in your student portal</small> </div>
          </div>
          <div class="images">
          <img class="aa" src="/PFEs/figma/Scholarcap scroll.svg" alt="">
          <img class="aa" src="/PFEs/figma/pers.png" alt="">
          <img class="aa" src="/PFEs/figma/Backpack.svg" alt="">
          </div>
          
       </div>
       <div class="container">
       <a href="/PFEs/listeDesEncadreur/teachers_list.php">
            <div class="encadreur-div">
               <img class="icon" src="/PFEs/figma/teacher-svgrepo-com.svg" alt="">
                Find a supervisor
            </div>
       </a>
       <a  href="/PFEs/profil/profil.php">
            <div class="profil-div"> 
                    <img class="icon" src="/PFEs/figma/profile-round-1345-svgrepo-com.svg" alt="">
                    My profil 
            </div>
       </a>
       <a href="/PFEs/liste-sujets/liste-sujets.php">
            <div class="themes-div"> 
                    <img class="icon" src="/PFEs/figma/studies-svgrepo-com.svg" alt="">
                    Themes available
            </div>
       </a>    
       </div>
    </div>

    </div>

    <footer>
    &#169; Gestion des PFEs et choix (isil/si)
    </footer>

<script>
  // Get the current date
  var currentDate = new Date();
  
  // Format the date as "Month Day, Year"
  var month = currentDate.toLocaleString('default', { month: 'long' });
  var day = currentDate.getDate();
  var year = currentDate.getFullYear();
  var formattedDate = month + " " + day + ", " + year;

  // Set the formatted date into the small tag
  document.getElementById("current-date").textContent = formattedDate;
</script>
</body>
</html>