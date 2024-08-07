<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['usid'])) {
    // User is not logged in, redirect to the login page
    header("Location: /login/login.php");
    exit();
}

// Check if the user has admin privileges
include "../connectdb.php";

$user_id = $_SESSION['usid'];

$query = "SELECT * FROM admins WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// get the id and update the user

$id = $_GET['id'];

$requete = "SELECT * FROM user WHERE id = ?";
$stmt = $db->prepare($requete);
$stmt->bind_param('i', $id);
$stmt->execute();
$resultat = $stmt->get_result();
$message='';
// display the result in table format
if ($resultat->num_rows > 0) {
    $row = $resultat->fetch_assoc();
} 

// update query
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'email' and 'password' parameters are set in the POST request
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input datas
        $newEmail = mysqli_real_escape_string($db, $_POST['email']);
        $newPassword = mysqli_real_escape_string($db, $_POST['password']);
        $newRole = mysqli_real_escape_string($db, $_POST['role']);

        // Check if the email already exists in the database, excluding the current user
        $emailCheckQuery = "SELECT id FROM user WHERE email = ? AND id != ?";
        $emailCheckStmt = mysqli_prepare($db, $emailCheckQuery);
        mysqli_stmt_bind_param($emailCheckStmt, "si", $newEmail, $id);
        mysqli_stmt_execute($emailCheckStmt);
        $emailCheckResult = mysqli_stmt_get_result($emailCheckStmt);

        if (mysqli_num_rows($emailCheckResult) > 0) {
            $message = "The email address is already in use.";
        } else {

            // Prepare the UPDATE query to update email, password, and role for the user
            $query = "UPDATE user SET email = ?, password = ?, role = ? WHERE id = ?";
            $stmt = mysqli_prepare($db, $query);

            // Bind the parameters
            mysqli_stmt_bind_param($stmt, "sssi", $newEmail, $newPassword, $newRole, $id);

            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                // Update successful
                header("Location: /adminpanel/tables/user/userTab.php");
                exit();
            } else {
                // Update failed
                echo "Failed to update user details.";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($emailCheckStmt);
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Aldrich&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/adminpanel/tables/etudient/etuidenttab.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>List ESP</title>
</head>

<body>
<div class="body-container" style="height: 100vh;">
        <div class="menubar">
            <div>
                <img src="/PFEs/figma/MyChoice (1).svg" alt="">
            </div>
            <div>
                <nav>
                <a href="/adminpanel/homepage/admin.php" >
                    <img style="height: 15px;" src="/PFEs/figma//homme.svg" alt="">
                        Dashboard
                    </a>
                    <a href="/adminpanel/listeFinish/ranking.php">
                    <img style="height: 15px;" src="/PFEs/figma/rankuung.svg" alt="">
                        Rankings
                    </a>
                    <a href="/adminpanel/listeFinish/liste.php">
                    <img style="height: 15px;" src="/PFEs/figma/assss.svg" alt="">
                        Assign PFEs
                    </a>
                    <a href="/adminpanel/L2choices/LtwoListe.php">
                    <img style="height: 15px;" src="/PFEs/figma/spec.svg" alt="">
                        Assign Specialties
                    </a>
                    <a href="/adminpanel/tables/user/userTab.php" style="color: #fdfdfd;">
                    <img style="height: 15px;" src="/PFEs/figma/zazaz.svg" alt="">
                        Users
                    </a>
                <a href="/adminpanel/tables/user/add_user.php">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                    Create new user
                </a>
                <a href="/adminpanel/tables/etudient/etudientTab.php">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                    Students
                </a>
                <a href="/adminpanel/tables/professor/professorTab.php">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg"alt="">
                    Supervisors
                </a>
                <a style="border-top: 1px solid white; justify-self:flex-end;" href="/logout.php">
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
   <form class="edit-form" action="" method="post">
        <h5> <?php echo $message ?></h5>
        <div>
        <label for=""> Signed up :</label>
        <input class="input-ep-rd" type="text" value="<?php echo $row['creation']?>" readonly>    
        </div>
        <div>
        <label for=""> ID :</label>
        <input class="input-ep-rd"type="text" name="" id="" value="<?php echo $row['id']?>" readonly>
        </div>
        <div>
        <label for="">State :</label>
        <input class="input-ep-rd"type="text" name="state" id="" value="<?php echo $row['state']?>" readonly>
        </div>
        <div>
        <label for="">Email :</label>
        <input class="input-ep"type="text" name="email" id="" value="<?php echo $row['email']?>">
        </div>
        <div>
        <label for="">Password :</label>
        <input class="input-ep"type="text" name="password" id="" value="<?php echo $row['password']?>">
        </div>

        <?php
        if (!empty($row['role'])) {
        $selected_options = explode(',', $row['role']);
          }?>

        <div>
        <label for="">Role :</label>
        <select class="input-ep" name="role">
            <option value="student" <?php if (isset($selected_options) && in_array('student', $selected_options)) echo 'selected'; ?>>student</option>
            <option value="teacher" <?php if (isset($selected_options) && in_array('teacher', $selected_options)) echo 'selected'; ?>>teacher</option>
        </select>
        </div>
        
        <input class="btn-sub" type="submit" value="Update">
   </form>

    </div>
</div>

</body>
</html>
