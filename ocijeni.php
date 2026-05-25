<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['korisnik_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: galerija.php");
    exit();
}

$korisnik_id = $_SESSION['korisnik_id'];
$slika_id = (int)$_POST['slika_id'];
$ocjena = (int)$_POST['ocjena'];

if ($ocjena >= 1 && $ocjena <= 5) {
    // Spremi novu ocjenu ILI ažuriraj staru ako već postoji
    $sql = "INSERT INTO ocjene (id_korisnik, id_slika, ocjena) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE ocjena = VALUES(ocjena), vrijeme_ocjene = CURRENT_TIMESTAMP";
            
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $korisnik_id, $slika_id, $ocjena);
    mysqli_stmt_execute($stmt);
}

header("Location: galerija.php");
?>