<?php 
session_start();
include "../connectdb.php";

$user_level = $_SESSION['user_niveau'];
$user_id = $_SESSION['user_id'];
$partner_id = $_SESSION['partner'];

// Check if the user_id exists in demande table
$stmt_demande = mysqli_prepare($db, "SELECT id_theme FROM demande d WHERE d.id_etudient = ? OR d.id_etudient = ?");
mysqli_stmt_bind_param($stmt_demande, "ii", $user_id,$partner_id);
mysqli_stmt_execute($stmt_demande);
$result_demande = mysqli_stmt_get_result($stmt_demande);

$stmt = mysqli_prepare($db, "SELECT theme.*, prof.nomprenom,prof.image
                              FROM theme
                              JOIN prof ON theme.id_prof = prof.id
                              WHERE theme.niveau = ? AND theme.id not in (SELECT id_theme from demande where id_etudient = $user_id)");
mysqli_stmt_bind_param($stmt, "s", $user_level);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme list</title>
    <link rel="stylesheet" href="liste-sujets.css">
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
                    <a style="border-top: 1px solid white; justify-self:flex-end;" href="/logout.php">
                    <img style="height: 15px;" src="/PFEs/figma/logg.svg" alt="">
                        Logout
                    </a>
                </nav>
            </div>
        </div>
        <div class="container">
        <img class="p" src="/PFEs/figma/content-svgrepo-com.svg" alt="">
        <img class="p z" src="/PFEs/figma/idea.png" alt="">
        <img class="zp" src="/PFEs/figma/computer-engineer.png" alt="">
        <h1>
            <?php if(mysqli_num_rows($result_demande) >= 5) {
                echo '<red>Sorry, you already submited 5 themes</red>';
            } else {
                echo 'THESIS TITLE';
            } ?>
        </h1>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="teacher-div"> 
               <div class="supervisor-inf">
               <label for="encadrant">Supervisor:</label>
               <img style="height: 100px; border-radius:10%;" src="/backend/image/<?php echo $row['image'];?>" alt="">
               <?php echo $row['nomprenom']; ?>
               </div>
               <label for="themes">Title:</label>
               <input class="inputo" name="theme" value="<?php echo $row['intitule']; ?>" readonly>
               <label for="summary">Summary:</label>
               <textarea  name="summary" rows="5" readonly><?php echo $row['summary']; ?></textarea>
               <label for="keywords">Key-words:</label>
               <textarea  name="keywords" rows="2" readonly><?php echo $row['keyword']; ?></textarea>
               <?php 
                    // Check if the user_id exists in demande or demandesupervision table
                    if(mysqli_num_rows($result_demande) >= 5) {
                        // User has already submitted a request, disable the link
                        echo '<a class="link disabled-link">Add to Wishlist</a>';
                    } else {
                        // User can submit a request, show the link
                        echo '<a class="link" href="/PFEs/deamndeSujet/demandesujet.php?id_prof=' . urlencode($row['id_prof']) . '&intitule=' . urlencode($row['intitule']) . '">Add to Wishlist</a>';
                    }
                ?>
            </div>
        <?php endwhile; ?>    
        </div>
    </div>
    <footer>
        &#169; Gestion des PFEs et choix (isil/si)
    </footer>
</body>
</html>
