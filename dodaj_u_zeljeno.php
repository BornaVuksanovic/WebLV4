<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['korisnik_id'])) {
    header("Location: login.php");
    exit();
}

$film_id = (int)$_GET['id'];
$korisnik_id = $_SESSION['korisnik_id'];

// 1. PROVJERA: Je li film već u videoteci?
$check_sql = "SELECT * FROM zeljeni_filmovi WHERE korisnik_id = ? AND film_id = ?";
$stmt = mysqli_prepare($con, $check_sql);
mysqli_stmt_bind_param($stmt, "ii", $korisnik_id, $film_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    // Film je već dodan
    echo "<script>alert('Film je već u tvojoj videoteci!'); window.location.href='index.php';</script>";
    exit();
}

// 2. AKO NIJE, nastavi s provjerom ocjene (tvoj postojeći kod)
$query = mysqli_prepare($con, "SELECT prosjecna_ocjena FROM filmovi WHERE id = ?");

if (isset($_GET['id'])) {
    $film_id = (int)$_GET['id'];
    
    // Provjera ocjene
    $query = mysqli_prepare($con, "SELECT prosjecna_ocjena FROM filmovi WHERE id = ?");
    mysqli_stmt_bind_param($query, "i", $film_id);
    mysqli_stmt_execute($query);
    $res = mysqli_stmt_get_result($query);
    $film = mysqli_fetch_assoc($res);

    if ($film['prosjecna_ocjena'] < 5.0) {
        // ISPIS UPOZORENJA
        echo "<h1>Upozorenje!</h1>";
        echo "<div style='border: 2px solid red; padding: 20px; background-color: #ffe6e6;'>";
        echo "Ovaj film ima nisku ocjenu (" . $film['prosjecna_ocjena'] . ") – jeste li sigurni da ga želite dodati?";
        echo "<br><br><a href='potvrdi_dodavanje.php?id=$film_id'>DA, POTVRDI DODAVANJE</a> | ";
        echo "<a href='index.php'>ODUSTANI</a>";
        echo "</div>";
    } else {
        // Ocjena je OK, automatski preusmjeri na potvrdu
        header("Location: potvrdi_dodavanje.php?id=$film_id");
    }
}
?>