<?php

$host = getenv('MYSQLHOST') ?: 'localhost';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$baza = getenv('MYSQLDATABASE') ?: 'videoteka_baza'; 
$port = getenv('MYSQLPORT') ?: 3306;

$con = mysqli_connect($host, $user, $pass, $baza, $port);

if (!$con) {
    die("Greška pri spajanju na bazu: " . mysqli_connect_error());
}
?>