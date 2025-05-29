<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knjižnica</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>Gorškova knjižnica</h1>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="index.php">Domov</a></li>
                    <li><a href="#">Moje knjige</a></li>
                    <li><a href="#">Dodaj knjigo</a></li>
                </ul>
                <ul class="auth-links">
                  
                        <li><a href="#">Prijava</a></li>
                        <li><a href="#">Registracija</a></li>
                    
                </ul>
            </nav>
        </div>
    </header>
    <main> 