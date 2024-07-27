<?php
session_start();

if (isset($_GET['name']) && isset($_GET['image'])) {
    $professorName = $_GET['name'];
    $professorImage = $_GET['image'];

} else {
    // Handle the case when the parameters are not set
    echo "Invalid URL parameters.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title >request for supervision </title>
    <link rel="stylesheet" href="/PFEs/demandeEncadreur/demande-encadre.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
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
                    <a href="/PFEs/listeDesEncadreur/teachers_list.php">
                     <img style="height: 15px;" src="/PFEs/figma/techhher.svg" alt="">
                        Find a supervisor
                    </a>
                    <a href="/PFEs/liste-sujets/liste-sujets.php">
                    <img style="height: 15px;" src="/PFEs/figma/thesus.svg" alt="">
                        themes available
                    </a>
                    <a href="/PFEs/homepage/homePage.php">
                    <img style="height: 15px;" src="/PFEs/figma//homme.svg" alt="">
                        Home
                    </a>
                    <a href="/logout.php">
                    <img style="height: 15px;" src="/PFEs/figma/logg.svg" alt="">
                        Logout
                    </a>
                </nav>
            </div>
        </div>
    <div class="container">
    <h2>
Request for supervision of license thesis
</h2>
<h4>
if you have a partner  <a class="binome-link" href="/PFEs/demandeEncadreur/binome_demande.php?nom_prof=<?php echo $professorName; ?>&image_prof=<?php echo $professorImage ?>"> Click here</a>
</h4>
          <form action="/backend/SignUpForSupervisor.php" enctype="multipart/form-data" method="post">
            <div class="persons">
               <div class="student">
                <img class="icon" src="/backend/image/<?php echo $_SESSION['user_image'];?>" alt="">
                <div class="student-info">
                    <label for="name">Full name:</label>
                    <input class="readonly-input" name="student" value="<?php echo $_SESSION['user_nompre'];?>" type="text" readonly >
                    <label for="dateNaissance">Date of birth:</label>
                    <input class="readonly-input"  type="date" value="<?php echo $_SESSION['user_DOB'];?>" readonly>
                </div>
               </div>
               <img class="line" src="/PFEs/figma/Line 2.svg" alt="">
               <div class="teacher">
                <img class="icon" src="/backend/image/<?php echo $professorImage; ?>" alt="">
                <div class="teacher-info">
                    <label for="name"> Full name:</label>
                    <input class="readonly-input" name="prof"  value="<?php echo $professorName; ?>" type="text" readonly>
                </div>
               </div>
            </div>
            <div class="information">
              <label for="intitule"> dissertation title :</label>
              <input class="inputo" type="text" name="intitule" id="intitule">
              <label for="resume">summary :</label>
              <textarea class="inputo-area" name="resume" id="resume" cols="1" rows="5"></textarea>
              <label for="intitule"> Key-words :</label>
              <input class="inputo" type="text" name="keyword">
            </div>
            <button name="submit">
            Submit request
            </button>
          </form>    
    </div>
    </div>

    <footer>
    &#169; Gestion des PFEs et choix (isil/si)
    </footer>
</body>
</html>