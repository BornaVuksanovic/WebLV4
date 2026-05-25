<?php
session_start();

// Zabrana pristupa neprijavljenima
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: login.php");
    exit();
}

include('includes/db.php');

$poruka = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prikupljanje podataka i micanje razmaka
    $naslov = trim($_POST['naslov']);
    $zanr = trim($_POST['zanr']);
    $godina = (int)$_POST['godina'];
    $trajanje = (int)$_POST['trajanje'];
    $prosjecna_ocjena = (float)$_POST['prosjecna_ocjena'];
    $zemlja = trim($_POST['zemlja']);
    $slika = trim($_POST['slika']);

    // 1. SERVERSKA VALIDACIJA PODATAKA
    if (empty($naslov) || empty($zanr) || empty($godina) || empty($trajanje)) {
        $poruka = "Sva polja osim slike i ocjene su obavezna!";
    } elseif ($godina < 1888 || $godina > 2100) {
        // Prvi film ikad snimljen je iz 1888. godine :)
        $poruka = "Godina izdanja nije u ispravnom formatu!";
    } elseif ($trajanje <= 0 || $trajanje > 500) {
        $poruka = "Trajanje filma mora biti logično (između 1 i 500 minuta)!";
    } elseif ($prosjecna_ocjena < 0 || $prosjecna_ocjena > 10) {
        $poruka = "Ocjena mora biti između 0 i 10!";
    } else {
        // 2. SPREMANJE U BAZU (Prepared Statement)
        $sql = "INSERT INTO filmovi (naslov, zanr, godina, trajanje, prosjecna_ocjena, zemlja, slika) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($con, $sql)) {
            // Oznake tipova: s=string, s=string, i=integer, i=integer, d=double, s=string
            mysqli_stmt_bind_param($stmt, "ssiidss", $naslov, $zanr, $godina, $trajanje, $prosjecna_ocjena, $zemlja, $slika);
            
            if (mysqli_stmt_execute($stmt)) {
                $poruka = "<span style='color: green;'>Film je uspješno dodan u videoteku!</span>";
            } else {
                $poruka = "<span style='color: red;'>Greška pri dodavanju filma u bazu.</span>";
            }
            mysqli_stmt_close($stmt);
        } else {
            $poruka = "<span style='color: red;'>Greška pri pripremi upita.</span>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Dodaj film</title>
</head>
<body>
    <header>
        <h1>Dodaj novi film</h1>
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

            <form action="dodaj_film.php" method="POST">
                <label for="naslov">Naslov filma:</label>
                <input type="text" id="naslov" name="naslov" required>
                
                <label for="zanr">Žanr:</label>
                <input type="text" id="zanr" name="zanr" required>
                
                <label for="godina">Godina izdanja:</label>
                <input type="number" id="godina" name="godina" required>
                
                <label for="trajanje">Trajanje (u minutama):</label>
                <input type="number" id="trajanje" name="trajanje" required>
                
                <label for="prosjecna_ocjena">Prosječna ocjena (npr. 8.5):</label>
                <input type="number" step="0.1" id="prosjecna_ocjena" name="prosjecna_ocjena" value="0.0">

                <label for="zemlja">Država porijekla:</label>
                <input type="text" id="zemlja" name="zemlja">
                
                <label for="slika">URL slike (opcionalno):</label>
                <input type="text" id="slika" name="slika">
                
                <button type="submit">Spremi film</button>
            </form>
        </div>
    </main>
</body>
</html>