<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "videoteka_baza"; 

// Kreiranje konekcije (bez onih cite oznaka!)
$con = mysqli_connect($host, $username, $password, $database);

// Provjera konekcije
if (mysqli_connect_errno()) {
    echo "Greška pri spajanju na MySQL: " . mysqli_connect_error();
    die(); 
}
?>