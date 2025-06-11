<?php
// Vključimo konfiguracijsko datoteko za povezavo na bazo
require_once 'config.php';

// Preverimo, če je uporabnik že prijavljen
if (isset($_SESSION['user_id'])) {
    // Če je, ga preusmerimo na domačo stran
    header('Location: domov.php');
    exit();
}

// Inicializiramo spremenljivki za sporočila
$error = '';
$success = '';

// Preverimo, če je bil obrazec poslan (POST metoda)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Shranimo vrednosti iz obrazca
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    
    // Preverimo, če so vsa polja izpolnjena
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email) || empty($first_name) || empty($last_name)) {
        $error = 'Prosim izpolnite vsa polja.';
    } elseif ($password !== $confirm_password) {
        // Preverimo, če se gesli ujemata
        $error = 'Gesli se ne ujemata.';
    } else {
        // Preverimo, če uporabniško ime ali email že obstaja
        $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = 'Uporabniško ime ali email že obstaja.';
        } else {
            // Zasifriramo geslo
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Dodamo novega uporabnika v bazo
            $query = "INSERT INTO users (username, password, email, first_name, last_name) 
                     VALUES ('$username', '$hashed_password', '$email', '$first_name', '$last_name')";
            
            if (mysqli_query($conn, $query)) {
                $success = 'Registracija uspešna! Zdaj se lahko prijavite.';
            } else {
                $error = 'Napaka pri registraciji.';
            }
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="form-container">
    <h2>Registracija</h2>
    
    <!-- Prikaz sporočila o napaki, če obstaja -->
    <?php if ($error): ?>
        <div class="message error"><?= $error ?></div>
    <?php endif; ?>
    
    <!-- Prikaz sporočila o uspehu, če obstaja -->
    <?php if ($success): ?>
        <div class="message success"><?= $success ?></div>
    <?php endif; ?>
    
    <!-- Obrazec za registracijo -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Uporabniško ime:</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="first_name">Ime:</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>
        
        <div class="form-group">
            <label for="last_name">Priimek:</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>
        
        <div class="form-group">
            <label for="password">Geslo:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Potrdi geslo:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <div class="form-group">
            <input type="submit" value="Registracija">
        </div>
    </form>
    
    <!-- Povezava na prijavo za obstoječe uporabnike -->
    <p>Že imate račun? <a href="login.php">Prijavite se</a></p>
</div>

<?php include 'footer.php'; ?> 