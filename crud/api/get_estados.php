<?php
require '../config/conexao.php';

try {
    $stmt = $pdo->query("SELECT id, nome, uf FROM estado ORDER BY nome");
    $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($estados);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao buscar estados: ' . $e->getMessage()]);
}