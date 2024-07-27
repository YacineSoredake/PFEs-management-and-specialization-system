<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['usid'])) {
    header("Location: /login/login.php");
    exit();
}

// Include database connection
include "C:/Users/USER/OneDrive/Bureau/PFEs/connectdb.php";

// Retrieve the IDs from the URL
if (isset($_GET['id_student']) && isset($_GET['id_theme'])) {
    $id_student = $_GET['id_student'];
    $id_theme = $_GET['id_theme'];

    // Fetch the data from the demande table
    $query = "SELECT * FROM demande d JOIN etudient e ON d.id_etudient = e.id JOIN theme t ON d.id_theme = t.id WHERE d.id_etudient = ? AND d.id_theme = ?";
    $stmt = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($stmt, "ii", $id_student, $id_theme);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the updated data from the form
        $new_field1 = $_POST['id_etudient'];
        $new_field2 = $_POST['id_theme'];
        $new_field3 = $_POST['priority'];
        $new_field4 = $_POST['etat'];
        // Add more fields as needed

        // Update the data in the database
        $update_query = "UPDATE demande SET id_etudient = ?, id_theme = ? , priority = ? , etat = ? WHERE id_etudient = ? and id_theme= ?";
        $update_stmt = mysqli_prepare($db, $update_query);
        mysqli_stmt_bind_param($update_stmt, "iiisii", $new_field1, $new_field2,$new_field3,$new_field4, $id_student, $id_theme);
        mysqli_stmt_execute($update_stmt);

        // Redirect back to the previous page
        header("Location: /adminpanel/listefinish/liste.php");
        exit();
    }
} else {
    // Redirect back if IDs are not set
    header("Location: /adminpanel/listefinish/liste.php");
    exit();
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
    <h1 style="align-self: center;">Edit PFE Assignment</h1>
    <form method="post" action="" class="choice-form" style="gap:5px;flex-direction: column; width:500px;">
        <label for="field1">id student:</label>
        <input type="text" name="id_etudient" value="<?php echo htmlspecialchars($row['id_etudient']); ?>" required readonly>
        
        <label for="field2">id theme :</label>
        <input type="text" name="id_theme" value="<?php echo htmlspecialchars($row['id_theme']); ?>" required readonly>
        <textarea style="width: 300px;"  name="" id=""><?php echo htmlspecialchars($row['nompre']); ?></textarea>
        <textarea style="width: 300px;"  name="" id="" rows="5"><?php echo htmlspecialchars($row['intitule']); ?></textarea>
        <label for="field1">priority:</label>
        <input type="text" name="priority" value="<?php echo htmlspecialchars($row['priority']); ?>" required>
       
        <label for="field2">etat:</label>
        <?php
        if (!empty($row['etat'])) {
        $selected_options = explode(',', $row['etat']);
          }?>
        <select name="etat">
            <option value="On hold" <?php if (isset($selected_options) && in_array('On hold', $selected_options)) echo 'selected'; ?>>On hold</option>
            <option value="assigned" <?php if (isset($selected_options) && in_array('assigned', $selected_options)) echo 'selected'; ?>>assigned</option>
            <option value="Not assigned" <?php if (isset($selected_options) && in_array('Not assigned', $selected_options)) echo 'selected'; ?>>Not assigned</option>
        </select>
        <!-- Add more fields as needed -->
        <input class="assign-btn" type="submit" value="Update">
    </form>
</body>
</html>
