<?php
session_start();
if (!isset($_SESSION['korisnik_id'])) {
    header("Location: login.php");
    exit();
}
include('includes/db.php');

// PHP Logika za filtriranje
$sql = "SELECT id, naslov, zanr, godina, trajanje, prosjecna_ocjena, zemlja FROM filmovi WHERE 1=1";
$params = [];
$types = "";

if (!empty($_GET['search'])) {
    $sql .= " AND naslov LIKE ?";
    $params[] = "%" . $_GET['search'] . "%";
    $types .= "s";
}
if (!empty($_GET['minOcjena'])) {
    $sql .= " AND prosjecna_ocjena >= ?";
    $params[] = (float)$_GET['minOcjena'];
    $types .= "d";
}
if (!empty($_GET['drzava']) && $_GET['drzava'] !== 'Sve') {
    $sql .= " AND zemlja = ?";
    $params[] = $_GET['drzava'];
    $types .= "s";
}

$stmt = mysqli_prepare($con, $sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Videoteka - Početna</title> 
</head>
<body>
    <header>
        <h1>Dobrodošli u Videoteku</h1>
    </header>

    <nav> 
        <ul> 
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn">Menu</a>
                <div class="dropdown-content">
                    <a href="index.php">Početna</a>
                    <a href="dodaj_film.php">Dodaj film</a>
                    <a href="galerija.php">Galerija slika</a>
                    <a href="logout.php">Odjavi se (<?php echo htmlspecialchars($_SESSION['korisnicko_ime']); ?>)</a>
                </div>
            </li>
        </ul>
    </nav>

    <main>
        <h1>Popis Filmova</h1>
        <section> 
          <form action="index.php" method="GET" class="filter-sekcija">
    <h3>Filtriraj filmove</h3>
    <div class="filter-grupa">
        <label>Pretraži naslov:</label>
        <input type="text" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
    </div>
    <div class="filter-grupa">
        <label>Min. ocjena:</label>
        <input type="range" name="minOcjena" min="0" max="10" step="0.1" value="<?php echo htmlspecialchars($_GET['minOcjena'] ?? '5.0'); ?>">
    </div>
    <div class="filter-grupa">
        <label>Država:</label>
        <input type="radio" name="drzava" value="Sve" <?php if(($_GET['drzava'] ?? 'Sve') == 'Sve') echo 'checked'; ?>> Sve
        <input type="radio" name="drzava" value="USA" <?php if(($_GET['drzava'] ?? '') == 'USA') echo 'checked'; ?>> USA
        <input type="radio" name="drzava" value="Ostalo" <?php if(($_GET['drzava'] ?? '') == 'Ostalo') echo 'checked'; ?>> Ostalo
    </div>
    <button type="submit">Primijeni filtre</button>
</form>

<table>
<thead>
        <tr><th>Naslov</th><th>Žanr</th><th>Zemlja</th><th>Godina</th><th>Ocjena</th><th>Akcija</th></tr>
    </thead>
    <tbody>
    <?php
    while($row = mysqli_fetch_assoc($result)) {
        $stil = ($row['prosjecna_ocjena'] < 5.0) ? "style='color: red; font-weight: bold;'" : "";
        echo "<tr>
                <td>" . htmlspecialchars($row['naslov']) . "</td>
                <td>" . htmlspecialchars($row['zanr']) . "</td>
                <td>" . htmlspecialchars($row['zemlja']) . "</td>
                <td>" . htmlspecialchars($row['godina']) . "</td>
                <td $stil>" . htmlspecialchars($row['prosjecna_ocjena']) . "</td>
                <td><a href='dodaj_u_zeljeno.php?id=" . $row['id'] . "'>Dodaj</a></td>
                <td><a href='ukloni_iz_zeljenog.php?id={$row['id']}'>ukloni</a></td>
              </tr>";
    }
    ?>
    </tbody>
</table>
        </section>
        
        <aside id="kosarica-kontejner">
            <h2>Moja videoteka</h2>
            <ul id="lista-kosarice">
            <?php
            $korisnik_id = $_SESSION['korisnik_id'];
            $sql_kosarica = "SELECT f.naslov FROM filmovi f JOIN zeljeni_filmovi zf ON f.id = zf.film_id WHERE zf.korisnik_id = ?";
            if ($stmt = mysqli_prepare($con, $sql_kosarica)) {
                mysqli_stmt_bind_param($stmt, "i", $korisnik_id);
                mysqli_stmt_execute($stmt);
                $result_kosarica = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result_kosarica) > 0) {
                    while($row = mysqli_fetch_assoc($result_kosarica)) {
                        echo "<li>" . htmlspecialchars($row['naslov']) . "</li>";
                    }
                } else {
                    echo "<li>Videoteka je prazna.</li>";
                }
                mysqli_stmt_close($stmt);
            }
            ?>
            </ul>
        </aside>
        
        <article>
            <h2>Najnovije vijesti</h2>
            <p>Sustav je uspješno povezan s MySQL bazom podataka.</p>
        </article>
    </main>

    <footer>
        <p>&copy; 2026. Web Programiranje. Sva prava pridržana.</p>
    </footer>
</body>
</html>