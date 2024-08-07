<?php 

include "../connectdb.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$id_etudient = isset($_POST['id_et']) ? $_POST['id_et'] : "";
$lechoix = isset($_POST['choix']) ? $_POST['choix'] : "";

    
$req = "UPDATE etudient SET choix = ? WHERE id = ?";
$stmt = mysqli_prepare($db, $req);
mysqli_stmt_bind_param($stmt, "si", $lechoix, $id_etudient);
mysqli_stmt_execute($stmt);


if (mysqli_stmt_affected_rows($stmt) > 0) {
    header("Location: /choix/done/demandeDone.php");
    exit;
} else {
    echo "Error inserting demande.";
}

mysqli_stmt_close($stmt);
mysqli_close($db); 
}
?>