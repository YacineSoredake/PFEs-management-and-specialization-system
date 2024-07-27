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

// Query to fetch users
$query = "SELECT * FROM user";
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
    <link href="https://fonts.googleapis.com/css2?family=Aldrich&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/adminpanel/tables/etudient/etuidenttab.css">
    <title>Users</title>
</head>

<body>
<div class="body-container">
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
                <th>ID</th>
                <th>Email</th>
                <th>Password</th>
                <th>Role</th>
                <th>State</th>
                <th>Action</th>
            </tr>
            <?php
            // Fetch and display each row from the user table
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['password'] . "</td>";
                echo "<td>" . $row['role'] . "</td>";
                echo "<td>" . $row['state'] . "</td>";
                echo "<td>";
                // Print button
                echo "<a class='btn-s' href='share_user.php?id=" . $row['id'] . "'><img style='height: 20px;' src='/PFEs/figma/share.svg' alt=''></a>";

                // Edit button
                echo "<a class='btn-s' href='edit_user.php?id=" . $row['id'] . "'><img style='height: 20px;' src='/PFEs/figma/edit.svg' alt=''></a>";
                
                // Delete button
                echo "<button class='btn-s delete-btn' data-id='" . $row['id'] . "'><img style='height: 20px;' src='/PFEs/figma/dele.svg' alt=''></button>";
                echo "</td>";
                echo "</tr>";
            }
            ?> 
         </table>
    </div>
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', event => {
        // Prevent default button action
        event.preventDefault();

        // Get user ID from data attribute
        const userId = btn.getAttribute('data-id');

        // Send Ajax request to delete_user.php
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'disable_user.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Check response from disable_user.php
                if (xhr.responseText === 'success') {
                    // Remove table row if deactivation was successful
                    location.reload();
                } else {
                    // Display error message if deactivation failed
                    alert(xhr.responseText);
                }
            } else {
                // Display error message if request fails
                alert('Error: ' + xhr.status);
            }
        };
        xhr.send('id=' + userId); // Send user ID in the request body
    });
});
</script>
</body>
</html>
