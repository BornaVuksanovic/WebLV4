<?php
session_start();
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: login.php");
    exit();
}
include('includes/db.php');

$poruka = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["slika"])) {
    $opis = trim($_POST['opis']);
    
    $ime_datoteke = $_FILES["slika"]["name"];
    $tmp_ime = $_FILES["slika"]["tmp_name"];
    $velicina = $_FILES["slika"]["size"];
    $tip = mime_content_type($tmp_ime);
    
    // 1. Validacija veličine (5MB = 5 * 1024 * 1024 bytes)
    $max_velicina = 5 * 1024 * 1024;
    // 2. Validacija formata
    $dozvoljeni_tipovi = ['image/jpeg', 'image/png'];

    if ($velicina > $max_velicina) {
        $poruka = "<span style='color:red;'>Slika ne smije biti veća od 5MB!</span>";
    } elseif (!in_array($tip, $dozvoljeni_tipovi)) {
        $poruka = "<span style='color:red;'>Dozvoljeni su samo JPEG i PNG formati!</span>";
    } else {
        // Generiraj jedinstveno ime da se ne prepišu slike s istim imenom
        $novo_ime = time() . "_" . basename($ime_datoteke);
        $putanja = "slike/" . $novo_ime;
        
        if (move_uploaded_file($tmp_ime, $putanja)) {
            // Spremi u bazu
            $sql = "INSERT INTO slike (naziv_datoteke, opis, putanja) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $novo_ime, $opis, $putanja);
            
            if (mysqli_stmt_execute($stmt)) {
                $poruka = "<span style='color:green;'>Slika uspješno dodana!</span>";
            } else {
                $poruka = "<span style='color:red;'>Greška pri upisu u bazu.</span>";
            }
        } else {
            $poruka = "<span style='color:red;'>Greška pri prijenosu datoteke na server.</span>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj sliku</title>
    <link rel="stylesheet" href="slike.css">
</head>
<body>
    <header>
        <h1>Dodaj novu sliku</h1>
    </header>

    <nav> 
        <ul> 
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Menu</a>
                <div class="dropdown-content">
                    <a href="index.php">Početna</a>
                    <a href="galerija.php">Galerija</a>
                    <a href="logout.php">Odjavi se</a>
                </div>
            </li>
        </ul>
    </nav>

    <main>
        <div class="forma-kontejner">
            <?php if ($poruka != ""): ?>
                <div class="poruka-box"><?php echo $poruka; ?></div>
            <?php endif; ?>

            <form action="dodaj_sliku.php" method="POST" enctype="multipart/form-data">
                <label>Odaberi sliku (JPG/PNG, max 5MB):</label>
                <input type="file" name="slika" accept=".jpg, .jpeg, .png" required>
                
                <label>Opis slike:</label>
                <textarea name="opis" rows="4"></textarea>
                
                <button type="submit">Učitaj sliku</button>
            </form>
        </div>
    </main>
</body>
</html>