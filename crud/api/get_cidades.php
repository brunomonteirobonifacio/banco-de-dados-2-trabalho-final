<?php
require '../config/conexao.php';

// Pega o ID do estado da URL (ex: /api/get_cidades.php?estado_id=8)
$estado_id = isset($_GET['estado_id']) ? (int)$_GET['estado_id'] : 0;

if ($estado_id > 0) {
    try {
        $sql = "SELECT id, nome FROM cidade WHERE estado_id = :estado_id ORDER BY nome";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':estado_id' => $estado_id]);
        $cidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($cidades);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao buscar cidades: ' . $e->getMessage()]);
    }
} else {
    // Se nenhum estado_id for fornecido, retorna uma lista vazia
    header('Content-Type: application/json');
    echo json_encode([]);
}