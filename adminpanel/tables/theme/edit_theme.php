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

// get the id and update the user

$id = $_GET['id'];

$requete = "SELECT * FROM theme WHERE id = ?";
$stmt = $db->prepare($requete);
$stmt->bind_param('i', $id);
$stmt->execute();
$resultat = $stmt->get_result();

// display the result in table format
if ($resultat->num_rows > 0) {
    $rows = $resultat->fetch_assoc();
} 

// update query
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the required parameters are set in the POST request
        // Sanitize input data
        $newname = mysqli_real_escape_string($db, $_POST['name']);
        $newlevel = mysqli_real_escape_string($db, $_POST['level']);
        $newuser_id = mysqli_real_escape_string($db, $_POST['user_id']);


        // Get the user ID from the session
        $id = $_GET['id'];

        // Prepare the UPDATE query to update user details
        $query = "UPDATE theme SET intitule = ?, niveau = ?, id_prof = ? WHERE id = ?";
        $stmt = mysqli_prepare($db, $query);

        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "ssii", $newname, $newlevel, $newuser_id,$id);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            header("location:/adminpanel/tables/theme/themeTab.php");
            exit();
        } else {
            // Update failed
            echo "Failed to update user details";
        }

        mysqli_stmt_close($stmt);
}

$query = "SELECT id FROM prof";
$result = mysqli_query($db, $query);

// Initialize an array to store user IDs
$userIds = array();

// Check if query was successful
if ($result) {
    // Fetch each row and store the user IDs
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['id'] != $rows['id_prof']) {
            // Add the ID to the array
            $userIds[] = $row['id'];
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

   <div class="for-form-section">
   <h2>Theme Form</h2>
   <a class="return" href="/adminpanel/tables/theme/themeTab.php">
    <img class="return-icon" src="/adminpanel/adminpic/return-svgrepo-com.svg" alt="">
   </a>
   <form class="student-form" action="" method="post" enctype="multipart/form-data">

        <div class="entete">
        <label for=""> ID :</label>
        <input class="input-ep-rd-id"type="text" name="id" id="id" value="<?php echo $rows['id']?>" readonly>

        <label for=""> Added :</label>
        <input class="input-ep-rd" type="text" value="<?php echo $rows['creation']?>" readonly>    
        </div>
        <div>
        <label for="">Theme title :</label>
        <input value="<?php echo $rows['intitule']?>" class="input-ep"type="text" name="name" id="">
        </div>

        <div>
        <?php
        if (!empty($rows['niveau'])) {
        $selected_options = explode(',', $rows['niveau']);
          }?>
        <label for="">Level:</label>
        <select class="input-ep" name="level">
            <option value="3L" <?php if (isset($selected_options) && in_array('3L', $selected_options)) echo 'selected'; ?>>3 License</option>
            <option value="2M" <?php if (isset($selected_options) && in_array('2M', $selected_options)) echo 'selected'; ?>>2 Master</option>
        </select>
        </div>

        <div>
        <label for="">id teacher :</label>
        <select class="input-ep" name="user_id" id="">
                 <?php
                 $idp=$rows['id_prof'];
                echo "<option value=\"$idp\">$idp</option>";
                foreach ($userIds as $userId) {
                    echo "<option value=\"$userId\">$userId</option>";
                }
                ?>
        </select>
        </div>
        <input class="btn-sub" type="submit" value="Update">
   </form>
   </div>

    <footer>
        &#169; Gestion des PFEs et choix (isil/si)
    </footer>

</body>

<script>
    

        

function displayImagePreview() {
    // Get the file input element
    var fileInput = document.getElementById('file');

    // Get the image preview element
    var imagePreview = document.getElementById('image-preview');

    // Check if a file is selected
    if (fileInput.files.length > 0) {
        // Create a FileReader
        var reader = new FileReader();

        // Set a callback function to handle the file reading
        reader.onload = function (e) {
            // Set the source of the image preview to the data URL
            imagePreview.src = e.target.result;

            // Display the image preview
            imagePreview.style.display = 'block';
        };

        // Read the selected file as a data URL
        reader.readAsDataURL(fileInput.files[0]);
    } else {
        // No file selected, hide the image preview
        imagePreview.style.display = 'none';
    }
}
</script>


</html>