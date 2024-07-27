<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./chosePage.css">
    <title> Home page</title>
</head>
<body>
    <header>
        <img src="/PFEs/figma/MyChoice (1).svg" alt="">
        <div class="right-header">
            <div class="profile">
            <div class="chap">
            <img class="chap" src="/backend/image/<?php echo $_SESSION['user_image'];?>" alt="">
            </div>       
             <?php echo $_SESSION['user_nompre'];?>
            </div>
        </div>
     </header>
     <div class="body-container">
<div class="menubar">
        
        <div>
        <nav>
                    <a href="/choix/profil/profil.php">
                        <img style="height: 15px;" src="/PFEs/figma/profillle.svg" alt="">
                        My profil
                    </a>
                    <a href="/choix/homePage/home.php">
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

    <img class="imag" src="/PFEs/figma/option.png"alt="">
    <section>
        <div>
        <img class="icon" src="/backend/image/<?php echo $_SESSION['user_image'];?>" alt="">
        </div>
        <form action="/backend/SendChoiceForm.php" method="post">
        <input type="hidden" name="id_et" value="<?php echo $_SESSION['user_id'];?>" readonly>
        <label class="self" for="">Personal information</label>
        <div class="lab-input">
            <label for="">Full name :</label>
            <input value="<?php echo $_SESSION['user_nompre'];?>" type="text" readonly>
        </div>
        <div class="lab-input">
            <label for="">Level :</label>
            <input value="<?php echo $_SESSION['user_niveau'];?>" type="text" readonly>
        </div>
        <div class="lab-input">
            <label for="">speciality :</label>
            <input value="<?php echo $_SESSION['user_specialite'];?>" type="text" readonly>
        </div>
        <div class="lab-input">
            <label for="">Date of birth :</label>
            <input value="<?php echo $_SESSION['user_DOB'];?>" type="date" readonly>
        </div>
        
        <label class="self" for="">Choose your first choice </label>
        <select name="choix">
            <option value="isil">isil</option>
            <option value="si">si</option>
        </select>
        <input type="submit" value="Submit my choice">
       </form>
    </section>
    </div>
     </div>
    <footer>
        &#169; Gestion des PFEs et choix (isil/si)
    </footer>
</body>
</html>