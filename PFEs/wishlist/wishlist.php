<?php 
session_start();
include "C:/Users/USER/OneDrive/Bureau/PFEs/connectdb.php";
$user_id = $_SESSION['user_id'];
$partner_id = $_SESSION['partner'];

$query = "SELECT d.*, t.intitule, t.summary, t.keyword, p.nomprenom
        FROM demande d
        JOIN theme t ON d.id_theme = t.id
        JOIN etudient e ON d.id_etudient = e.id
        JOIN prof p ON t.id_prof = p.id
        WHERE d.id_etudient = ? OR d.id_etudient = ?
        ORDER BY d.priority ASC";

$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "ii", $user_id, $partner_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo "Error: " . mysqli_error($db);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link rel="stylesheet" href="./wishlist.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="preload" href="/PFEs/figma/pexels-yungsaac-1557251.jpg" as="image">
</head>
<body>
    <header>
       <img src="/PFEs/figma/MyChoice (1).svg" alt="">
       <div class="profile">
       <img class="chap" src="/backend/image/<?php echo $_SESSION['user_image'];?>" alt="">
        <?php echo $_SESSION['user_nompre'];?>
       </div>
    </header>
    <div class="body-container">
        <div class="menubar">
            <div class="afficher">
                open menu
            </div>
            <div>
            <nav>
                    <a href="/PFEs/profil/profil.php">
                        <img style="height: 15px;" src="/PFEs/figma/profillle.svg" alt="">
                        My profil
                    </a>
                    <a href="/PFEs/wishlist/wishlist.php">
                     <img style="height: 15px;" src="/PFEs/figma/wish.svg" alt="">
                        My Wishlist
                    </a>
                    <a href="/PFEs/wishlist/affectation.php">
                     <img style="height: 15px;" src="/PFEs/figma/techhher.svg" alt="">
                        Affectation
                    </a>
                    <a href="/PFEs/listeDesEncadreur/teachers_list.php">
                     <img style="height: 15px;" src="/PFEs/figma/techhher.svg" alt="">
                        Find a supervisor
                    </a>
                    <a href="/PFEs/liste-sujets/liste-sujets.php">
                    <img style="height: 15px;" src="/PFEs/figma/thesus.svg" alt="">
                        Themes available
                    </a>
                    <a href="/PFEs/homepage/homePage.php">
                    <img style="height: 15px;" src="/PFEs/figma//homme.svg" alt="">
                        Home
                    </a>
                    <a style="border-top: 1px solid white; justify-self:flex-end; " href="/logout.php">
                    <img style="height: 15px;" src="/PFEs/figma/logg.svg" alt="">
                        Logout
                    </a>
                </nav>
            </div>
        </div>
        <div class="container">
        <div class="btn-container">
            <a class="link-par-theme" href="./addPartner.php"><img height="30px" src="/PFEs/figma/adds.svg" alt="">My partner</a>
            <a class="link-par-theme" href="/PFEs/liste-sujets/liste-sujets.php"><img height="30px" src="/PFEs/figma/adds.svg" alt="">Add theme</a>
        </div>

        <h1 style="margin: 0;">
            My wishlist
        </h1>
        <table>
            <tr>
                <th>Title</th>
                <th>Summary</th>
                <th>key-word</th>
                <th>supervisor</th>
                <th>priority</th>
                <th>State</th>
                <th>option</th>
            </tr>
            <?php
// Fetch and display each row from the etudient table
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr id='$row[id_theme]'>";
    echo "<td>" . $row['intitule'] . "</td>";
    echo "<td>" . $row['summary'] . "</td>";
    echo "<td>" . $row['keyword'] . "</td>";
    echo "<td>" . $row['nomprenom'] . "</td>";
    echo "<td>" . $row['priority'] . "</td>";
    echo "<td>" . $row['etat'] . "</td>";
    echo "<td>";
    // Check if etat is not 'on hold', then add 'disabled' attribute to the button
    if ($row['etat'] != 'On hold') {
        if (trim($row['etat']) == 'assigned') {
            echo '<a href="./affectation.php" style="color:green;">Result</a>';
        }
    } else {
        echo "<button class='delete-btn' data-id='" . $row['id_theme'] . "' onclick='deleteItem(this)'><img style='height: 25px;' src='/PFEs/figma/dede.svg'>Delete</button>";
    }
    echo "</td>";
    echo "</tr>";
}
?> 
        </table>

    </div>
    </div>
   
    <footer>
    &#169; Gestion des PFEs et choix (isil/si)
    </footer>

    <script>

function deleteItem(button) {
    var itemId = button.getAttribute('data-id');
    var rowId = button.closest('tr').getAttribute('id');

    // Send AJAX request to delete the item
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'delete_item.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                // Remove the row from the table if deletion was successful
                document.getElementById(rowId).remove();
            } else {
                alert('Failed to delete item: ' + response.message);
            }
        } else {
            alert('Failed to delete item: Server Error');
        }
    };
    xhr.send('item_id=' + encodeURIComponent(itemId));
}
</script>

</body>
</html>
