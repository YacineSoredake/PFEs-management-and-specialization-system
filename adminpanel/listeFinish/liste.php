<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['usid'])) {
    header("Location: /login/login.php");
    exit();
}

// Check if the user has admin privileges
include "C:\\Users\\USER\\OneDrive\\Bureau\\PFEs\\connectdb.php";

$user_id = $_SESSION['usid'];

// Verify admin
$query = "SELECT * FROM admins WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: /login/login.php");
    exit();
}

if (isset($_POST['start_assignment'])) {
    // Fetch all theme requests and student rankings
    $query = "SELECT d.id_theme, d.id_etudient, e.ranking, d.priority
              FROM demande d
              JOIN etudient e ON d.id_etudient = e.id
              WHERE e.niveau = '3L'
              ORDER BY e.ranking ASC, d.priority ASC";
    $result = mysqli_query($db, $query);

    $assignments = [];
    $themes = [];

    // Prepare data structures
    while ($row = mysqli_fetch_assoc($result)) {
        $student_id = $row['id_etudient'];
        $theme_id = $row['id_theme'];
        $preference_order = $row['priority'];

        if (!isset($assignments[$student_id])) {
            $assignments[$student_id] = [];
        }
        if (!isset($themes[$theme_id])) {
            $themes[$theme_id] = [];
        }

        $assignments[$student_id][$preference_order] = $theme_id;
        $themes[$theme_id][] = $student_id;
    }

    // Assign themes based on ranking and preferences
    $assigned = [];
    $unassigned = [];

    foreach ($assignments as $student_id => $preferences) {
        $assigned_flag = false;
        ksort($preferences); // Ensure preferences are processed in order

        foreach ($preferences as $preference_order => $theme_id) {
            if (!isset($assigned[$theme_id])) {
                $assigned[$theme_id] = $student_id;
                $assigned_flag = true;
                break;
            }
        }
        if (!$assigned_flag) {
            $unassigned[] = $student_id;
        }
    }

    // Handle unassigned students by assigning them to themes that are not yet assigned
    foreach ($unassigned as $student_id) {
        foreach ($themes as $theme_id => $students) {
            if (!isset($assigned[$theme_id])) {
                $assigned[$theme_id] = $student_id;
                break;
            }
        }
    }

    // Update database with assignments
    foreach ($assigned as $theme_id => $student_id) {
        $update_query = "UPDATE demande SET etat = 'assigned' WHERE id_theme = ? AND id_etudient = ?";
        $update_stmt = mysqli_prepare($db, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ii", $theme_id, $student_id);
        mysqli_stmt_execute($update_stmt);

        // Mark other requests of the student as refused
        $refuse_query = "UPDATE demande SET etat = 'Not assigned' WHERE id_etudient = ? AND id_theme != ?";
        $refuse_stmt = mysqli_prepare($db, $refuse_query);
        mysqli_stmt_bind_param($refuse_stmt, "ii", $student_id, $theme_id);
        mysqli_stmt_execute($refuse_stmt);
    }

    // Mark all other requests as 'Not assigned'
    $refuse_remaining_query = "UPDATE demande SET etat = 'Not assigned' WHERE etat IS NULL";
    mysqli_query($db, $refuse_remaining_query);
}

// Now fetch the final data to be displayed
$query = "SELECT d.id_theme, t.intitule, d.id_etudient, e.nompre, e.ranking, d.etat,d.priority
          FROM demande d
          JOIN theme t ON d.id_theme = t.id
          JOIN etudient e ON d.id_etudient = e.id
          WHERE e.niveau = '3L'";
$resultat = mysqli_query($db, $query);

if (!$resultat) {
    die("Query failed: " . mysqli_error($db));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="liste.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Aldrich&display=swap" rel="stylesheet">
    <title>List ESP</title>
</head>
<body>
<div class="body-container">
    <div class="menubar">
        <div>
            <img src="/PFEs/figma/MyChoice (1).svg" alt="">
        </div>
        <nav>
                    <a href="/adminpanel/homepage/admin.php">
                    <img style="height: 15px;" src="/PFEs/figma//homme.svg" alt="">
                        Dashboard
                    </a>
                    <a href="/adminpanel/listeFinish/ranking.php">
                    <img style="height: 15px;" src="/PFEs/figma/rankuung.svg" alt="">
                        Rankings
                    </a>
                    <a href="/adminpanel/listeFinish/liste.php" style="color: #fdfdfd;">
                    <img style="height: 15px;" src="/PFEs/figma/assss.svg" alt="">
                        Assign PFEs
                    </a>
                    <a href="/adminpanel/listeFinish/liste.php" style="color: #fdfdfd;">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                        3rd licence
                    </a>
                    <a href="/adminpanel/listeFinish/liste2.php">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                        2nd master
                    </a>
                    <a href="/adminpanel/L2choices/LtwoListe.php" >
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
    <div class="container">
        <header>
            <div class="profile">
                Admin
            </div>
        </header>

        <form style="align-self: center;" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <button class="assign-btn" type="submit" name="start_assignment">Start Assignment</button>
        </form>

        <table>
            <tr>
                <th>ID Theme</th>
                <th>Title</th>
                <th>ID student</th>
                <th>Student</th>
                <th>Ranking</th>
                <th>priority</th>
                <th>Affectation state</th>
                <th>option</th>
            </tr>
            <?php
            // Fetch and display each row from the demande table
            while ($row = mysqli_fetch_assoc($resultat)) {
                echo "<tr>";
                echo "<td>{$row['id_theme']}</td>";
                echo "<td>{$row['intitule']}</td>";
                echo "<td>{$row['id_etudient']}</td>";
                echo "<td>{$row['nompre']}</td>";
                echo "<td>{$row['ranking']}</td>";
                echo "<td>{$row['priority']}</td>";
                echo "<td>{$row['etat']}</td>";
                echo '<td><a style="color:black;" href="/adminpanel/listefinish/edit_PFEs_assigned.php?id_student=' . $row['id_etudient'] . '&id_theme=' . $row['id_theme'] . '">Edit</a></td>';
                echo "</tr>";
            }
            ?> 
        </table>
    </div>
</div>
</body>
</html>
