<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <!-- Osnovni meta podatki -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knjižnica</title>
    
    <!-- Povezava na CSS datoteko za oblikovanje -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <!-- Logo in naslov spletne strani -->
            <div class="logo">
                <h1>Gorškova knjižnica</h1>
            </div>
            
            <!-- Glavna navigacijska vrstica -->
            <nav>
                <!-- Seznam glavnih povezav -->
                <ul class="nav-links">
                    <li><a href="domov.php">Domov</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Povezave za prijavljene uporabnike -->
                        <li><a href="my_books.php">Moje knjige</a></li>
                        <li><a href="add_book.php">Dodaj knjigo</a></li>
                        <?php if (isset($_SESSION['is_admin']) && ($_SESSION['is_admin'] == 1 || $_SESSION['is_admin'] === '1')): ?>
                            <!-- Povezave za administratorje -->
                            <li><a href="admin.php">Administracija</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                
                <!-- Seznam povezav za prijavo/odjavo -->
                <ul class="auth-links">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Prikaz pozdrava in povezave za odjavo -->
                        <li><span>Pozdravljen, <?= $_SESSION['username'] ?></span></li>
                        <li><a href="logout.php">Odjava</a></li>
                    <?php else: ?>
                        <!-- Povezave za neprijavljene uporabnike -->
                        <li><a href="login.php">Prijava</a></li>
                        <li><a href="register.php">Registracija</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    
  
    <main> 