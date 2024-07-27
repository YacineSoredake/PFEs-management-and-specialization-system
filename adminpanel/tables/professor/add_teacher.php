<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['usid'])) {
    // User is not logged in, redirect to the login page
    header("Location: /login/login.php");
    exit();
}

// Check if the user has admin privileges
include "C:\\Users\\USER\\OneDrive\\Bureau\\PFEs\\connectdb.php";

$user_id = $_SESSION['usid'];

$query = "SELECT * FROM admins WHERE id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize the input data
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $speciality = mysqli_real_escape_string($db, $_POST['specialite']);
    $rank = mysqli_real_escape_string($db, $_POST['rank']);
    $dob = mysqli_real_escape_string($db, $_POST['DOB']);
    $user_id = mysqli_real_escape_string($db, $_POST['user_id']);
    
    // File upload
    $photo = $_FILES['fileInput']['name'];
    $uploadDirectory = "/backend/image/";

    // Move the uploaded file to the destination directory
    if (move_uploaded_file($_FILES["fileInput"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $uploadDirectory . $_FILES["fileInput"]["name"])) {
        // File uploaded successfully
    } else {
        // Error uploading file
        echo "Failed to upload file.";
    }

    // Prepare the INSERT query for the prof table
    $query = "INSERT INTO prof (nomprenom, specialite, grade, dateNaissance, id_user, image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "ssssis", $name, $speciality, $rank, $dob, $user_id, $photo);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // Insertion successful
        header("location:professorTab.php");
        exit();
    } else {
        // Insertion failed
        echo "Failed to insert data into prof table";
    }

    mysqli_stmt_close($stmt);
}

// Fetch user IDs that do not exist in the prof or etudient tables
$query = "SELECT id FROM user WHERE id NOT IN (SELECT id_user FROM prof UNION SELECT id_user FROM etudient)";
$result = mysqli_query($db, $query);

// Initialize an array to store user IDs
$filteredUserIds = array();

// Check if query was successful
if ($result) {
    // Fetch each row and store the filtered user IDs
    while ($row = mysqli_fetch_assoc($result)) {
        $filteredUserIds[] = $row['id'];
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
                
                <a href="/adminpanel/tables/etudient/etudientTab.php" >
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                    Students
                </a>
               
                <a href="/adminpanel/tables/professor/professorTab.php" >
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg"alt="">
                    Supervisors
                </a>
                <a href="/adminpanel/tables/professor/add_teacher.php" style="color: #fdfdfd;">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                    Create new supervisor
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
        <form class="edit-form" action="" method="post" enctype="multipart/form-data">
            <div class="image-form">
                <img id="image-preview" src="#" alt="Image Preview" class="preview-img">   
                <input type="file" name="fileInput" id="file" class="inputfile" onchange="displayImagePreview()" />
                <label for="file" class="label-file">Add picture</label>
            </div>
            <div>
                <label for="">Full name :</label>
                <input placeholder="ex:Blaha Yacine" class="input-ep" type="text" name="name" id="">
            </div>
            <div>
                <label for="">Date of birth :</label>
                <input class="input-ep" type="date" name="DOB" id="">
            </div>
            <div>
                <label for="">Rank:</label>
                <select class="input-ep" name="rank">
                    <option value="Professor">Professor</option>
                    <option value="assistant">assistant</option>
                    <option value="MAA">MAA</option>
                    <option value="MAB">MAC</option>
                    <option value="MCB">MCB</option>
                    <option value="MCA">MCA</option>
                </select>
            </div>
            <div>
                <label for="">Speciality :</label>
                <select class="input-ep" name="specialite" id="specialite">
                    <option value="mathematic">mathemathic</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="other">other</option>
                </select>
            </div>
            <div>
                <label for="">id_user :</label>
                <select class="input-ep" name="user_id" id="">
                    <?php
                    // Iterate over the filtered user IDs and create an option for each
                    foreach ($filteredUserIds as $userId) {
                        echo "<option value=\"$userId\">$userId</option>";
                    }
                    ?>
                </select>
            </div>
            <input class="btn-sub" type="submit" value="ADD">
        </form>
    </div>
</div>

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
