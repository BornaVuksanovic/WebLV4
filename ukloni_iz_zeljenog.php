<?php
session_start();
include('includes/db.php');

if (isset($_GET['id'])) {
    $film_id = (int)$_GET['id'];
    $korisnik_id = $_SESSION['korisnik_id'];
    
    // Sigurno brisanje
    $sql = "DELETE FROM zeljeni_filmovi WHERE film_id = ? AND korisnik_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $film_id, $korisnik_id);
    mysqli_stmt_execute($stmt);
    
    header("Location: index.php");
}
?>