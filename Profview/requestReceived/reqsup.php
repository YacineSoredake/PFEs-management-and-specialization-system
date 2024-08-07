<?php
session_start();
include "../connectdb.php";

$id = $_SESSION['prof_id'];

$query = "SELECT demandesupervision.*, 
                 etudient1.nompre AS student_name, 
                 etudient2.nompre AS partner_name,
                 etudient1.niveau AS student_level, 
                 etudient1.specialite AS student_specialite
          FROM demandesupervision
          JOIN etudient AS etudient1 ON demandesupervision.id_etudient = etudient1.id
          LEFT JOIN etudient AS etudient2 ON demandesupervision.id_pair = etudient2.id
          WHERE demandesupervision.id_prof = ? AND demandesupervision.etat = 'On hold'";

$stmtt =  mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmtt, "i", $id);
mysqli_stmt_execute($stmtt);
$resultat = mysqli_stmt_get_result($stmtt);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>request received</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="listrequest.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    
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
   
    <?php
    if (mysqli_num_rows($resultat) > 0) {
        while ($rows = mysqli_fetch_assoc($resultat)) {
    ?>
    list for supervising (extern theme)
    <div class="request" id="requestSup_<?php echo $rows['id']; ?>">
                <label for="studentName"> Student full name :</label>
                <input type="text" name="studentName" readonly value="<?php echo $rows['student_name']?>">
                <label for="studentName"> Partner full name :</label>
                <input type="text" name="partnerName" readonly value="<?php echo $rows['partner_name']; ?>">
                <label for="level">Level:</label>
                <input type="text" name="level" readonly value="<?php echo $rows['student_level']?>">
                <label for="specialite">Speciality :</label>
                <input type="text" name="specialite" readonly value="<?php echo $rows['student_specialite']?>">
                <label for="themename"> Theme title :</label>
                <input type="text" name="themename" readonly value="<?php echo $rows['intitule']?>">
                <label for="sum"> Summary :</label>
                <input type="text" name="sum" readonly value="<?php echo $rows['resume']?>">
                <button class="btn-r refuse" data-request-id="<?php echo $rows['id']; ?>"> Refuse </button>
                <button class="btn-g accept" data-request-id="<?php echo $rows['id']; ?>"> Accept </button>
     </div>
     <?php
        }
    } else {
    ?>
    <img style="height: 200px;" src="/PFEs/figma/no-notfication-1-svgrepo-com.svg" alt="">
    <p>No requests for you at the moment.</p>
    <?php
    }
    ?>
    </div>
     </div>

    <footer>
    &#169; Gestion des PFEs et choix (isil/si)
    </footer>
</body>
<script>
$(document).ready(function() {
    // Attach a click event handler to the "Refuse" buttons
    $(".refuse").click(function() {
        handleRequest($(this).data("request-id"), "refuse");
    });

    // Attach a click event handler to the "Accept" buttons
    $(".accept").click(function() {
        handleRequest($(this).data("request-id"), "accept");
    });

    function handleRequest(requestId, action) {
        $.ajax({
            type: "POST",
            url: "update_sup.php",
            data: { action: action, requestId: requestId },
            success: function(response) {
                console.log(response);

                // Assuming you want to remove the request div on success
                $("#requestSup_" + requestId).remove();

                // Reload the page after handling the request
                location.reload();
            },
            error: function(error) {
                console.error("Error handling request: ", error);
            }
        });
    }
});
    </script>

</html>
