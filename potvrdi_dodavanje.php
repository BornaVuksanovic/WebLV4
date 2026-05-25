<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['korisnik_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$film_id = (int)$_GET['id'];
$korisnik_id = $_SESSION['korisnik_id'];

// Spremanje u bazu
$sql = "INSERT INTO zeljeni_filmovi (korisnik_id, film_id) VALUES (?, ?)";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "ii", $korisnik_id, $film_id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php?status=dodano");
} else {
    echo "Došlo je do greške pri spremanju u videoteku.";
}
mysqli_stmt_close($stmt);
?>