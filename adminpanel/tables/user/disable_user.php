<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['usid'])) {
    header("Location: /login/login.php");
    exit();
}

include "../connectdb.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'id' parameter is set in the POST request
    if (isset($_POST['id'])) {
        // Sanitize the user ID
        $userId = mysqli_real_escape_string($db, $_POST['id']);

        // Prepare the UPDATE query to deactivate the user
        $queryUser = "UPDATE user SET state = 'inactive' WHERE id = ?";
        $stmtUser = mysqli_prepare($db, $queryUser);
        mysqli_stmt_bind_param($stmtUser, "i", $userId);

        // Execute the query
        if (mysqli_stmt_execute($stmtUser)) {
            // Deactivation successful
            echo "success";
        } else {
            // Deactivation failed
            echo "Failed to deactivate user";
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
