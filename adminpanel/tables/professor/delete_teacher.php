<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['usid'])) {
    // User is not logged in, redirect to the login page
    header("Location: /login/login.php");
    exit();
}

// Include database connection
include "../connectdb.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'id' parameter is set in the POST request
    if (isset($_POST['id'])) {
        // Sanitize the user ID
        $userId = mysqli_real_escape_string($db, $_POST['id']);

        // Prepare the DELETE query for user table
        $queryUser = "DELETE FROM prof WHERE id = ?";
        $stmtUser = mysqli_prepare($db, $queryUser);
        mysqli_stmt_bind_param($stmtUser, "i", $userId);

        // Execute the query for user table
        if (mysqli_stmt_execute($stmtUser)) {
            // Deletion successful
            echo "success";
        } else {
            // Deletion failed
            echo "Failed to delete user";
        }

        mysqli_stmt_close($stmtUser);
    } else {
        // 'id' parameter is not set
        echo "User ID not provided";
    }
} else {
    // Invalid request method
    echo "Invalid request method";
}
?>
