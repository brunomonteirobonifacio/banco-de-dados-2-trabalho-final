<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['usuario_id'])) {

    $baseUrl = '/banco-de-dados-2-trabalho-final/crud'; 
    header('Location: ' . $baseUrl . '/login.php');
    exit();
}