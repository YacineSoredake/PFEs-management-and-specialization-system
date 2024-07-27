<?php
session_start();
include "C:\Users\USER\OneDrive\Bureau\PFEs\connectdb.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $themeId = $_POST['theme_id'];
    $profId = $_SESSION['prof_id'];

    $deleteQuery = "DELETE FROM theme WHERE id_prof = ? AND id = ?";
    $deleteStmt = mysqli_prepare($db, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, "ii", $profId, $themeId);

    if (mysqli_stmt_execute($deleteStmt)) {
        // Theme deleted successfully
        echo "success";
        exit();
    } else {
        // Handle the deletion failure
        echo "error: " . mysqli_error($db);
        exit();
    }
} else {
    // Invalid request method
    echo "error: Invalid request method";
    exit();
}
?>
