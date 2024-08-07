<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['usid'])) {
    // User is not logged in, redirect to the login page
    header("Location: /login/login.php");
    exit();
}

include "../connectdb.php";

$user_id = $_SESSION['usid'];

$query = "SELECT * FROM admins WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$query = "SELECT * FROM etudient";
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
    <link rel="stylesheet" href="./etuidenttab.css">
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
                
                <a href="/adminpanel/tables/etudient/etudientTab.php" style="color: #fdfdfd;">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                    Students
                </a>
                <a href="/adminpanel/tables/etudient/add_etudient.php">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                    Create new student
                </a>
                <a href="/adminpanel/tables/professor/professorTab.php">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg"alt="">
                    Supervisors
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
                    <th>image</th>
                    <th>Full Name</th>
                    <th>Date of Birth</th>
                    <th>Level</th>
                    <th>Speciality</th>
                    <th>User id</th>
                    <th>action</th>
                </tr>
                <?php
                // Fetch and display each row from the etudient table
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td><img class='icon' src='/backend/image/" . $row['image'] . "' alt='-'></td>";
                    echo "<td>" . $row['nompre'] . "</td>";
                    echo "<td>" . $row['datenaissance'] . "</td>";
                    echo "<td>" . $row['niveau'] . "</td>";
                    echo "<td>" . $row['specialite'] . "</td>"; 
                    echo "<td>" . $row['id_user'] . "</td>";
                    echo "<td>";
                        // Edit button
                        echo "<a class='btn-s' href='edit_etudient.php?id=" . $row['id'] . "'><img style='height: 20px;' src='/PFEs/figma/edit.svg' alt=''></a>";

                        // Delete button
                        echo "<button class='btn-s delete-btn' data-id='" . $row['id'] . "'><img style='height: 20px;' src='/PFEs/figma/dele.svg' alt=''></button>";
                        echo "</td>";
                    echo "</tr>";
                }
                ?>  
         </table>
   </div>

</div>
    <script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', event => {
        // Get user ID from data attribute
        const userId = event.target.getAttribute('data-id');

        // Send Ajax request to delete_user.php
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_etudient.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
            if (xhr.status === 200) {
                // Check response from delete_user.php
                if (xhr.responseText === 'success') {
                    // Remove table row if deletion was successful
                    event.target.closest('tr').remove();
                    // Reload the page after successful deletion
                    location.reload();
                } else {
                    // Display error message if deletion failed
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