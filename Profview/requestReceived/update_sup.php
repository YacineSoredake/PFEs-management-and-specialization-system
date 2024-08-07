<?php
session_start();
include "../connectdb.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['requestId'])) {
    $action = $_POST['action'];
    $requestId = $_POST['requestId'];

    $id = $_SESSION['prof_id'];

    if ($action == "refuse") {
        // Delete the row for "refuse" action
        $RefuseQuery = "UPDATE demandesupervision SET etat = 'refused' WHERE id = ? AND id_prof = ?";
        $refuseStmt = mysqli_prepare($db, $RefuseQuery);

        if ($refuseStmt) {
            mysqli_stmt_bind_param($refuseStmt, "ii", $requestId, $id);
            $success = mysqli_stmt_execute($refuseStmt);

            if ($success) {
                echo "Request deleted successfully.";
            } else {
                echo "Error deleting request.";
            }

            mysqli_stmt_close($refuseStmt);
        } else {
            echo "Error preparing delete statement.";
        }
    } elseif ($action == "accept") {
        // Update the 'etat' column for "accept" action
        $updateQuery = "UPDATE demandesupervision SET etat = 'accepted' WHERE id = ? AND id_prof = ?";
        $stmt = mysqli_prepare($db, $updateQuery);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ii", $requestId, $id);
            $success = mysqli_stmt_execute($stmt);

            if ($success) {
                echo "Request accepted successfully.";
            } else {
                echo "Error updating request.";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Error preparing accept statement.";
        }
    } else {
        echo "Invalid action.";
    }

    mysqli_close($db);
} else {
    echo "Invalid request.";
}
?>
