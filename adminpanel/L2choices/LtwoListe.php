<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['usid'])) {
    header("Location: /login/login.php");
    exit();
}

// Check if the user has admin privileges
include "C:/Users/USER/OneDrive/Bureau/PFEs/connectdb.php";

$user_id = $_SESSION['usid'];

$query = "SELECT * FROM admins WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: /login/login.php");
    exit();
}

$query_total = "SELECT COUNT(*) as totalNBR FROM etudient WHERE niveau = '2L'";
$stmt_total = mysqli_prepare($db, $query_total);
mysqli_stmt_execute($stmt_total);
$result_total = mysqli_stmt_get_result($stmt_total);
$total_row = mysqli_fetch_assoc($result_total);
$totalNBR = $total_row['totalNBR'];
// Fetch the total number of 2L students
$query_totalSI = "SELECT COUNT(*) as totalNBR FROM etudient WHERE niveau = '2L' and choix = 'si'";
$stmt_totalSI = mysqli_prepare($db, $query_totalSI);
mysqli_stmt_execute($stmt_totalSI);
$result_totalSI = mysqli_stmt_get_result($stmt_totalSI);
$totalSI_row = mysqli_fetch_assoc($result_totalSI);
$totalNBRSI = $totalSI_row['totalNBR'];

$totalNBRSI = ($totalNBRSI*100)/$totalNBR;

$queryISIL_total = "SELECT COUNT(*) as totalNBR FROM etudient WHERE niveau = '2L' and choix ='isil'";
$stmt_totalISIL = mysqli_prepare($db, $queryISIL_total);
mysqli_stmt_execute($stmt_totalISIL);
$result_totalISIL = mysqli_stmt_get_result($stmt_totalISIL);
$total_rowISIL = mysqli_fetch_assoc($result_totalISIL);
$totalNBRISIL = $total_rowISIL['totalNBR'];

$totalNBRISIL = ($totalNBRISIL*100)/$totalNBR;
// Initialize arrays to avoid undefined variable warnings
$si_students = [];
$isil_students = [];

if (isset($_POST['assign'])) {
    $max_si_percentage = $_POST['maxsi'];
    $max_isil_percentage = $_POST['maxisil'];

    // Calculate the number of slots based on the percentages
    $max_si = floor($totalNBR * ($max_si_percentage / 100));
    $max_isil = floor($totalNBR * ($max_isil_percentage / 100));

    $query_si = "SELECT id, nompre, ranking FROM etudient WHERE niveau = '2L' AND choix = 'si' ORDER BY ranking ASC";
    $stmt_si = mysqli_prepare($db, $query_si);
    mysqli_stmt_execute($stmt_si);
    $result_si = mysqli_stmt_get_result($stmt_si);
    
    $query_isil = "SELECT id, nompre, ranking FROM etudient WHERE niveau = '2L' AND choix = 'isil' ORDER BY ranking ASC";
    $stmt_isil = mysqli_prepare($db, $query_isil);
    mysqli_stmt_execute($stmt_isil);
    $result_isil = mysqli_stmt_get_result($stmt_isil);

    while ($row_si = mysqli_fetch_assoc($result_si)) {
        $si_students[] = $row_si;
    }

    while ($row_isil = mysqli_fetch_assoc($result_isil)) {
        $isil_students[] = $row_isil;
    }

    usort($si_students, function($a, $b) {
        return $a['ranking'] - $b['ranking'];
    });

    usort($isil_students, function($a, $b) {
        return $a['ranking'] - $b['ranking'];
    });

    if (count($si_students) > $max_si) {
        $diff = count($si_students) - $max_si;
        for ($i = 0; $i < $diff; $i++) {
            $student = array_pop($si_students);
            $student['choix'] = 'isil';
            array_unshift($isil_students, $student);
            $update_query = "UPDATE etudient SET choix = 'isil' WHERE id = ?";
            $update_stmt = mysqli_prepare($db, $update_query);
            mysqli_stmt_bind_param($update_stmt, "i", $student['id']);
            mysqli_stmt_execute($update_stmt);
        }   
    }

    if (count($isil_students) > $max_isil) {
        $diff = count($isil_students) - $max_isil;
        for ($i = 0; $i < $diff; $i++) {
            $student = array_pop($isil_students);
            $student['choix'] = 'si';
            array_unshift($si_students, $student);
            $update_query = "UPDATE etudient SET choix = 'si' WHERE id = ?";
            $update_stmt = mysqli_prepare($db, $update_query);
            mysqli_stmt_bind_param($update_stmt, "i", $student['id']);
            mysqli_stmt_execute($update_stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Aldrich&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/adminpanel/listeFinish/liste.css">
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
                    <a href="/adminpanel/listeFinish/ranking.php">
                    <img style="height: 15px;" src="/PFEs/figma/rankuung.svg" alt="">
                        Rankings
                    </a>
                    </a>
                    <a href="/adminpanel/listeFinish/2lRanking.php" >
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="" >
                        2nd licence
                    </a>
                    <a href="/adminpanel/listeFinish/liste.php">
                    <img style="height: 15px;" src="/PFEs/figma/assss.svg" alt="">
                        Assign PFEs
                    </a>
                    <a href="/adminpanel/L2choices/LtwoListe.php"  style="color: #fdfdfd;">
                    <img style="height: 15px;" src="/PFEs/figma/spec.svg" alt="">
                        Assign Specialties
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
        <h4 style="margin:0;border-radius:5px;border:1px solid rgb(31, 119, 72); padding:8px; background-color:#fdfdfd; gap:6px; color:rgb(31, 119, 72); align-self: center; text-align:center;align-items:center; ;display:flex">
            <img height="35px" src="/PFEs/figma/inf.svg" alt="">
            Total Students: <?php echo $totalNBR; ?>(
            ISIL : <?php echo $totalNBRISIL; ?>% /
            SI: <?php echo $totalNBRSI; ?>% )
        </h4>
        <form class="choice-form" method="post" action="">
            <label for="">Maximum per Specialty si</label>
            <input type="number" name="maxsi" value="100">%
            <label for="">Maximum per Specialty isil</label>
            <input type="number" name="maxisil" value="100">%
            <input name="assign" class="assign-btn" type="submit" value="assign">
        </form>
        <table>
            <thead>
                <tr>
                    <th>Number</th>
                    <th>Student</th>
                    <th>Ranking</th>
                    <th>Speciality</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rowNumber = 1;
                foreach ($si_students as $student) {
                    echo "<tr>";
                    echo "<td>" . $rowNumber++ . "</td>";
                    echo "<td>" . $student['nompre'] . "</td>";
                    echo "<td>" . $student['ranking'] . "</td>";
                    echo "<td>si</td>";
                    echo "</tr>";
                }
                foreach ($isil_students as $student) {
                    echo "<tr>";
                    echo "<td>" . $rowNumber++ . "</td>";
                    echo "<td>" . $student['nompre'] . "</td>";
                    echo "<td>" . $student['ranking'] . "</td>";
                    echo "<td>isil</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
