<?php
// Vključimo konfiguracijsko datoteko za povezavo na bazo
require_once 'config.php';

// Preverimo, če je uporabnik prijavljen in tud preverimo če je administrator za foro
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // Če ni prijavljen ali ni administrator, ga preusmerimo na domačo stran
    header('Location: domov.php');
    exit();
}

// Inicializiramo spremenljivke za sporočila
$error = '';
$success = '';

// Pripravimo SQL poizvedbo za pridobivanje vseh knjig
// Pridobimo podatke o knjigah, avtorjih in žanrih
$books_query = "SELECT b.*, a.first_name, a.last_name, g.name as genre_name, u.username as added_by_username
                FROM books b
                JOIN authors a ON b.authors_id = a.authors_id
                JOIN genres g ON b.genre_id = g.genre_id
                JOIN users u ON b.added_by = u.user_id
                ORDER BY b.title";

// Pripravimo SQL poizvedbo za pridobivanje vseh uporabnikov
$users_query = "SELECT * FROM users ORDER BY user_id DESC";

// Izvedemo poizvedbi
$books_result = mysqli_query($conn, $books_query);
$users_result = mysqli_query($conn, $users_query);

// Obdelamo brisanje knjige
if (isset($_POST['delete_book']) && isset($_POST['confirm_delete'])) {
    $book_id = $_POST['book_id'];
    if (mysqli_query($conn, "DELETE FROM books WHERE books_id = $book_id")) {
        $success = 'Knjiga je bila uspešno izbrisana';
    } else {
        $error = 'Napaka pri brisanju knjige';
    }
}

// Obdelamo brisanje uporabnika
if (isset($_POST['delete_user']) && isset($_POST['confirm_delete'])) {
    $user_id = $_POST['user_id'];
    
    // Check if user is admin
    $check_admin = "SELECT is_admin FROM users WHERE user_id = $user_id";
    $admin_result = mysqli_query($conn, $check_admin);
    $user = mysqli_fetch_assoc($admin_result);
    
    if ($user['is_admin'] != 1) {
        // First delete user's book statuses
        $delete_status_query = "DELETE FROM users_books_status WHERE user_id = $user_id";
        if (mysqli_query($conn, $delete_status_query)) {
            // Then delete the user
            if (mysqli_query($conn, "DELETE FROM users WHERE user_id = $user_id")) {
                $success = 'Uporabnik je bil uspešno izbrisan';
            } else {
                $error = 'Napaka pri brisanju uporabnika';
            }
        } else {
            $error = 'Napaka pri brisanju uporabnikovih knjig';
        }
    } else {
        $error = 'Administratorja ni mogoče izbrisati';
    }
}
?>

<?php include 'header.php'; ?>

<div class="admin-container">
    <div class="admin-section">
        <h2>Administracija</h2>
        
        <!-- Prikaz sporočil o napakah in uspehu če je kaj notri-->
        <?php if ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="message success"><?= $success ?></div>
        <?php endif; ?>
        
       
    </div>
    
    <!-- Sekcija za upravljanje knjig -->
    <div class="admin-section">
        <h3>Knjige</h3>
        <div class="book-list">
            <?php while ($book = mysqli_fetch_assoc($books_result)): ?>
                <div class="book-card">
                    <h4><?= $book['title'] ?></h4>
                    <p><strong>Avtor:</strong> <?= $book['first_name'] . ' ' . $book['last_name'] ?></p>
                    <p><strong>Dodal:</strong> <?= $book['added_by_username'] ?></p>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="book_id" value="<?= $book['books_id'] ?>">
                        <input type="hidden" name="confirm_delete" value="1">
                        <input type="submit" name="delete_book" value="Izbriši knjigo" class="delete-button">
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Sekcija za upravljanje uporabnikov -->
    <div class="admin-section">
        <h3>Uporabniki</h3>
        <div class="user-list">
            <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                <div class="user-card">
                    <h4><?= $user['username'] ?></h4>
                    <p><strong>Email:</strong> <?= $user['email'] ?></p>
                    <p><strong>Ime:</strong> <?= $user['first_name'] . ' ' . $user['last_name'] ?></p>
                    <p><strong>Status:</strong> 
                    <?php 
                  
                    if ($user['is_admin']) {
                        echo 'Administrator';
                    } else {
                        echo 'Uporabnik';
                    }
                    ?>
                    </p>
                    
                    <?php if ($user['user_id'] !== 1): ?>
                        <form method="POST" action="">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <input type="hidden" name="confirm_delete" value="1">
                            <input type="submit" name="delete_user" value="Izbriši uporabnika" class="delete-button">
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?> 