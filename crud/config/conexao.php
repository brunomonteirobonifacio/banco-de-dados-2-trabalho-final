<?php

// Define a URL base 
define('BASE_URL', '/banco-de-dados-2-trabalho-final/crud/');


$host = 'localhost';



$host = 'localhost';
$port = '5432'; 
$dbname = 'Banco_trabalho'; 
$user = 'postgres'; 
$password = '050905'; 


$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
?>