<?php
include '../config/conexao.php';
$id = (int)$_GET['id'];
if ($id > 0) {
    try {
        $sql = "DELETE FROM tipo_usuario WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        header('Location: index.php');
    } catch (PDOException $e) {
        if ($e->getCode() == '23503') { die("Erro: Não é possível remover este tipo, pois ele está em uso por um ou mais usuários."); }
        else { die("Erro ao remover tipo de usuário: " . $e->getMessage()); }
    }
}
?>