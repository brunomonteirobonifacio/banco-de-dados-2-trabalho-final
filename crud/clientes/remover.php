<?php

include '../config/conexao.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $sql = "DELETE FROM cliente WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        header('Location: index.php?status=removido');
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == '23503') {
             die("Erro: Não é possível remover este cliente pois ele está associado a uma ou mais vendas.");
        } else {
             die("Erro ao remover cliente: " . $e->getMessage());
        }
    }
} else {
    header('Location: index.php');
    exit();
}
?>