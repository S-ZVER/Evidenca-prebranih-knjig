<?php
require_once 'config.php';

// Uniči sejo
session_destroy();

// Preusmeri na domačo stran
header('Location: domov.php');
exit();
?> 