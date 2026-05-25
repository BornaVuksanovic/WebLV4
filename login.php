<?php
// Obavezno pokretanje sesije na samom vrhu
session_start();

// Povezujemo se s bazom iz novog foldera
include('includes/db.php');

$poruka = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $korisnicko_ime = trim($_POST['korisnicko_ime']);
    $unesena_lozinka = $_POST['lozinka'];

    if (empty($korisnicko_ime) || empty($unesena_lozinka)) {
        $poruka = "Molimo ispunite sva polja!";
    } else {
        // Pripremljeni upit za dohvaćanje korisnika
        $sql = "SELECT id, korisnicko_ime, lozinka, uloga FROM korisnici WHERE korisnicko_ime = ?";
        
        if ($stmt = mysqli_prepare($con, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $korisnicko_ime);
            mysqli_stmt_execute($stmt);
            
            // Spremamo rezultat
            mysqli_stmt_store_result($stmt);
            
            // Provjeravamo postoji li korisnik
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Povezujemo rezultate iz baze s varijablama
                mysqli_stmt_bind_result($stmt, $id, $db_korisnicko_ime, $hashirana_lozinka, $uloga);
                mysqli_stmt_fetch($stmt);
                
                // Ključni korak: Provjera odgovara li unesena lozinka hashiranoj u bazi
                if (password_verify($unesena_lozinka, $hashirana_lozinka)) {
                    // Lozinka je točna! Spremamo podatke u sesiju
                    $_SESSION['korisnik_id'] = $id;
                    $_SESSION['korisnicko_ime'] = $db_korisnicko_ime;
                    $_SESSION['uloga'] = $uloga;
                    
                    // Preusmjeravanje na početnu stranicu
                    header("Location: index.php");
                    exit(); // Prekidamo daljnje izvršavanje koda
                } else {
                    $poruka = "Pogrešna lozinka!";
                }
            } else {
                $poruka = "Korisnik ne postoji!";
            }
            mysqli_stmt_close($stmt);
        } else {
            $poruka = "Greška pri komunikaciji s bazom.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Prijava</title>
</head>
<body>
    <h2>Prijava u Videoteku</h2>
    
    <?php if ($poruka != ""): ?>
        <p style="color: red;"><strong><?php echo $poruka; ?></strong></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="korisnicko_ime">Korisničko ime:</label><br>
        <input type="text" id="korisnicko_ime" name="korisnicko_ime" required><br><br>
        
        <label for="lozinka">Lozinka:</label><br>
        <input type="password" id="lozinka" name="lozinka" required><br><br>
        
        <button type="submit">Prijavi se</button>
    </form>
    
    <p><a href="register.php">Nemaš račun? Registriraj se.</a></p>
</body>
</html>