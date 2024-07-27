<?php
session_start();
include "C:\Users\USER\OneDrive\Bureau\PFEs\connectdb.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['levels'])) {
        $action = $_POST['action'];
        $levels = json_decode($_POST['levels'], true);
        $state = ($action === 'enable') ? 'active' : 'inactive';

        foreach ($levels as $level) {
            $query = "
                UPDATE user 
                SET state = ? 
                WHERE id IN (
                    SELECT id_user 
                    FROM etudient
                    WHERE niveau = ?
                )";
            $stmt = mysqli_prepare($db, $query);
            mysqli_stmt_bind_param($stmt, "ss", $state, $level);

            if (!mysqli_stmt_execute($stmt)) {
                echo "Failed to update users for level $level";
                exit();
            }
            mysqli_stmt_close($stmt);
        }
        echo "Users updated successfully";
    } else {
        echo "Invalid parameters";
    }
} else {
    echo "Invalid request method";
}
?>
