<?php
// Vključimo konfiguracijsko datoteko za povezavo na bazo
require_once 'config.php';

// Preverimo, če je uporabnik prijavljen
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Inicializiramo spremenljivke za sporočila
$error = '';
$success = '';

// Preverimo, če je bil obrazec poslan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Shranimo vrednosti iz obrazca
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $language = mysqli_real_escape_string($conn, $_POST['language']);
    $publication_year = (int)$_POST['publication_year'];
    $genre_id = (int)$_POST['genre_id'];
    
    // Handle new author
    if (isset($_POST['new_author']) && $_POST['new_author'] == '1') {
        $first_name = mysqli_real_escape_string($conn, $_POST['new_first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['new_last_name']);
        
        if (empty($first_name) || empty($last_name)) {
            $error = 'Prosim vnesite ime in priimek avtorja';
        } else {
            // dodaj novega avtorja v bazo
            $author_query = "INSERT INTO authors (first_name, last_name) VALUES ('$first_name', '$last_name')";
            if (mysqli_query($conn, $author_query)) {
                $author_id = mysqli_insert_id($conn);
            } else {
                $error = 'Napaka pri dodajanju avtorja';
            }
        }
    } else {
        $author_id = (int)$_POST['author_id'];
    }
    
    // Preverimo, če so vsa obvezna polja izpolnjena
    if (empty($title) || empty($language) || empty($publication_year) || empty($genre_id) || empty($author_id)) {
        $error = 'Prosim izpolnite vsa obvezna polja';
    } else {
        // Pripravimo SQL poizvedbo za dodajanje nove knjige
        $query = "INSERT INTO books (title, authors_id, language, publication_year, genre_id, added_by) 
                 VALUES ('$title', $author_id, '$language', $publication_year, $genre_id, {$_SESSION['user_id']})";
        
        // Izvedemo poizvedbo
        if (mysqli_query($conn, $query)) {
            $success = 'Knjiga je bila uspešno dodana';
        } else {
            $error = 'Napaka pri dodajanju knjige';
        }
    }
}

// Pridobimo seznam avtorjev
$authors_query = "SELECT * FROM authors ORDER BY last_name, first_name";
$authors_result = mysqli_query($conn, $authors_query);

// Pridobimo seznam žanrov
$genres_query = "SELECT * FROM genres ORDER BY name";
$genres_result = mysqli_query($conn, $genres_query);
?>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Dodaj novo knjigo</h2>
    
    <!-- Prikaz sporočila o napaki, če obstaja -->
    <?php if ($error): ?>
        <div class="message error"><?= $error ?></div>
    <?php endif; ?>
    
    <!-- Prikaz sporočila o uspehu, če obstaja -->
    <?php if ($success): ?>
        <div class="message success"><?= $success ?></div>
    <?php endif; ?>
    
    <!-- Obrazec za dodajanje knjige -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="title">Naslov knjige:</label>
            <input type="text" id="title" name="title" required>
        </div>
        
        <div class="form-group">
            <label>Avtor:</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="new_author" value="0" checked> Izberi obstoječega avtorja
                </label>
                <label>
                    <input type="radio" name="new_author" value="1"> Dodaj novega avtorja
                </label>
            </div>
            
            <div>
                <select name="author_id" id="author_id" required>
                    <option value="">Izberite avtorja</option>
                    <!-- Prikazujemo avtorje v zanki -->
                    <?php while ($author = mysqli_fetch_assoc($authors_result)): ?>
                        <option value="<?= $author['authors_id'] ?>">
                            <?= $author['last_name'] . ', ' . $author['first_name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div>
                <input type="text" name="new_first_name" placeholder="Ime avtorja">
                <input type="text" name="new_last_name" placeholder="Priimek avtorja">
            </div>
        </div>
        
        <div class="form-group">
            <label for="language">Jezik:</label>
            <input type="text" id="language" name="language" required>
        </div>
        
        <div class="form-group">
            <label for="publication_year">Leto izdaje:</label>
            <input type="number" id="publication_year" name="publication_year" min="1000" max="2025" required>
        </div>
        
        <div class="form-group">
            <label for="genre_id">Žanr:</label>
            <select name="genre_id" id="genre_id" required>
                <option value="">Izberite žanr</option>
                <!-- Prikazujemo žanre v zanki -->
                <?php while ($genre = mysqli_fetch_assoc($genres_result)): ?>
                    <option value="<?= $genre['genre_id'] ?>">
                        <?= $genre['name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-group">
            <input type="submit" value="Dodaj knjigo">
        </div>
    </form>
</div>

<?php include 'footer.php'; ?> 