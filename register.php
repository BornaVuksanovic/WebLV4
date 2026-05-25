<?php
// Uključujemo konekciju na bazu
include('includes/db.php');

$poruka = "";

// Provjeravamo je li forma poslana (POST metoda)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $korisnicko_ime = trim($_POST['korisnicko_ime']);
    $lozinka = $_POST['lozinka'];

    // Osnovna validacija da polja nisu prazna
    if (empty($korisnicko_ime) || empty($lozinka)) {
        $poruka = "Molimo ispunite sva polja!";
    } else {
        // Hashiranje lozinke - OBAVEZNO za prolaz na labosu!
        $hashirana_lozinka = password_hash($lozinka, PASSWORD_DEFAULT);

        // Pripremljeni SQL upit (Prepared Statement) za zaštitu od SQL injekcije
        $sql = "INSERT INTO korisnici (korisnicko_ime, lozinka) VALUES (?, ?)";
        
        if ($stmt = mysqli_prepare($con, $sql)) {
            // Povezujemo parametre (s = string, s = string) umjesto upitnika
            mysqli_stmt_bind_param($stmt, "ss", $korisnicko_ime, $hashirana_lozinka);
            
            // Izvršavamo upit
            if (mysqli_stmt_execute($stmt)) {
                $poruka = "Uspješna registracija! Sada se možete prijaviti.";
            } else {
                // Ako korisnik s tim imenom već postoji (jer smo stavili UNIQUE u bazi)
                $poruka = "Greška: Korisničko ime već postoji.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $poruka = "Greška pri pripremi upita.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
</head>
<body>
    <h2>Registracija novog korisnika</h2>
    
    <?php if ($poruka != ""): ?>
        <p><strong><?php echo $poruka; ?></strong></p>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <label for="korisnicko_ime">Korisničko ime:</label><br>
        <input type="text" id="korisnicko_ime" name="korisnicko_ime" required><br><br>
        
        <label for="lozinka">Lozinka:</label><br>
        <input type="password" id="lozinka" name="lozinka" required><br><br>
        
        <button type="submit">Registriraj se</button>
    </form>
    
    <p><a href="login.php">Već imaš račun? Prijavi se ovdje.</a></p>
</body>
</html>