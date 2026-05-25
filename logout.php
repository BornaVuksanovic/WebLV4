<?php
session_start();
session_unset(); // Briše sve varijable sesije
session_destroy(); // Uništava samu sesiju

// Preusmjeravanje nazad na login
header("Location: login.php");
exit();
?>