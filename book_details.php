<?php
// Vključimo konfiguracijsko datoteko za povezavo na bazo
require_once 'config.php';

// Preverimo, če je v URL-ju podan ID knjige
if (!isset($_GET['id'])) {
    // Če ni, preusmerimo na domačo stran
    header('Location: domov.php');
    exit();
}

// Shranimo ID knjige iz URL-ja
$book_id = $_GET['id'];

// Pripravimo SQL poizvedbo za pridobivanje podrobnosti o knjigi

// Pridobimo vse podatke o knjigi, avtorju in žanru
$query = "SELECT b.*, a.first_name, a.last_name, g.name as genre_name
          FROM books b, authors a, genres g
          WHERE b.books_id = $book_id
          AND b.authors_id = a.authors_id
          AND b.genre_id = g.genre_id";

// Izvedemo poizvedbo
$result = mysqli_query($conn, $query);
$book = mysqli_fetch_assoc($result);


// Inicializiramo spremenljivki za sporočila
$error = '';
$success = '';

// Obdelamo oceno knjige, če je bil obrazec poslan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    // Preverimo, če je uporabnik prijavljen
    if (!isset($_SESSION['user_id'])) {
        $error = 'Za ocenjevanje morate biti prijavljeni.';
    } else {
        // Shranimo oceno in ID uporabnika
        $rating = $_POST['rating'];
        $user_id = $_SESSION['user_id'];
    
       
            // Preverimo, če je uporabnik že ocenil to knjigo
            $check_query = "SELECT * FROM users_books_status WHERE user_id = $user_id AND books_id = $book_id";
            $check_result = mysqli_query($conn, $check_query);
            
            if (mysqli_num_rows($check_result) > 0) {
                // Če je že ocenil, posodobimo oceno
                $update_query = "UPDATE users_books_status SET rating = $rating WHERE user_id = $user_id AND books_id = $book_id";
                if (mysqli_query($conn, $update_query)) {
                    $success = 'Vaša ocena je bila uspešno posodobljena.';
                } else {
                    $error = 'Napaka pri posodabljanju ocene.';
                }
            } else {
                // Če še ni ocenil, dodamo novo oceno
                $insert_query = "INSERT INTO users_books_status (user_id, books_id, rating, status) VALUES ($user_id, $book_id, $rating, 'read')";
                if (mysqli_query($conn, $insert_query)) {
                    $success = 'Vaša ocena je bila uspešno dodana.';
                } else {
                    $error = 'Napaka pri dodajanju ocene.';
                }
            }
      
    }
}

// Pridobimo vse ocene  za to knjigo
$reviews_query = "SELECT ubs.rating, u.username 
                 FROM users_books_status ubs, users u 
                 WHERE ubs.books_id = $book_id 
                 AND ubs.rating IS NOT NULL 
                 AND ubs.user_id = u.user_id 
                 ORDER BY ubs.status_id ";
$reviews_result = mysqli_query($conn, $reviews_query);
$reviews = [];
while ($row = mysqli_fetch_assoc($reviews_result)) {
    $reviews[] = $row;
}
?>

<?php include 'header.php'; ?>

<div class="book-details">
    <!-- Prikaz podrobnosti o knjigi -->
    <div class="book-info">
        <h2><?= $book['title'] ?></h2>
        <p><strong>Avtor:</strong> <?= $book['first_name'] . ' ' . $book['last_name'] ?></p>
        <p><strong>Jezik:</strong> <?= $book['language'] ?></p>
        <p><strong>Leto izdaje:</strong> <?= $book['publication_year'] ?></p>
        <p><strong>Zvrst:</strong> <?= $book['genre_name'] ?></p>
    </div>

    <!-- Obrazec za ocenjevanje knjige (prikazan samo prijavljenim uporabnikom) -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="book-status">
            <h3>Oceni knjigo</h3>
            <?php if ($error): ?>
                <div class="message error"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="message success"><?= $success ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="rating">Vaša ocena:</label>
                    <select name="rating" id="rating" required>
                        <option value="">Izberite oceno</option>
                        <option value="1">1 - Slabo</option>
                        <option value="2">2 - Zadovoljivo</option>
                        <option value="3">3 - Dobro</option>
                        <option value="4">4 - Zelo dobro</option>
                        <option value="5">5 - Odlično</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" value="Oceni">
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- Prikaz ocen  -->
    <div class="book-reviews">
        <h3>Ocene bralcev</h3>
        <?php if (empty($reviews)): ?>
            <p>Še ni ocen.</p>
        <?php else: ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review">
                    <p><strong><?= $review['username'] ?></strong> - <?= $review['rating'] ?>/5</p>
                    
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?> 