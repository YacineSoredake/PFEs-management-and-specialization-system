<?php
$id = $_GET['id'];
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
            <img class="chap" src="/PFEs/figma/toga 1.svg" alt="">
            <img class="mychoice" src="/PFEs/figma/MyChoice.svg" alt="">
        </div>  
        <form action="/backend/SignUpFormStudent.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="form-container">
                <div class="info-form">
                    <input type="text" name="iduser" value="<?php echo $id; ?>" hidden>
                    <label for="number">Registration number :</label>
                    <input class="inpt-login" placeholder="ex:202136853088" type="number" name="number" required>

                    <label for="name">Full name :</label>
                    <input class="inpt-login" placeholder="ex:Blaha mohamed yacine" type="text" name="name" id="name" required oninput="validateName()">
                    <span id="nameError" style="color: red; display: none;">Full name must not contain numbers.</span>

                    <label for="dateNaissance">Date of birth :</label>
                    <input type="date" name="dateNaissance" id="dateNaissance" required onblur="validateDate()" onkeydown="return false;">
                    <div id="dateError" style="display:none;color:red;">The birth date cannot be in the future.</div>
                     <div id="dateErrorAge" style="display:none;color:red;">You must be at least 19 years old.</div>

                    <div>
                        <label for="level">Level / Speciality :</label>
                        <select name="level" id="level" onchange="updateSpecialiteOptions()">
                            <option value="2L">2 Licence</option>
                            <option value="3L">3 Licence</option>
                            <option value="2M">2 Master</option>
                        </select>
                        <select name="specialite" id="specialite">
                            <!-- dynamic option -->
                        </select>             
                    </div>
                </div>

                <div class="image-form">
                    <img id="image-preview" src="#" alt="Image Preview" style="border-radius:50%; width:144px; height:144px; display:none;">   
                    <input accept=".png, .jpg"  type="file" name="fileInput" id="file" class="inputfile" onchange="displayImagePreview()" />
                    <label for="file" class="label-file">Add your picture</label>
                    <div class="g-recaptcha" data-sitekey="6Lc5KYspAAAAAG5wCvZr30YjzTP3yzfuCRmnxaMg"></div>                 
                </div>
            </div>
            <img class="style-line" src="/PFEs/figma/Line 1.png" alt="">
            <input class="regsiter-btn" type="submit" name="register" value="Sign up">   
        </form>
    </div>

    <script>
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
            var dateErrorAge = document.getElementById('dateErrorAge');

            // Reset error messages
            dateError.style.display = 'none';
            dateErrorAge.style.display = 'none';

            // Check if birth date is in the future
            if (birthDate >= today) {
                dateError.style.display = 'block';
                return; // Exit the function if date is in the future
            }

            // Calculate age
            var ageDiff = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                ageDiff--;
            }

            // Check if age is less than 19
            if (ageDiff < 19) {
                dateErrorAge.style.display = 'block';
            }
        }

        // Disable keyboard input for date field
        document.getElementById('dateNaissance').addEventListener('keydown', function(event) {
            event.preventDefault();
        });
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
    </script>
</body>
</html>
