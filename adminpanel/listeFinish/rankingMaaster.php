<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['usid'])) {
    // User is not logged in, redirect to the login page
    header("Location: /login/login.php");
    exit();
}

// Include database connection and PhpSpreadsheet autoload
include "../connectdb.php";
//path to your autoload.php file 
require '/Bureau/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Function to update or insert ranking for each student
function updateOrInsertRanking($db, $student_id, $ranking) {
    $query = "SELECT * FROM etudient WHERE id = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Student exists in the database, update ranking
        $update_query = "UPDATE etudient SET ranking = ? WHERE id = ?";
        $update_stmt = mysqli_prepare($db, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ii", $ranking, $student_id);
        mysqli_stmt_execute($update_stmt);
    } 
}

// Check if the refresh button is clicked
// Check if the refresh button is clicked
if (isset($_POST['refresh'])) {
    $excelFilePath = 'C:\Users\USER\OneDrive\Bureau\PFEs - Copie\excel\master_student_ranking.xlsx'; // Adjust this path

    // Load Excel file
    $spreadsheet = IOFactory::load($excelFilePath);
    $sheet = $spreadsheet->getActiveSheet();

    $firstRowSkipped = false; // Flag to skip the first row

    foreach ($sheet->getRowIterator() as $row) {
        if (!$firstRowSkipped) {
            $firstRowSkipped = true;
            continue; // Skip the first row
        }

        $rowData = [];
        foreach ($row->getCellIterator() as $cell) {
            $rowData[] = $cell->getValue();
        }

        // Check if the necessary array keys exist
        if (isset($rowData[0], $rowData[1])) {
            // Update or insert ranking for each student
            updateOrInsertRanking($db, $rowData[0], $rowData[1]);
        } else {
            // Handle missing array keys
            echo "Missing array keys in row data.";
        }
    }
}


// Query to fetch students with their rankings
$query = "SELECT id,nompre,ranking FROM etudient WHERE niveau = '2M'";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Aldrich&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="liste.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>List ESP</title>
</head>

<body>
<div class="body-container">
        <div class="menubar">
            <div>
                <img src="/PFEs/figma/MyChoice (1).svg" alt="">
            </div>
            <div>
                <nav>
                <a href="/adminpanel/homepage/admin.php">
                    <img style="height: 15px;" src="/PFEs/figma//homme.svg" alt="">
                        Dashboard
                    </a>
                    <a href="/adminpanel/listeFinish/ranking.php" style="color: #fdfdfd;">
                    <img style="height: 15px;" src="/PFEs/figma/rankuung.svg" alt="">
                        Rankings
                    </a>
                    <a href="/adminpanel/listeFinish/2lRanking.php">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                        2nd licence
                    </a>
                    <a href="/adminpanel/listeFinish/ranking.php" >
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                        3rd licence
                    </a>
                    <a href="/adminpanel/listeFinish/rankingMaaster.php" style="color: #fdfdfd;">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                        2nd master
                    </a>
                    <a href="/adminpanel/listeFinish/liste.php">
                    <img style="height: 15px;" src="/PFEs/figma/assss.svg" alt="">
                        Assign PFEs
                    </a>
                    <a href="/adminpanel/tables/user/userTab.php">
                    <img style="height: 15px;" src="/PFEs/figma/zazaz.svg" alt="">
                        Users
                    </a>
                    <a href="/adminpanel/homepage/setting/setting.php">
                        <img style="height: 15px;" src="/PFEs/figma/seting.svg" alt="">
                        Settings
                    </a>
                    <a style="border-top: 1px solid white; justify-self:flex-end; " href="/logouttoAdmin.php">
                    <img style="height: 15px;" src="/PFEs/figma/logg.svg" alt="">
                        Logout
                    </a>
                </nav>
            </div>
        </div>
    <div class="container">
    <header>
            <div class="profile">
                Admin
            </div>
    </header>

        <table>
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Ranking</th>
            </tr>
            <?php
            // Fetch and display each row from the etudient table
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nompre'] . "</td>";
                echo "<td>" . $row['ranking'] . "</td>";
                echo "</tr>";
            }
            ?> 
                <form method="post" action="">
                    <button class="assign-btn" type="submit" name="refresh">Refresh</button>
                </form>
        </table>
    </div>
</body>
</html>
