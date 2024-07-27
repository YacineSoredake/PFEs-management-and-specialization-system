<?php
session_start();

include "C:\Users\USER\OneDrive\Bureau\PFEs\connectdb.php";

if (isset($_POST['rechercher'])) {
    $matricule = $_POST['matricule']; 
    $user_level = $_SESSION['user_niveau']; // Niveau de l'utilisateur

    // Préparer et exécuter la requête SQL pour vérifier dans la table 'etudient'
    $requete_etudient = "SELECT * FROM etudient WHERE id = ?";
    $stmt_etudient = $db->prepare($requete_etudient);
    $stmt_etudient->bind_param('i', $matricule);
    $stmt_etudient->execute();
    $resultat_etudient = $stmt_etudient->get_result();

    // Vérifier si l'étudiant existe et a le même niveau que l'utilisateur
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



if (isset($_GET['nom_prof']) && isset($_GET['image_prof'])) {
    $professorName = $_GET['nom_prof'];
    $professorImage = $_GET['image_prof'];

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
    <title>Document</title>
    <link rel="stylesheet" href="/PFEs/demandeEncadreur/binome.css">
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
        <div style="padding-top: 50px; align-self: flex-start;">
            <div>
                <p style="text-decoration: underline; color:red;"><img style="height: 15px;" src="/PFEs/figma/rule.svg" alt=""> rules :</p>
                <p>- Your partner must be in the same level as you</p>
                <p>- Your partner should be available</p>
            </div>
            <form method="post" class="for-search">
                    <img style="height: 20px;" src="/PFEs/figma/add.svg" alt="">
                    <label for="matricule">Parter:</label>
                    <input placeholder="registration-code" class="inputo" type="text" name="matricule" id="matricule" />
                    <button type="submit" class="add" name="rechercher">add</button>
            </form>

        </div>
            
          <form action="/backend/SignUpForSupervisor.php" method="post">
            <div class="persons">
               <div class="student">
                <h4>
                    You
                </h4>
                <img class="icon" src="/backend/image/<?php echo $_SESSION['user_image'];?>" alt="">
                <div class="student-info">
                    <label for="name">Full name:</label>
                    <input name="student" class="readonly-input" value="<?php echo $_SESSION['user_nompre'];?>" type="text" readonly >
                    <label for="dateNaissance">Date of birth:</label>
                    <input value="<?php echo $_SESSION['user_DOB'];?>" class="readonly-input"  type="date" readonly>
                </div>
               </div>
               <?php if (!empty($errorMsg)) : ?>
                    <div style="color:red;"><?php echo $errorMsg; ?></div>
               <?php endif; ?>
                <div class="student student2-container" style="display: <?php echo $student2Display; ?>;">
                <h4>
                Partner
                </h4>
                <img class="icon" src="/backend/image/<?php echo $row_etudient['image'];?>" alt="">
                <div class="student-info">
                    <label for="name">Full name::</label>
                    <input style="display: none;" type="number" name="id_pair" value="<?php echo $row_etudient['id']; ?>">
                    <input placeholder=" -------" class="readonly-input" value="<?php if(!empty($row_etudient['nompre'])){ echo $row_etudient['nompre']; } ?>" type="text"  >
                    <label for="dateNaissance">Date of birth:</label>
                    <input value="<?php if(!empty($row_etudient['datenaissance'])){ echo $row_etudient['datenaissance']; } ?>" class="readonly-input" type="date" readonly>
                </div>
               </div>
               <div class="teacher">
               <h4>
                Supervisor
                </h4>
                <img class="icon" src="/backend/image/<?php echo $professorImage; ?>" alt="">
                <div class="teacher-info">
                    <label for="name">Full name:</label>
                    <input name="prof" class="readonly-input"  value="<?php echo $professorName?>" type="text" readonly>
                </div>
               </div>
            </div>
            <div class="information">
              <label for="intitule">dissertation title :</label>
              <input class="inputo" type="text" name="intitule">
              <label for="resume">summary :</label>
              <textarea class="inputo-area" name="resume" id="resume" cols="30" rows="6"></textarea>
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
<script>
    function displayImagePreview() {
    // Get the file input element
    var fileInput = document.getElementById('file');

    // Get the image preview element
    var imagePreview = document.getElementById('image-preview');

    // Check if a file is selected
    if (fileInput.files.length > 0) {
        // Create a FileReader
        var reader = new FileReader();

        // Set a callback function to handle the file reading
        reader.onload = function (e) {
            // Set the source of the image preview to the data URL
            imagePreview.src = e.target.result;

            // Display the image preview
            imagePreview.style.display = 'block';
        };

        // Read the selected file as a data URL
        reader.readAsDataURL(fileInput.files[0]);
    } else {
        // No file selected, hide the image preview
        imagePreview.style.display = 'none';
    }
}

</script>
</html>
