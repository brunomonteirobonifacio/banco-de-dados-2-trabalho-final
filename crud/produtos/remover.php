<?php
include '../config/conexao.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    try {
        $sql = "DELETE FROM produto WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        header('Location: index.php?status=removido');
    } catch (PDOException $e) {
        if ($e->getCode() == '23503') {
            die("Erro: Não é possível remover este produto pois ele está associado a vendas ou pedidos.");
        } else {
            die("Erro ao remover produto: " . $e->getMessage());
        }
    }
} else {
    header('Location: index.php');
}
?>