<?php
session_start();
// Provjera je li korisnik prijavljen
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: login.php");
    exit();
}
include('includes/db.php');

// Dohvati sve slike i izračunaj prosječnu ocjenu pomoću SQL-a
$sql = "SELECT s.*, 
               COALESCE(AVG(o.ocjena), 0) AS prosjek, 
               COUNT(o.ocjena) AS broj_glasova 
        FROM slike s 
        LEFT JOIN ocjene o ON s.id = o.id_slika 
        GROUP BY s.id";
$result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Galerija slika">
  
  <link rel="stylesheet" href="slike.css">
  
  <title>Galerija slika</title>
  
  <style>
      .ocjena-forma { margin-top: 10px; display: flex; flex-direction: column; gap: 5px; }
      .ocjena-forma select, .ocjena-forma button { padding: 5px; border-radius: 5px; border: 1px solid #ccc; }
      .ocjena-forma button { background-color: #b1df0b; cursor: pointer; border: none; font-weight: bold; }
      .ocjena-forma button:hover { background-color: #9ac20a; }
  </style>
</head>
<body>
    <header>
        <h1>Galerija</h1>
    </header>

    <nav> 
        <ul> 
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Menu</a>
                <div class="dropdown-content">
                    <a href="index.php">Početna</a>
                    <a href="dodaj_sliku.php">Dodaj novu sliku</a>
                    <a href="logout.php">Odjava</a>
                </div>
            </li>
        </ul>
    </nav>
    
    <h1>Galerija slika i ocjenjivanje</h1>
    
    <section class="galerija"> 
        <?php 
        // Vrtimo petlju za svaku sliku pronađenu u bazi
        while($row = mysqli_fetch_assoc($result)): 
            // Kreiramo jedinstveni ID za lightbox (npr. img1, img2...)
            $lightbox_id = "img" . $row['id'];
        ?>
            <figure class="galerija_slika">
                <a href="#<?php echo $lightbox_id; ?>">
                    <img src="<?php echo htmlspecialchars($row['putanja']); ?>" alt="<?php echo htmlspecialchars($row['naziv_datoteke']); ?>" loading="lazy">
                </a>
                <figcaption>
                    <strong><?php echo htmlspecialchars($row['opis']); ?></strong><br>
                    
                    <span style="color: #f5c518; font-size: 1.2em;">★ <?php echo number_format($row['prosjek'], 1); ?></span> 
                    <span style="font-size: 0.8em; color: #555;">(<?php echo $row['broj_glasova']; ?> glasova)</span>
                    
                    <form action="ocijeni.php" method="POST" class="ocjena-forma">
                        <input type="hidden" name="slika_id" value="<?php echo $row['id']; ?>">
                        <select name="ocjena" required>
                            <option value="">Odaberi ocjenu...</option>
                            <option value="5">5 ★ - Odlično</option>
                            <option value="4">4 ★ - Vrlo dobro</option>
                            <option value="3">3 ★ - Dobro</option>
                            <option value="2">2 ★ - Dovoljno</option>
                            <option value="1">1 ★ - Loše</option>
                        </select>
                        <button type="submit">Ocijeni sliku</button>
                    </form>
                </figcaption>
            </figure>

            <div id="<?php echo $lightbox_id; ?>" class="lightbox">
                <a href="#" class="close">✖</a>
                <img src="<?php echo htmlspecialchars($row['putanja']); ?>" alt="<?php echo htmlspecialchars($row['naziv_datoteke']); ?>">
            </div>
            
        <?php endwhile; ?>
    </section>

</body>
</html>