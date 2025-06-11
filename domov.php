<?php
// Vključimo konfiguracijsko datoteko, ki vsebuje povezavo na bazo podatkov
require_once 'config.php';

// Preverimo, če obstaja iskalni parameter v URL-ju (npr. domov.php?search=harry)
if (isset($_GET['search'])) {
    // Če obstaja, shranimo iskalni niz v spremenljivko $search
    $search = $_GET['search'];
} else {
    // Če ne obstaja, nastavimo prazen niz
    $search = '';
}

// Preverimo, če obstaja parameter žanra v URL-ju (npr. domov.php?genre=1)
if (isset($_GET['genre'])) {
    // Če obstaja, shranimo vrednost žanra
    $genre = $_GET['genre'];
} else {
    // Če ne obstaja, nastavimo prazen niz
    $genre = '';
}

// Pripravimo osnovno SQL poizvedbo za pridobivanje knjig
// Izberemo vse podatke o knjigah (b.*), ime in priimek avtorja ter ime žanra
$query = "SELECT b.*, a.first_name, a.last_name, g.name as genre_name
          FROM books b, authors a, genres g
          WHERE b.authors_id = a.authors_id 
          AND b.genre_id = g.genre_id";

// Če obstaja iskalni niz, dodamo pogoj za iskanje po naslovu knjige
// % pomeni, da iščemo del besedila kjerkoli v naslovu
if ($search) {
    $query .= " AND b.title LIKE '%$search%'";
}

// Če je izbran žanr, dodamo pogoj za filtriranje po žanru
if ($genre) {
    $query .= " AND g.genre_id = $genre";
}

// Dodamo razvrščanje po naslovu knjige (A-Ž)
$query .= " ORDER BY b.title";

// Izvedemo poizvedbo v katero se shranijo rezultati SQL stavka
$result = mysqli_query($conn, $query);

// Pripravimo poizvedbo za pridobivanje vseh žanrov za filter (uno pod išči po naslovu)
$genres_query = "SELECT * FROM genres ORDER BY name";
$genres_result = mysqli_query($conn, $genres_query);
?>

<!-- Vključimo header.php, da je header na spletni strani -->
<?php include 'header.php'; ?>

<h2>Iskanje knjig</h2>

<!-- Obrazec za iskanje knjig - uporablja GET metodo, da se parametri prikažejo v URL-ju -->
<form method="GET" action="" class="search-form">
    <div class="form-group">
        <!-- Polje za vnos iskalnega niza - uporabnik lahko išče po naslovu knjige -->
        <input type="text" name="search" placeholder="Išči po naslovu">
    </div>
    <div class="form-group">
        <!-- Razvrstni seznam za izbiro žanra - prikaže vse žanre iz baze -->
        <select name="genre">
            <option value="">Vse zvrsti</option>
            <!-- Za vsak žanr v bazi ustvarimo novo možnost v seznamu -->
            <?php while ($genre_row = mysqli_fetch_assoc($genres_result)): ?>
                <option value="<?= $genre_row['genre_id'] ?>">
                    <?= $genre_row['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="form-group">
        <!-- Gumb za pošiljanje obrazca - sproži iskanje -->
        <input type="submit" value="Išči">
    </div>
</form>

<!-- Tabela za prikaz knjig - prikaže vse knjige, ki ustrezajo iskalnim kriterijem -->
<table class="books-table">
    <tr>
        <th>Naslov</th>
        <th>Avtor</th>
        <th>Jezik</th>
        <th>Leto</th>
        <th>Žanr</th>
        <th>Podrobnosti</th>
    </tr>
    <!-- Za vsako knjigo v tabeli je while dokler ne izpiše vse knjige pol pa se konča -->
    <?php while ($book = mysqli_fetch_assoc($result)): ?>
        <tr>
            <!-- Prikaz podatkov o knjigi za vsako knjigo -->
            <td><?= $book['title'] ?></td>
            <td><?= $book['first_name'] . ' ' . $book['last_name'] ?></td>
            <td><?= $book['language'] ?></td>
            <td><?= $book['publication_year'] ?></td>
            <td><?= $book['genre_name'] ?></td>
            <!-- Povezava na podrobnosti knjige - prenese ID knjige kot parameter -->
            <td><a href="book_details.php?id=<?= $book['books_id'] ?>">Poglej</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<!-- Vključimo footer.php za prikaz noge spletne strani -->
<?php include 'footer.php'; ?>
