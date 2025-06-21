<?php
// GARANTIMOS QUE ESTE ARQUIVO SEJA O PRIMEIRO A INICIAR A SESSÃO
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se a variável de sessão 'usuario_id' NÃO existe
if (!isset($_SESSION['usuario_id'])) {
    // Para o redirecionamento, definimos a URL base de forma simples aqui,
    // pois não podemos mais depender da conexão.
    $baseUrl = '/banco-de-dados-2-trabalho-final/crud'; 
    header('Location: ' . $baseUrl . '/login.php');
    exit();
}