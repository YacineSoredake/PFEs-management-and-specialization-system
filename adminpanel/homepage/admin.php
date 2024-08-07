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

$sql = "SELECT COUNT(*) AS count2L FROM etudient WHERE niveau = '2L'";
$result = $db->query($sql);
// Fetch the result
$row = $result->fetch_assoc();

$_SESSION['2lnbr'] = $row['count2L'];

$sql2 = "SELECT COUNT(*) AS count3L FROM etudient WHERE niveau = '3L'";
$result2 = $db->query($sql2);
// Fetch the result
$rows = $result2->fetch_assoc();

$sql3 = "SELECT COUNT(*) AS count2M FROM etudient WHERE niveau = '2M'";
$result3 = $db->query($sql3);
// Fetch the result
$roW = $result3->fetch_assoc();

$sql4 = "SELECT COUNT(id) AS prf FROM prof";
$result4 = $db->query($sql4);
// Fetch the result
$roWz = $result4->fetch_assoc();


$sql_isil = "SELECT COUNT(*) AS countISIL FROM etudient WHERE niveau = '2L' AND choix = 'ISIL'";
$result_isil = $db->query($sql_isil);
$row_isil = $result_isil->fetch_assoc();

$sql_si = "SELECT COUNT(*) AS countSI FROM etudient WHERE niveau = '2L' AND choix = 'SI'";
$result_si = $db->query($sql_si);
$row_si = $result_si->fetch_assoc();


$queri = "SELECT DATE(creation) AS signup_date, COUNT(*) AS num_signups 
          FROM user 
          GROUP BY DATE(creation)";

$resul = mysqli_query($db, $queri);

// Initialize an array to hold the chart data
$datat = array();

// Loop through the result set and format the data
while ($ro = mysqli_fetch_assoc($resul)) {
    $datat[] = array($ro['signup_date'], (int) $ro['num_signups']);
}

// Convert data to JSON format
$datat_json = json_encode($datat);
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
    <link rel="stylesheet" href="admin.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>Admin Panel</title>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        google.charts.setOnLoadCallback(drawUserSignupChart);

        function drawUserSignupChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('date', 'Date');
            data.addColumn('number', 'Number of Sign-ups');

            // Parse JSON data retrieved from PHP
            var jsonData = <?php echo $datat_json; ?>;

            // Iterate over the JSON data and add rows to the DataTable
            for (var i = 0; i < jsonData.length; i++) {
                var entry = jsonData[i];
                data.addRow([new Date(entry[0]), entry[1]]);
            }

            var options = {
                backgroundColor: 'transparent',
                title: 'User Sign-ups Over Time',
                titleTextStyle: {
                      fontName:"Inter",
                        color: 'black',
                        fontSize: 12
                    },
                curveType: 'linear',
                colors: ['#34f775'],
                legend: 'none',
                vAxis: { title: 'Number of Sign-ups',  titleTextStyle: {
                      fontName:"Inter",
                        color: 'black',
                        fontSize: 12
                    } },
                hAxis: { title: 'Date(day)',  titleTextStyle: {
                      fontName:"Inter",
                        color: 'black',
                        fontSize: 12
                    } }
            };

            var chart = new google.visualization.LineChart(document.getElementById('user_signup_chart_div'));
            chart.draw(data, options);
        }

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Choice', 'Number of Students'],
                ['ISIL', <?php echo $row_isil['countISIL']; ?>],
                ['SI', <?php echo $row_si['countSI']; ?>]
            ]);

            var options = {
                title: '2L Choice',
                pieHole: 0.5,
                backgroundColor: 'transparent',
                height: 200,
                width: 300,
                colors: ['#74d8fc', '#FF6F61'],
                fontName: 'Inter',
                titleTextStyle: {
                    color: 'Black',
                    fontSize: 12,
                    bold: true,
                },
                legend: {
                    textStyle: {
                        color: 'Black',
                    },
                },
                chartArea: {
                    left: 50,
                    top: 50,
                    width: '70%',
                    height: '70%',
                },
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
        }
    </script>
</head>

<body>
    <div class="body-container">
        <div class="menubar">
            <div>
                <img src="/PFEs/figma/MyChoice (1).svg" alt="">
            </div>
            <div>
                <nav>
                    <a href="/adminpanel/homepage/admin.php" style="color: #fdfdfd;">
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
                    <a href="/adminpanel/tables/user/userTab.php">
                    <img style="height: 15px;" src="/PFEs/figma/zazaz.svg" alt="">
                        Users
                    </a>
                    <a href="/adminpanel/homepage/setting/setting.php">
                        <img style="height: 15px;" src="/PFEs/figma/seting.svg" alt="">
                        Settings
                    </a>
                    <a style="border-top: 1px solid white; justify-self:flex-end; " href="/logouttoAdmin.php">
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
    <div class="stats">
        <div style="color:lightslategray; border:3px solid lightslategray;" class="stat-std">
            <strong><?php echo $roWz['prf'] ?></strong>
            Supervisors
        </div>
        <div style="color:lightblue; border:3px solid lightblue;" class="stat-std">
            <strong><?php echo $row['count2L'] ?></strong>
            Second's Licence
        </div>
        <div style="color:lightcoral; border:3px solid lightcoral;" class="stat-std">
            <strong><?php echo $rows['count3L'] ?></strong>
            Third's Licence
        </div>
        <div style="color:lightgreen; border:3px solid lightgreen;" class="stat-std">
            <strong><?php echo $roW['count2M'] ?></strong>
            Second's Master
        </div>
    </div>
    <div class="option" >
    <div id="user_signup_chart_div" class="sign-chart" ></div>
    <a href="/adminpanel/L2choices/LtwoListe.php">
    <div id="donutchart"></div>
    </a>
    </div>
    <div class="option" >
        <a class="opt" style="background-color:#1447a6;" href="/adminpanel/tables/user/add_user.php"> <img height="40px" src="/PFEs/figma/zazaz.svg" alt=""> Create new user</a>
        <a class="opt" style="background-color:#0bb861;" href="/adminpanel/L2choices/LtwoListe.php"> <img height="40px" src="/PFEs/figma/spec.svg" alt="">Specialty assignment</a>
        <a class="opt" style="background-color:lightcoral;" href="/adminpanel/listeFinish/liste.php"> <img height="40px" src="/PFEs/figma/assss.svg" alt="">PFEs assingment</a>
        <a class="opt" style="background-color:lightseagreen;" href="/adminpanel/listeFinish/ranking.php"> <img height="40px" src="/PFEs/figma/rankuung.svg" alt="">Rankings</a>
        <a class="opt" style="background-color:lightslategray;" href="/adminpanel/homepage/setting/setting.php"> <img height="40px" src="/PFEs/figma/seting.svg" alt="">Settings</a>
    </div>        
    </div>
    </div>
</body>
<script>
        function updateUsers(action, levels) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_users.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    location.reload();
                } else {
                    alert('Error: ' + xhr.status);
                }
            };
            xhr.send('action=' + action + '&levels=' + JSON.stringify(levels));
        }
    </script>


</html>