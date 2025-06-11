<?php
// Vključimo konfiguracijsko datoteko za povezavo na bazo
require_once 'config.php';

// Preverimo, če je uporabnik prijavljen
if (!isset($_SESSION['user_id'])) {
    // Če ni prijavljen, ga preusmerimo na stran za prijavo
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Pripravimo SQL poizvedbo za pridobivanje knjig uporabnika
// Pridobimo podatke o knjigah, avtorjih in žanrih
$query = "SELECT b.*, a.first_name, a.last_name, g.name as genre_name, ubs.status
          FROM books b, authors a, genres g, users_books_status ubs
          WHERE ubs.user_id = $user_id
          AND ubs.books_id = b.books_id
          AND b.authors_id = a.authors_id
          AND b.genre_id = g.genre_id
          ORDER BY b.title";

// Izvedemo poizvedbo
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Moje knjige</h2>
    
    <!-- Preverimo, če ima uporabnik kakšne knjige -->
    <?php if (mysqli_num_rows($result) > 0): ?>
        <!-- Tabela za prikaz knjig -->
        <table class="books-table">
            <tr>
                <th>Naslov</th>
                <th>Avtor</th>
                <th>Jezik</th>
                <th>Leto</th>
                <th>Žanr</th>
                <th>Status</th>
                <th>Podrobnosti</th>
            </tr>
            <!-- Prikazujemo knjige v zanki -->
            <?php while ($book = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $book['title'] ?></td>
                    <td><?= $book['first_name'] . ' ' . $book['last_name'] ?></td>
                    <td><?= $book['language'] ?></td>
                    <td><?= $book['publication_year'] ?></td>
                    <td><?= $book['genre_name'] ?></td>
                    <td><?= $book['status'] ?></td>
                    <td><a href="book_details.php?id=<?= $book['books_id'] ?>">Poglej</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <!-- Sporočilo, če uporabnik nima nobene knjige -->
        <p>Nimate še nobene knjige v svoji zbirki.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?> 