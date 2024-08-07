<?php

include "../connectdb.php";
require '../PHPMailer\src\PHPMailer.php';
require '../PHPMailer\src\SMTP.php';
require '../PHPMailer\src\Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $intitule = isset($_POST['intitule']) ? $_POST['intitule'] : "";
    $student_name = isset($_POST['student_name']) ? $_POST['student_name'] : "";
    $student_dob = isset($_POST['student_dob']) ? $_POST['student_dob'] : "";
    $priority = isset($_POST['priority']) ? $_POST['priority'] : null;

    // Getting theme_id
    $theme_query = "SELECT id FROM theme WHERE intitule = ?";
    $stmt = mysqli_prepare($db, $theme_query);
    mysqli_stmt_bind_param($stmt, "s", $intitule);
    mysqli_stmt_execute($stmt);
    $theme_result = mysqli_stmt_get_result($stmt);

    if ($theme_row = mysqli_fetch_assoc($theme_result)) {
        $theme_id = $theme_row['id'];
    } 

    // Getting student_id
    $student_query = "SELECT id,ranking FROM etudient WHERE nompre = ?";
    $stmt = mysqli_prepare($db, $student_query);
    mysqli_stmt_bind_param($stmt, "s", $student_name);
    mysqli_stmt_execute($stmt);
    $student_result = mysqli_stmt_get_result($stmt);

    if ($student_row = mysqli_fetch_assoc($student_result)) {
        $etudient_id = $student_row['id'];
        $etudient_rank = $student_row['ranking'];
    } else {
        echo "Student ID not found for student: $student_name";
        exit;
    }
    
    $req = "INSERT INTO demande(id_theme, id_etudient,priority) VALUES (?, ?,?)";
    $stmt = mysqli_prepare($db, $req);

    // Bind the parameters for id_theme, id_prof, id_etudient, and id_pair
    mysqli_stmt_bind_param($stmt, "iii", $theme_id,$etudient_id,$priority);

    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        header("Location: /PFEs/done/demandeDone.php");
        exit;
    } else {
        echo "Error inserting demande.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($db); 
}
?>
