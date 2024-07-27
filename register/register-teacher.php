<?php
$id=$_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="register-student.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="container">
    <div class="back" style="margin-left: -1000px;">
        <a style="padding: 6px 8px; text-decoration:none; font-family: 'Inter'; color:#e6e6e6; gap:10px; flex-direction:row; display:flex; align-items:center;" href="/login/login.php">
            <img style="height:45px;" src="/PFEs/figma/back-square-svgrepo-com.svg" alt="">home
        </a>
    </div>

        <div class="image-Container">
        <img class="chap"  src="/PFEs/figma/toga 1.svg" alt="">
        <img class="mychoice" src="/PFEs/figma/MyChoice.svg" alt="">
        </div>  

    <form action="/backend/SignUpFormteacher.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="form-container">
            <div class="info-form">
                <input type="text" name="iduser" value="<?php echo $id; ?>" hidden>
                <label for="name">Full name :</label>
                <input class="inpt-login" placeholder="ex:Blaha mohamed yacine" type="text" name="name" id="name" required oninput="validateName()" required>

                <label for="dateNaissance">Date of birth :</label>
                <input type="date" name="dateNaissance" id="dateNaissance" required onchange="validateDate()" required>
                
                <div>
                    <label for="specialite">Specilality :</label>
                    <select name="specialite" id="specialite">
                          <option value="mathematic">mathemathic</option>
                          <option value="Computer Science">Computer Science</option>
                          <option value="other">other</option>
                    </select>
                </div>
                <div>
                    <label for="grade">grade :</label>
                    <select name="grade" id="grade">
                          <option value="Professor">Professor</option>
                          <option value="assistant">assistant</option>
                          <option value="MAA">MAA</option>
                          <option value="MAB">MAC</option>
                          <option value="MCA">MCA</option>
                          <option value="MCB">MCB</option>
                    </select>
                </div>
              
            </div>

        <div class="image-form">
            <img  id="image-preview" src="#" alt="Image Preview" style="width: 144px; border-radius:50%; height: 144px;display: none;">   
            <input type="file" name="fileInput" id="file" class="inputfile" onchange="displayImagePreview()" />
            <label for="file" class="label-file">Add your picture</label>
                            
            <div class="g-recaptcha" data-sitekey="6Lc5KYspAAAAAG5wCvZr30YjzTP3yzfuCRmnxaMg"></div>         
        </div>
        </div>
        <img class="style-line" src="/PFEs/figma/Line 1.png" alt="">
        <input class="regsiter-btn" type="submit" name="register" value="Sign up">   
    </form>
    </div>
</body>
<script>
     function validateName() {
        var name = document.getElementById('name').value;
        var namePattern = /^[a-zA-Z\s]+$/;
        var nameError = document.getElementById('nameError');

        if (!namePattern.test(name)) {
            nameError.style.display = 'block';
        } else {
            nameError.style.display = 'none';
        }
    }

    function validateDate() {
        var dateNaissance = document.getElementById('dateNaissance').value;
        var today = new Date();
        var birthDate = new Date(dateNaissance);
        var dateError = document.getElementById('dateError');

        if (birthDate >= today) {
            dateError.style.display = 'block';
        } else {
            dateError.style.display = 'none';
        }
    }

    function validateForm() {
        validateName();
        validateDate();

        var nameError = document.getElementById('nameError').style.display;
        var dateError = document.getElementById('dateError').style.display;

        if (nameError === 'none' && dateError === 'none') {
            return true;
        }
        return false;
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateSpecialiteOptions();
    });
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
