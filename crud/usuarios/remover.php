<?php
include '../config/conexao.php';
$id = (int)$_GET['id'];
if ($id > 0) {
    try {
        $sql = "DELETE FROM usuario WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        header('Location: index.php');
    } catch (PDOException $e) {
        if ($e->getCode() == '23503') { die("Erro: Não é possível remover este usuário, pois ele é responsável por pedidos ou movimentos de estoque."); }
        else { die("Erro ao remover usuário: " . $e->getMessage()); }
    }
}
?>