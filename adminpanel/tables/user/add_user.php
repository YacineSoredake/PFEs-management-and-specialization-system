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



// update query
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'email' and 'password' parameters are set in the POST request
    if (isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input data
        $newEmail = mysqli_real_escape_string($db, $_POST['email']);
        $newPassword = mysqli_real_escape_string($db, $_POST['password']);
        $role = mysqli_real_escape_string($db, $_POST['role']);

        // Prepare the UPDATE query to update email and password for the user
        $query = "INSERT user(email,password,role) VALUES (?,?,?)";
        $stmt = mysqli_prepare($db, $query);

        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "sss", $newEmail, $newPassword,$role);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            // Update successful
            header("location:/adminpanel/tables/user/userTab.php");
        } else {
            // Update failed
            echo "Failed to update user details";
        }

        mysqli_stmt_close($stmt);
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
                <a href="/adminpanel/tables/user/add_user.php" style="color: #fdfdfd;">
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
        <div>
        <label for="">Email :</label>
        <input placeholder="ec@example.com" class="input-ep"type="text" name="email" id="" >
        </div>
        <div>
        <label for="">Password :</label>
        <input  placeholder="------" class="input-ep"type="text" name="password" id="">
        </div>
        <div>
        <label for="">Role :</label>
        <select class="input-ep" name="role">
            <option value="student">student</option>
            <option value="teacher">teacher</option>
        </select>
        </div>
        
        <input class="btn-sub" type="submit" value="ADD">
   </form>
    </div>
</div>


</body>

</html>