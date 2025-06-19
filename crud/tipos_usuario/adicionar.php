<?php
include '../config/conexao.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $sql = "INSERT INTO tipo_usuario (nome) VALUES (:nome)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $nome]);
    header('Location: index.php');
    exit();
}
include '../templates/header.php';
?>
<h2>Adicionar Novo Tipo de Usuário</h2>
<form action="adicionar.php" method="post">
    <label for="nome">Nome do Tipo:</label>
    <input type="text" id="nome" name="nome" required>
    <button type="submit" class="btn">Salvar</button>
</form>
<?php include '../templates/footer.php'; ?>