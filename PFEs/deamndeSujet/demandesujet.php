<?php
session_start();

include "C:\Users\USER\OneDrive\Bureau\PFEs\connectdb.php";

$user_id = $_SESSION['user_id'];

if (isset($_GET['id_prof']) && isset($_GET['intitule'])) {
    $professorid = $_GET['id_prof'];
    $themeintitule = $_GET['intitule'];

    $stmt = mysqli_prepare($db, "SELECT image, nomprenom FROM prof WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $professorid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $professorData = mysqli_fetch_assoc($result);
    // Now you can use $professorData['image'] and $professorData['nomprenom'] in your HTML
    
} else {
    // Handle the case when the parameters are not set
    echo "Invalid URL parameters.";
}

$stmtPriority = mysqli_prepare($db, "SELECT COUNT(id_etudient) FROM demande WHERE id_etudient = ?");
mysqli_stmt_bind_param($stmtPriority, 'i', $user_id);
mysqli_stmt_execute($stmtPriority);
$resultPriority = mysqli_stmt_get_result($stmtPriority);
$priorityvalue = mysqli_fetch_assoc($resultPriority);

$nextPrio = $priorityvalue['COUNT(id_etudient)'] + 1;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./demandesujet.css">
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
    <h2>
    ADD THEME TO YOUR WISH LIST
    </h2>
          <form action="/backend/SignUpDemande.php" method="post">
            <div class="persons">
               <div class="student">
                <img class="icon" src="/backend/image/<?php echo $_SESSION['user_image'];?>" alt="">
                <div class="student-info">
                    <label for="name">Full name:</label>
                    <input class="readonly-input" value="<?php echo $_SESSION['user_nompre'];?>" name="student_name" type="text" readonly >
                    <label for="dateNaissance">Date of birth:</label>
                    <input value="<?php echo $_SESSION['user_DOB'];?>" name="student_dob" class="readonly-input"  type="date" readonly>
                </div>
               </div>
               <img class="line" src="/PFEs/figma/Line 2.svg" alt="">
               <div class="teacher">
                <img class="icon" src="/backend/image/<?php echo $professorData['image']; ?>" alt="">
                <div class="teacher-info">
                    <label for="name">Full name:</label>
                    <input class="readonly-input" name="prof_name" value="<?php echo $professorData['nomprenom']; ?>" type="text" readonly>
                </div>
               </div>
            </div>
            <div class="information">
              <label for="intitule">Title of the dissertation :</label>
              <input class="inputo" type="text" name="intitule" value="<?php echo $themeintitule; ?>">
              <label for="intitule">Priority:</label>
              <input class="inputo" name="priority" type="number" value="<?php echo $nextPrio; ?>" readonly>
            </div>
            <button name="submit">
                Add to wishlist
            </button>
          </form>    
    </div>
    </div>

<footer>
    &#169; Gestion des PFEs et choix (isil/si)
</footer>
</body>
</html>