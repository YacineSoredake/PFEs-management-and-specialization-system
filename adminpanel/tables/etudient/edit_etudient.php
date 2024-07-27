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

if (!$result || mysqli_num_rows($result) === 0) {
    // User is not an admin
    header("Location: /login/login.php");
    exit();
}

// Get the student ID from the URL
$id = $_GET['id'];

$requete = "SELECT * FROM etudient WHERE id = ?";
$stmt = mysqli_prepare($db, $requete);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultat = mysqli_stmt_get_result($stmt);

// Fetch the student data
if ($resultat && mysqli_num_rows($resultat) > 0) {
    $rows = mysqli_fetch_assoc($resultat);
} else {
    // No student found, handle the error
    echo "No student found.";
    exit();
}

// Update query
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'], $_POST['level'], $_POST['specialite'], $_POST['DOB'])) {
        $newname = mysqli_real_escape_string($db, $_POST['name']);
        $newlevel = mysqli_real_escape_string($db, $_POST['level']);
        $newspec = mysqli_real_escape_string($db, $_POST['specialite']);
        $newdob = mysqli_real_escape_string($db, $_POST['DOB']);
        $user_id = mysqli_real_escape_string($db, $_POST['user_id']);
        
        // Handle file upload
        if (isset($_FILES['fileInput']['name']) && $_FILES['fileInput']['name'] != '') {
            $newphoto = $_FILES['fileInput']['name'];
            $uploadDirectory = "/backend/image/";

            if (move_uploaded_file($_FILES["fileInput"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $uploadDirectory . $_FILES["fileInput"]["name"])) {
                // File uploaded successfully
            } else {
                echo "Failed to upload file.";
                exit();
            }
        } else {
            // No new photo uploaded, use existing photo
            $query = "SELECT image FROM etudient WHERE id = ?";
            $stmt = mysqli_prepare($db, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $existingPhoto);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            $newphoto = $existingPhoto;
        }

        // Prepare the UPDATE query
        $query = "UPDATE etudient SET nompre = ?, niveau = ?, specialite = ?, datenaissance = ?, image = ?, id_user = ? WHERE id = ?";
        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "ssssssi", $newname, $newlevel, $newspec, $newdob, $newphoto, $user_id, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("location:/adminpanel/tables/etudient/etudientTab.php");
            exit();
        } else {
            echo "Failed to update user details";
        }

        mysqli_stmt_close($stmt);
    }
}

// Fetch user IDs that do not exist in the prof or etudient tables
$query = "SELECT id_user FROM prof UNION SELECT id_user FROM etudient";
$result = mysqli_query($db, $query);

$userIds = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $userIds[] = $row['id_user'];
    }
}

$filteredUserIds = [];
if (!empty($userIds)) {
    // Escape each ID in the array
    $escapedIds = array_map(function ($id) use ($db) {
        return mysqli_real_escape_string($db, $id);
    }, $userIds);

    // Implode the escaped IDs with single quotes
    $userIdsString = "'" . implode("','", $escapedIds) . "'";

    $query = "SELECT id FROM user WHERE id NOT IN ($userIdsString)";
    $result = mysqli_query($db, $query);

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
               
                <a href="/adminpanel/tables/etudient/etudientTab.php" style="color: #fdfdfd;">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                    Students
                </a>
                <a href="/adminpanel/tables/etudient/add_etudient.php" >
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                    Create new student
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

   <form class="student-form" action="" method="post" enctype="multipart/form-data">
        <div class="image-form">
            <img id="image-preview" src="/backend/image/<?php echo $rows['image']; ?>" alt="Image Preview" class="preview-img">   
            <input type="file" name="fileInput" id="file" class="inputfile" onchange="displayImagePreview()" />
            <label for="file" class="label-file">Change picture</label>
        </div>
        <div class="entete">
        <label for=""> ID :</label>
        <input class="input-ep-rd-id"type="text" name="id" id="id" value="<?php echo $rows['id']?>" readonly>

        <label for=""> Signed up :</label>
        <input class="input-ep-rd" type="text" value="<?php echo $rows['creation']?>" readonly>    
        </div>
        <div>
       
        <label for="">Full name :</label>
        <input value="<?php echo $rows['nompre']?>" class="input-ep"type="text" name="name" id="">
        </div>
        <div>
        <label for="">Date of birth :</label>
        <input value="<?php echo $rows['datenaissance'];?>" class="input-ep"type="date" name="DOB" id="" >
        </div>
        <div>

        <?php
        if (!empty($rows['niveau'])) {
        $selected_options = explode(',', $rows['niveau']);
          }?>

        <label for="">Level:</label>
        <select class="input-ep" name="level" id="level" onchange="updateSpecialiteOptions()">
                <option value="2L" <?php if (isset($selected_options) && in_array('2L', $selected_options)) echo 'selected'; ?> >2 Licence</option>
                <option value="3L" <?php if (isset($selected_options) && in_array('3L', $selected_options)) echo 'selected'; ?>>3 Licence</option>
                <option value="2M" <?php if (isset($selected_options) && in_array('2M', $selected_options)) echo 'selected'; ?>>2 Master</option>
        </select>
       
            <label for="">Speciality :</label>
            <select class="input-ep" name="specialite" id="specialite">
                <?php
                // Define the options for specialite
                $specialiteOptions = ["TC", "ISIL", "SI", "RSSI", "ISI", "WIC"];
                // Iterate over the options
                foreach ($specialiteOptions as $option) {
                    // Check if the current option matches the speciality from the database
                    $isSelected = ($option === $rows['specialite']) ? 'selected' : '';
                    // Output the option with the selected attribute if applicable
                    echo "<option value=\"$option\" $isSelected>$option</option>";
                }
                ?>
            </select>
        </div>
        <div>
        <label for="">id_user :</label>
        <select class="input-ep" name="user_id" id="">
                 <?php
                 $idp=$rows['id_user'];
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
        document.getElementById('level').addEventListener('input',()=>{
            updateSpecialiteOptions();
        })

         function updateSpecialiteOptions() {
            // Get the selected level
            var selectedLevel = document.getElementById("level").value;

            // Get the specialite select element
            var specialiteSelect = document.getElementById("specialite");

            // Remove existing options
            specialiteSelect.innerHTML = "";

            // Add new options based on the selected level
            if (selectedLevel === "2L") {
                addOption(specialiteSelect, "TC", "TC");
            } else if (selectedLevel === "3L") {
                addOption(specialiteSelect, "ISIL", "ISIL");
                addOption(specialiteSelect, "SI", "SI");
            } else if (selectedLevel === "2M") {
                addOption(specialiteSelect, "RSSI", "RSSI");
                addOption(specialiteSelect, "ISI", "ISI");
                addOption(specialiteSelect, "WIC", "WIC");
            }
        }

        function addOption(selectElement, value, text) {
            var option = document.createElement("option");
            option.value = value;
            option.text = text;
            selectElement.add(option);
        }

        

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