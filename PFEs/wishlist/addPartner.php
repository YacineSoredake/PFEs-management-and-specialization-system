<?php
session_start();

include "../connectdb.php";
$user_id = $_SESSION['user_id'];

$query_partner = "SELECT id_partner FROM etudient WHERE id = $user_id";
$result_partner = mysqli_query($db, $query_partner);
$row_partner = mysqli_fetch_assoc($result_partner);
if ($row_partner['id_partner']) {
    $student2Display = 'flex';
    $id_partner = $row_partner['id_partner'];
    $errorMsg = 'You already have a partner.';
    $requete_etudient = "SELECT * FROM etudient WHERE id = ?";
    $stmt_etudient = $db->prepare($requete_etudient);
    $stmt_etudient->bind_param('i', $id_partner);
    $stmt_etudient->execute();
    $resultat_etudient = $stmt_etudient->get_result();
    $row_etudient = $resultat_etudient->fetch_assoc();
}

if (isset($_POST['confirm'])) {
    $id_pair = $_POST['id_pair'];
    // Insert first student with their partner
    $query1 = "UPDATE  etudient  set id_partner = $id_pair WHERE id = $user_id";
    // Insert second student with their partner
    $query2 = "UPDATE  etudient  set id_partner = $user_id WHERE id = $id_pair";

    // Execute the first query
    if (!mysqli_query($db, $query1)) {
        echo "Error inserting first student: " . mysqli_error($db);
    }

    // Execute the second query
    if (!mysqli_query($db, $query2)) {
        echo "Error inserting second student: " . mysqli_error($db);
    } 
    $errorMsg = 'Partner added';
}


if (isset($_POST['rechercher'])) {
    
    $matricule = $_POST['matricule'];
    $user_level = $_SESSION['user_niveau']; // Niveau de l'utilisateur

    // Préparer et exécuter la requête SQL pour vérifier dans la table 'etudient'
    $requete_etudient = "SELECT * FROM etudient WHERE id = ?";
    $stmt_etudient = $db->prepare($requete_etudient);
    $stmt_etudient->bind_param('i', $matricule);
    $stmt_etudient->execute();
    $resultat_etudient = $stmt_etudient->get_result();

    if ($resultat_etudient->num_rows > 0) {
        $row_etudient = $resultat_etudient->fetch_assoc();
        if ($row_etudient['niveau'] == $user_level) {
            // Préparer et exécuter la requête SQL pour vérifier dans les tables 'demande' et 'supervision'
            $requete_demande_supervision = "SELECT id_etudient FROM demande WHERE id_etudient = ? AND etat = 'accepted' UNION SELECT id_etudient FROM demandesupervision WHERE id_etudient = ? AND etat = 'accepted'";
            $stmt_demande_supervision = $db->prepare($requete_demande_supervision);
            $stmt_demande_supervision->bind_param('ii', $row_etudient['id'], $row_etudient['id']);
            $stmt_demande_supervision->execute();
            $resultat_demande_supervision = $stmt_demande_supervision->get_result();

            // Si l'étudiant existe dans les tables 'demande' ou 'supervision', afficher un message
            if ($resultat_demande_supervision->num_rows > 0) {
                $errorMsg = 'The following student is unavailable.';
            } else {
                $student2Display = 'flex';
            }
        } else {
            // Si l'étudiant n'a pas le même niveau que l'utilisateur, afficher un message d'erreur
            $errorMsg = 'The following student do not have the same level.';
        }
    } else {
        // Si l'étudiant n'existe pas, afficher un message d'erreur
        $errorMsg = 'Student not found. Please enter a valid registration number.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./add.css">
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
        <div style="padding-top: 50px;">
            <div>
                <p style="text-decoration: underline; color:red;"><img style="height: 15px;" src="/PFEs/figma/rule.svg" alt=""> rules :</p>
                <p>- Your partner must be in the same level as you</p>
                <p>- Your partner should be available</p>
            </div>
            <form method="post" class="for-search">
                    <img style="height: 20px;" src="/PFEs/figma/add.svg" alt="">
                    <label for="matricule">Parter:</label>
                    <input placeholder="registration-code" class="inputo" type="text" name="matricule" id="matricule" />
                    <button type="submit" class="add" name="rechercher">Search</button>
            </form>

        </div>
            
          <form action="" method="post">
            <div class="persons">
               <?php if (!empty($errorMsg)) : ?>
                    <div style="color:red;"><?php echo $errorMsg; ?></div>
               <?php endif; ?>
                <div class="student student2-container" style="display: <?php echo $student2Display; ?>;">

                <img class="icon" src="/backend/image/<?php echo $row_etudient['image'];?>" alt="">
                <div class="student-info">
                    <label for="name">Full name::</label>
                    <input style="display: none;" type="number" name="id_pair" value="<?php echo $row_etudient['id']; ?>">
                    <input placeholder=" -------" class="readonly-input" value="<?php if(!empty($row_etudient['nompre'])){ echo $row_etudient['nompre']; } ?>" type="text"  >
                    <label for="dateNaissance">Date of birth:</label>
                    <input value="<?php if(!empty($row_etudient['datenaissance'])){ echo $row_etudient['datenaissance']; } ?>" class="readonly-input" type="date" readonly>
                    <button name="confirm">
                    ADD
                 </button>
                </div>
               </div>
            </div>
            
          </form>    
    </div>
    </div>

    <footer>
    &#169; Gestion des PFEs et choix (isil/si)
    </footer>
</body>

</html>
