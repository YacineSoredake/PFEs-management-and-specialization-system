<?php
session_start();
include "C:\Users\USER\OneDrive\Bureau\PFEs\connectdb.php";

// Check if item ID is provided and the user is logged in
if (isset($_POST['item_id']) && isset($_SESSION['user_id'])) {
    $item_id = $_POST['item_id'];

    // Prepare and bind parameters to prevent SQL injection
    $query = "DELETE FROM demande WHERE id_theme = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $item_id);

    // Execute the delete query
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false, "message" => "Failed to delete item"));
    }

    // Close statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($db);
} else {
    echo json_encode(array("success" => false, "message" => "Item ID or user ID not provided"));
}
?>
