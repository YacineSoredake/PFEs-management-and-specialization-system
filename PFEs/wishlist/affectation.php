<?php 
session_start();
include "C:/Users/USER/OneDrive/Bureau/PFEs/connectdb.php";

// Check if the connection is established
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$user_id = $_SESSION['user_id'];
$partner_id = $_SESSION['partner'];

$query = "SELECT d.*, t.intitule, t.summary, t.keyword, p.nomprenom, e.nompre, e2.nompre AS partner_name
FROM demande d
JOIN theme t ON d.id_theme = t.id
JOIN prof p ON t.id_prof = p.id
JOIN etudient e ON d.id_etudient = e.id
LEFT JOIN etudient e2 ON d.id_etudient = e2.id_partner
WHERE d.etat = 'assigned' AND (d.id_etudient = ? OR d.id_etudient = ?)
ORDER BY d.priority ASC";

$stmt = mysqli_prepare($db, $query);
if (!$stmt) {
    die("Statement preparation failed: " . mysqli_error($db));
}

mysqli_stmt_bind_param($stmt, "ii", $user_id, $partner_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result === false) {
    die("Query execution failed: " . mysqli_error($db));
}

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    $error = 'No assignment yet';
}

// Load dompdf
require_once('C:/Users/USER/OneDrive/Bureau/vendor/autoload.php');

if (isset($_POST['generate_pdf'])) {
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('Your Name');
    $pdf->SetAuthor('MyChoice');
    $pdf->SetTitle('PFEs assignment form');
    $pdf->SetSubject('Generating PDF from HTML using TCPDF');
    $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    
    // Set default header and footer fonts
    $pdf->setHeaderFont(Array('helvetica', '', 12));
    $pdf->setFooterFont(Array('helvetica', '', 12));
    
    // Set margins
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    
    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 15);
    
    // Set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
    // Set some language-dependent strings
    $pdf->setLanguageArray([
        'a_meta_charset' => 'UTF-8',
        'a_meta_dir' => 'ltr',
        'a_meta_language' => 'en'
    ]);
    
    // Add a page

    // Set content
    $pdf->AddPage();
    $signatory_name = "Dr. Khoubzaoui"; // Replace with actual signatory name
    $signatory_title = "Head of Computer Science Department"; // Replace with actual signatory title
    // Set content using table for layout
    $html = '
    <table>
        <tr>
            <td>
                <h4 style="line-height: 25px;">Université Djillali Liabes de Sidi Bel Abbes<br>Faculty of Exact Sciences<br>Computer Science Department</h4>
            </td>
            <td style="text-align: right;">
                <img style="height: 200px;" src="/PFEs/figma/téléchargement.png">
            </td>
        </tr>
    </table>
    <h2 style="color: #333; margin-top: 20px;">SUPERVISING FORM</h2>
    <p style="margin-bottom: 10px;">First Student : ' . htmlspecialchars($row['nompre']) . '</p>
    <p style="margin-bottom: 10px;">Second Student : ' . htmlspecialchars($row['partner_name']? : '/') . '</p>
    <p style="margin-bottom: 10px;">Supervisor: ' . htmlspecialchars($row['nomprenom']) . '</p>
    <p style="margin-bottom: 10px;">Title dissertation: ' . htmlspecialchars($row['intitule']) . '</p>
    <p style="margin-bottom: 10px;">Summary: ' . htmlspecialchars($row['summary']) . '</p>
    <br><br>
    <table style=" margin-top:300px; ">
        <tr>
            <td style="padding-top: 300px; width: 50%;">
                <p style="border-top: 1px solid #000; width: 80%;"></p>
                <p>Signature</p>
            </td>
            <td style="padding-top: 20px; text-align: right; width: 50%;">
                <p>' . htmlspecialchars($signatory_name) . '</p>
                <p>' . htmlspecialchars($signatory_title) . '</p>
            </td>
        </tr>
    </table>';
    $pdf->writeHTML($html, true, false, true, false, '');

    
    // Close and output PDF document
    $pdf->Output('document.pdf', 'I');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment result</title>
    <link rel="stylesheet" href="./wishlist.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="preload" href="/PFEs/figma/pexels-yungsaac-1557251.jpg" as="image">
</head>
<body>
    <header>
       <img src="/PFEs/figma/MyChoice (1).svg" alt="">
       <div class="profile">
           <img class="chap" src="/backend/image/<?php echo $_SESSION['user_image'];?>" alt="">
           <?php echo $_SESSION['user_nompre'];?>
       </div>
    </header>
    <div class="body-container" style="height: 85vh;">
        <div class="menubar">
            <div class="afficher">
                open menu
            </div>
            <nav>
                <a href="/PFEs/profil/profil.php">
                    <img style="height: 15px;" src="/PFEs/figma/profillle.svg" alt="">
                    My profile
                </a>
                <a href="/PFEs/wishlist/wishlist.php">
                    <img style="height: 15px;" src="/PFEs/figma/wish.svg" alt="">
                    My Wishlist
                </a>
                <a href="/PFEs/listeDesEncadreur/teachers_list.php">
                    <img style="height: 15px;" src="/PFEs/figma/techhher.svg" alt="">
                    Affectation
                </a>
                <a href="/PFEs/listeDesEncadreur/teachers_list.php">
                    <img style="height: 15px;" src="/PFEs/figma/techhher.svg" alt="">
                    Find a supervisor
                </a>
                <a href="/PFEs/liste-sujets/liste-sujets.php">
                    <img style="height: 15px;" src="/PFEs/figma/thesus.svg" alt="">
                    Themes available
                </a>
                <a href="/PFEs/homepage/homePage.php">
                    <img style="height: 15px;" src="/PFEs/figma//homme.svg" alt="">
                    Home
                </a>
                <a style="border-top: 1px solid white; justify-self:flex-end;" href="/logout.php">
                    <img style="height: 15px;" src="/PFEs/figma/logg.svg" alt="">
                    Logout
                </a>
            </nav>
        </div>
        <div class="container">
            <h1 style="margin: 0;">
                THE TITLE ASSIGNED
            </h1>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <button class="assign-btn" type="submit" name="generate_pdf">Generate PDF</button>
            </form>
            <?php if (isset($row)) : ?>
                <table style="background-color: lightgreen;">
                    <tr>
                        <th>Title</th>
                        <th>Summary</th>
                        <th>Keywords</th>
                        <th>Supervisor</th>
                        <th>Priority</th>
                    </tr>
                    <tr >
                        <td><?php echo htmlspecialchars($row['intitule']); ?></td>
                        <td><?php echo htmlspecialchars($row['summary']); ?></td>
                        <td><?php echo htmlspecialchars($row['keyword']); ?></td>
                        <td><?php echo htmlspecialchars($row['nomprenom']); ?></td>
                        <td><?php echo htmlspecialchars($row['priority']); ?></td>
                    </tr>
                </table>
            <?php endif; ?>
           
        </div>
    </div>
    <footer>
        &#169; Gestion des PFEs et choix (isil/si)
    </footer>
</body>
</html>
