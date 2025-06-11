<?php
session_start();

$conn = mysqli_connect(
'localhost','root','', 'knjiznica');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?> 