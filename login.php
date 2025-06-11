<?php
// Vključimo konfiguracijsko datoteko za povezavo na bazo
require_once 'config.php';

// Preverimo, če je uporabnik že prijavljen
if (isset($_SESSION['user_id'])) {
    // Če je, ga preusmerimo na domačo stran
    header('Location: domov.php');
    exit();
}

// Inicializiramo spremenljivko za sporočila o napakah
$error = '';

// Preverimo, če je bil obrazec poslan (POST metoda)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Shranimo vrednosti iz obrazca
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Preverimo, če sta uporabniško ime in geslo vnešena
    if (empty($username) || empty($password)) {
        $error = 'Prosim vnesite uporabniško ime in geslo.';
    } else {
        // Poiščemo uporabnika v bazi
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        
        // Preverimo, če uporabnik obstaja
        if ($user = mysqli_fetch_assoc($result)) {
            // Preverimo, če je geslo pravilno
            if (password_verify($password, $user['password'])) {
                // Če je geslo pravilno, shranimo podatke o uporabniku v sejo
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'];
                // Preusmerimo na domačo stran
                header('Location: domov.php');
                exit();
            } else {
                $error = 'Napačno geslo.';
            }
        } else {
            $error = 'Uporabnik ne obstaja.';
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="form-container">
    <h2>Prijava</h2>
    
    <!-- Prikaz sporočila o napaki, če obstaja -->
    <?php if ($error): ?>
        <div class="message error"><?= $error ?></div>
    <?php endif; ?>
    
    <!-- Obrazec za prijavo -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Uporabniško ime:</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="password">Geslo:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <input type="submit" value="Prijava">
        </div>
    </form>
    
    <!-- Povezava na registracijo za nove uporabnike -->
    <p>Še nimate računa? <a href="register.php">Registrirajte se</a></p>
</div>

<?php include 'footer.php'; ?> 