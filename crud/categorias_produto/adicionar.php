<?php
include '../config/conexao.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $sql = "INSERT INTO produto_categoria (nome) VALUES (:nome)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $nome]);
    header('Location: index.php');
    exit();
}
include '../templates/header.php';
?>
<h2>Adicionar Nova Categoria</h2>
<form action="adicionar.php" method="post">
    <label for="nome">Nome da Categoria:</label>
    <input type="text" id="nome" name="nome" required>
    <button type="submit" class="btn">Salvar</button>
</form>
<?php include '../templates/footer.php'; ?>