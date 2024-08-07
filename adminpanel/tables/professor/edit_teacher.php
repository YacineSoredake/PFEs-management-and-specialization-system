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

$requete = "SELECT * FROM prof WHERE id = ?";
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
    $newspeciality = mysqli_real_escape_string($db, $_POST['specialite']);
    $newrank = mysqli_real_escape_string($db, $_POST['level']);
    $newdob = mysqli_real_escape_string($db, $_POST['DOB']);
    $user_id = mysqli_real_escape_string($db, $_POST['user_id']);
    
    // Check if a new photo is uploaded
    if (isset($_FILES['fileInput']['name']) && $_FILES['fileInput']['name'] != '') {
        // Handle photo upload
        $newphoto = $_FILES['fileInput']['name'];
        $uploadDirectory = "/backend/image/";

        // Move the uploaded file to the destination directory
        if (move_uploaded_file($_FILES["fileInput"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $uploadDirectory . $_FILES["fileInput"]["name"])) {
            // File uploaded successfully
        } else {
            // Error uploading file
            echo "Failed to upload file.";
        }
    } else {
        // No new photo uploaded, retrieve existing photo filename from database
        $query = "SELECT image FROM prof WHERE id = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $existingPhoto);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Assign existing photo filename
        $newphoto = $existingPhoto;
    }
    // Get the user ID from the session
    $id = $_GET['id'];

    // Prepare the UPDATE query to update user details
    $query = "UPDATE prof SET nomprenom = ?, dateNaissance = ?, grade = ?, specialite = ?, image = ?, id_user = ? WHERE id = ?";
    $stmt = mysqli_prepare($db, $query);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "sssssii", $newname, $newdob, $newrank, $newspeciality, $newphoto, $user_id, $id);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        header("location:/adminpanel/tables/professor/professorTab.php");
        exit();
    } else {
        // Update failed
        echo "Failed to update user details";
    }

    mysqli_stmt_close($stmt);
}

$query = "SELECT id_user FROM prof UNION SELECT id_user FROM etudient";
$result = mysqli_query($db, $query);

// Initialize an array to store user IDs
$userIds = array();

// Check if query was successful
if ($result) {
    // Fetch each row and store the user IDs
    while ($row = mysqli_fetch_assoc($result)) {
        $userIds[] = $row['id_user'];
    }
}

// Fetch user IDs that do not exist in the id_user column of the etudient table
$filteredUserIds = array();

// Check if there are user IDs
if (!empty($userIds)) {
    // Create a comma-separated string of user IDs
    $userIdsString = implode(',', $userIds);

    // Sanitize the string of user IDs
    $userIdsString = implode(',', array_map('intval', explode(',', $userIdsString)));

    // Fetch user IDs that do not exist in the etudient table
    $query = "SELECT id FROM user WHERE id NOT IN ($userIdsString)";
    $result = mysqli_query($db, $query);

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
               
                <a href="/adminpanel/tables/professor/professorTab.php" style="color: #fdfdfd;">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg"alt="">
                    Supervisors
                </a>
                <a href="/adminpanel/tables/professor/add_teacher.php">
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
        <form class="student-form" action="" method="post" enctype="multipart/form-data">
            <div class="image-form">
                <img id="image-preview" src="/backend/image/<?php echo $rows['image']; ?>" class="preview-img">
                <input type="file" name="fileInput" id="file" class="inputfile" onchange="displayImagePreview()" />
                <label for="file" class="label-file">Change picture</label>
            </div>
            <div class="entete">
                <label for=""> ID :</label>
                <input class="input-ep-rd-id" type="text" name="id" id="id" value="<?php echo $rows['id']?>" readonly>

                <label for=""> Signed up :</label>
                <input class="input-ep-rd" type="text" value="<?php echo $rows['creation']?>" readonly>
            </div>
            <div>
                <label for="">Full name :</label>
                <input value="<?php echo $rows['nomprenom']?>" class="input-ep" type="text" name="name" id="">
            </div>
            <div>
                <label for="">Date of birth :</label>
                <input value="<?php echo $rows['dateNaissance'];?>" class="input-ep" type="date" name="DOB" id="">
            </div>
            <div>
                <?php
                if (!empty($rows['grade'])) {
                    $selected_options = explode(',', $rows['grade']);
                }?>

                <label for="">Rank:</label>
                <select class="input-ep" name="level" id="level" onchange="updateSpecialiteOptions()">
                    <option value="Professor" <?php if (isset($selected_options) && in_array('Professor', $selected_options)) echo 'selected'; ?> >Professor</option>
                    <option value="assistant" <?php if (isset($selected_options) && in_array('assistant', $selected_options)) echo 'selected'; ?>>assistant</option>
                    <option value="MAA" <?php if (isset($selected_options) && in_array('MAA', $selected_options)) echo 'selected'; ?>>MAA</option>
                    <option value="MCB" <?php if (isset($selected_options) && in_array('MCB', $selected_options)) echo 'selected'; ?>>MCB</option>
                    <option value="MCA" <?php if (isset($selected_options) && in_array('MCA', $selected_options)) echo 'selected'; ?>>MCA</option>
                    <option value="MAc" <?php if (isset($selected_options) && in_array('MAC', $selected_options)) echo 'selected'; ?>>MAC</option>
                </select>

                <?php
                if (!empty($rows['specialite'])) {
                    $selected_options = explode(',', $rows['specialite']);
                }?>

                <label for="">Speciality :</label>
                <select class="input-ep" name="specialite" id="specialite">
                    <option value="mathematic" <?php if (isset($selected_options) && in_array('mathematic', $selected_options)) echo 'selected'; ?> >mathematic</option>
                    <option value="Computer Science" <?php if (isset($selected_options) && in_array('Computer Science', $selected_options)) echo 'selected'; ?>>Computer Science</option>
                    <option value="others" <?php if (isset($selected_options) && in_array('others', $selected_options)) echo 'selected'; ?>>others</option>
                </select>
            </div>
            <div>
                <label for="">id_user :</label>
                <select class="input-ep" name="user_id" id="">
                    <?php
                    $idp = $rows['id_user'];
                    echo "<option value=\"$idp\">$idp</option>";
                    foreach ($filteredUserIds as $userId) {
                        echo "<option value=\"$userId\">$userId</option>";
                    }
                    ?>
                </select>
            </div>
            <input class="btn-sub" type="submit" value="Update">
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
