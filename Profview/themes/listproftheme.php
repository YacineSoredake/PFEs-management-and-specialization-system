<?php
session_start();
include "C:\Users\USER\OneDrive\Bureau\PFEs\connectdb.php";

$id = $_SESSION['prof_id'];

$query = "SELECT * FROM theme WHERE id_prof = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

//add new theme//

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new-theme'])) {
    $newThemeTitle = $_POST['new-theme'];
    $levelTitle = $_POST['level'];
    $newsum = $_POST['summary'];
    $newkey = $_POST['keyword'];

    // Insert the new theme into the database
    $insertQuery = "INSERT INTO theme (id_prof, intitule,niveau,summary,keyword) VALUES (?, ?,?,?,?)";
    $insertStmt = mysqli_prepare($db, $insertQuery);
    mysqli_stmt_bind_param($insertStmt, "issss", $id, $newThemeTitle,$levelTitle,$newsum,$newkey);

    if (mysqli_stmt_execute($insertStmt)) {
        // Theme added successfully
        $response = array("status" => "success");
        echo json_encode($response);
        exit();
    } else {
        // Handle the insertion failure
        $response = array("status" => "error", "message" => "Failed to add new theme. Please try again.");
        echo json_encode($response);
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>request received</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./theme.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    
</head>
<body>
<header>
        <div class="right-header">
        <img src="/PFEs/figma/MyChoice (1).svg" alt="">
        </div>
        <div class="right-header">
            <div class="profile">
                <div class="chap">
                <img class="chap" src="/backend/image/<?php echo $_SESSION['prof_image']; ?>" alt="">
                </div> 
                <?php echo $_SESSION['prof_name']; ?>
            </div>
           
        </div>
     </header>
<div class="body-container">
        <div class="menubar">
            <div>
                <nav>
                    <a href="/Profview/profile/profile.php">
                        <img style="height: 15px;" src="/PFEs/figma/profillle.svg" alt="">
                        My profil
                    </a>
                    <a href="/Profview/themes/listproftheme.php">
                    <img style="height: 15px;" src="/PFEs/figma/thesus.svg" alt="">
                        My Themes
                    </a>
                    <a href="/Profview/requestReceived/reqsup.php">
                    <img style="height: 15px;" src="/PFEs/figma//homme.svg" alt="">
                        Request
                    </a>
                    <a href="/Profview/homepage/homepage.php">
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
    <form id="addThemeForm" method="post" action="">
        <label for="new-theme">Theme title :</label>
        <input class="iii" type="text" name="new-theme" required>
        <label for="new-theme">Summary :</label>
        <textarea class="iii" name="summary" required rows="2" cols="40s"></textarea>
        <label for="new-theme">Key-words :</label>
        <textarea class="iii" name="keyword" required rows="3" cols="5"></textarea>
        <label for="new-theme">Level :</label>
        <select class="iii" name="level" required>
            <option value="3L">3 Licence</option>
            <option value="2M">2 Master</option>
        </select>
        <input type="submit" value="Add new theme" class="btn-g">
    </form>

    <h3>
     My themes: 
    </h3>
    
    <table>
    <tr>
        <th>Theme</th>
        <th>Summary</th>
        <th>Key-words</th>
        <th>Level</th>
        <th>Action</th>
    </tr>
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <tr id="theme_<?php echo $row['id']; ?>">
        <td><?php echo $row['intitule']; ?> </td>
        <td><?php echo $row['summary']; ?></td>
        <td><?php echo $row['keyword']; ?></td>
        <td><?php echo $row['niveau']; ?></td>
        <td><button class="btn-r" onclick="deleteTheme(<?php echo $row['id']; ?>)">Delete</button></td>
    </tr>
    <?php
    }
    ?>
</table>

    </div>
</div>


    <footer>
    &#169; Gestion des PFEs et choix (isil/si)
    </footer>
</body>
<script>
    $(document).ready(function() {
    $("#addThemeForm").submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Submit the form using AJAX
        $.ajax({
            type: "POST",
            url: "listproftheme.php", // Specify the correct path to your PHP file
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    // Refresh the page on success
                    location.reload();
                } else {
                    // Display an error message
                    alert(response.message);
                }
            },
            error: function() {
                alert("An error occurred while processing the request.");
            }
        });
    });
});



    function deleteTheme(themeId) {
            $.ajax({
                type: "POST",
                url: "delete_theme.php",
                data: { theme_id: themeId },
                success: function(response) {
                    if (response === "success") {
                        // Remove the theme container from the page
                        $("#theme_" + themeId).remove();
                    } else {
                        alert("Failed to delete theme. Please try again.");
                    }
                },
                error: function() {
                    alert("An error occurred while processing the request.");
                }
            });
    }
</script>

</html>
