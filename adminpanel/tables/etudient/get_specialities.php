<?php
// Include the database connection file
include "C:\Users\USER\OneDrive\Bureau\PFEs\connectdb.php";

// Check if the level parameter is set
if(isset($_GET['level'])) {
    // Sanitize the level parameter
    $level = mysqli_real_escape_string($db, $_GET['level']);

    // Query to fetch specialities based on the selected level
    $query = "SELECT specialite FROM etudient WHERE level = '$level'";
    $result = mysqli_query($db, $query);

    // Check if query was successful
    if($result) {
        // Initialize an empty array to store specialities
        $specialities = array();

        // Fetch each row and store the specialities
        while($row = mysqli_fetch_assoc($result)) {
            $specialities[] = $row['speciality'];
        }

        // Return JSON response
        echo json_encode($specialities);
    } else {
        // Error handling
        echo json_encode(array('error' => 'Failed to fetch specialities'));
    }
} else {
    // Error handling
    echo json_encode(array('error' => 'Level parameter is missing'));
}
?>
