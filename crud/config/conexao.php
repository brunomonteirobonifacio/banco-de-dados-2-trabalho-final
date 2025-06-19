<?php
// Detalhes da conexão com o banco de dados PostgreSQL
$host = 'localhost';
$port = '5432'; // Porta padrão do PostgreSQL
$dbname = 'Banco_trabalho'; // <-- MUDE AQUI para o nome do seu banco de dados
$user = 'postgres'; // <-- MUDE AQUI para o seu usuário (geralmente 'postgres')
$password = '050905'; // <-- MUDE AQUI para a senha do seu usuário

// String de conexão (DSN - Data Source Name)
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password";

try {
    // Cria uma nova instância do PDO (PHP Data Objects)
    // PDO é uma camada de abstração que permite usar o mesmo código para vários bancos de dados
    $pdo = new PDO($dsn);

    // Define o modo de erro do PDO para exceção
    // Isso significa que se algo der errado, o PDO vai "lançar um erro" que podemos capturar
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Se a conexão falhar, o 'try' para e o 'catch' é executado
    // Ele mostra uma mensagem de erro amigável e encerra o script
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
?>