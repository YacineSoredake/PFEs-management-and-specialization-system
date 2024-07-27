<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['usid'])) {
    // User is not logged in, redirect to the login page
    header("Location: /login/login.php");
    exit();
}

// Check if the user has admin privileges
include "C:\Users\USER\OneDrive\Bureau\PFEs\connectdb.php";

$user_id = $_SESSION['usid'];

$query = "SELECT * FROM admins WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$query = "SELECT *,p.nomprenom AS prof_name ,t.id AS id_theme
            FROM theme t
            JOIN prof p ON t.id_prof = p.id";
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
    <link rel="stylesheet" href="/adminpanel/tables/etudient/etuidenttab.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>List ESP</title>
</head>

<body>
    <header>
        <img src="/PFEs/figma/MyChoice (1).svg" alt="">
        <div class="right-header">
            <a href="/logout.php">Logout</a>
            <div class="profile">
                <div class="chap">
                </div>
                Admin
            </div>
        </div>
    </header>


    <div class="sidebar">
        <a href="/adminpanel/homepage/admin.php">
            <div class="border">
                Dashboard
            </div>
        </a>
        <a href="/adminpanel/crud/Tables.php">
            <div class="border">
                Manage database
            </div>
        </a>
        <a href="/adminpanel/L2choices/LtwoListe.php">
        <div class="border">
             L2 students choice
        </div>
        </a>
        <a href="/adminpanel/listeFinish/liste.php">
        <div class="border">
            list PFEs 
        </div>
        </a>  
    </div>


    <div class="sidebar-mini">
    <a class="mini-link" href="/adminpanel/tables/user/userTab.php">User table</a>
    <a class="mini-link" href="/adminpanel/tables/etudient/etudientTab.php">student table</a>
    <a class="mini-link" href="/adminpanel/tables/professor/professorTab.php">Teacher table</a>
    <a class="mini-link" href="/adminpanel/tables/theme/themeTab.php">Theme table</a>
    </div>

   <div class="section">
   <h2>theme table</h2>
   <a class="add-btn" href="add_theme.php">ADD</a>
   <table>
                <tr>
                    <th>ID</th>
                    <th>Theme</th>
                    <th>Level</th>
                    <th>Provided by</th>
                    <th>Action</th>
                </tr>
                <?php
                // Fetch and display each row from the etudient table
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['id_theme'] . "</td>";
                    echo "<td>" . $row['intitule'] . "</td>";
                    echo "<td>" . $row['niveau'] . "</td>";
                    echo "<td>" . $row['prof_name'] . "</td>";
                    echo "<td>";
                        // Edit button
                        echo "<a class='btn-edit' href='edit_theme.php?id=" . $row['id_theme'] . "'>Edit</a>";
                        echo " | ";
                        // Delete button
                        echo "<button class='delete-btn' data-id='" . $row['id_theme'] . "'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>  
         </table>
   </div>

    <footer>
        &#169; Gestion des PFEs et choix (isil/si)
    </footer>
    <script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', event => {
        location.reload();
        // Get user ID from data attribute
        const userId = event.target.getAttribute('data-id');

        // Send Ajax request to delete_user.php
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_themer.php', true);
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