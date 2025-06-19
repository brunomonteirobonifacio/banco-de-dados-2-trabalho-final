<?php
include '../config/conexao.php';
$id = (int)$_GET['id'];
if ($id > 0) {
    try {
        $sql = "DELETE FROM fornecedor WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        header('Location: index.php');
    } catch (PDOException $e) {
        if ($e->getCode() == '23503') { die("Erro: Não é possível remover este fornecedor pois ele está associado a um ou mais pedidos."); }
        else { die("Erro ao remover fornecedor: " . $e->getMessage()); }
    }
}
?>