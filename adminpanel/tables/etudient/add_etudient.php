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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize the input data
    $Matricule = mysqli_real_escape_string($db, $_POST['code']);
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $level = mysqli_real_escape_string($db, $_POST['level']);
    $speciality = mysqli_real_escape_string($db, $_POST['specialite']);
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

    // Prepare the INSERT query for the etudient table
    $query = "INSERT INTO etudient (id, nompre, specialite, niveau, datenaissance, id_user, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $query);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "issssis", $Matricule, $name, $speciality, $level, $dob, $user_id, $photo);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // Insertion successful
        header("Location: etudientTab.php");
    } else {
        // Insertion failed
        echo "Failed to insert data into etudient table: " . mysqli_error($db);
    }

    mysqli_stmt_close($stmt);
}

// Fetch user IDs from prof and etudient tables
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
    $escapedIds = array_map(function ($id) use ($db) {
        return mysqli_real_escape_string($db, $id);
    }, $userIds);
    // Create a comma-separated string of user IDs
    $userIdsString = "'" . implode("','", $escapedIds) . "'";

    // Fetch user IDs that do not exist in the etudient table
    $query = "SELECT id FROM user WHERE id NOT IN ($userIdsString)";
    $result = mysqli_query($db, $query);

    // Check if query was successful
    if ($result) {
        // Fetch each row and store the filtered user IDs
        while ($row = mysqli_fetch_assoc($result)) {
            $filteredUserIds[] = $row['id'];
        }
    } else {
        echo "Error: " . mysqli_error($db);
    }
} else {
    // Fetch all user IDs if no prof or etudient records found
    $query = "SELECT id FROM user";
    $result = mysqli_query($db, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $filteredUserIds[] = $row['id'];
        }
    } else {
        echo "Error: " . mysqli_error($db);
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
               
                <a href="/adminpanel/tables/etudient/etudientTab.php">
                    <img style="height: 15px;" src="/PFEs/figma/sub.svg" alt="">
                    Students
                </a>
                <a href="/adminpanel/tables/user/add_user.php" style="color: #fdfdfd;">
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
            <div class="profile">Admin</div>
        </header>
        <form class="edit-form" action="" method="post" enctype="multipart/form-data">
            <div class="image-form">
                <img id="image-preview" src="#" alt="Image Preview" class="preview-img">   
                <input type="file" name="fileInput" id="file" class="inputfile" onchange="displayImagePreview()" />
                <label for="file" class="label-file">Add picture</label>
            </div>
            <div>
                <label for="">Reg-code :</label>
                <input placeholder="ex:202185274123" class="input-ep" type="number" name="code" id="" >
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
                <label for="">Level:</label>
                <select class="input-ep" name="level" id="level" onchange="updateSpecialiteOptions()">
                    <option value="2L">2 Licence</option>
                    <option value="3L">3 Licence</option>
                    <option value="2M">2 Master</option>
                </select>
            </div>
            <div>
                <label for="">Speciality :</label>
                <select class="input-ep" name="specialite" id="specialite"></select>
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
<script>
    function updateSpecialiteOptions() {
        var selectedLevel = document.getElementById("level").value;
        var specialiteSelect = document.getElementById("specialite");
        specialiteSelect.innerHTML = "";

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

    updateSpecialiteOptions();

    function displayImagePreview() {
        var fileInput = document.getElementById('file');
        var imagePreview = document.getElementById('image-preview');

        if (fileInput.files.length > 0) {
            var reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(fileInput.files[0]);
        } else {
            imagePreview.style.display = 'none';
        }
    }
</script>
</body>
</html>
