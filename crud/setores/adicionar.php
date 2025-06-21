<?php
include '../config/verifica_login.php';
include '../config/conexao.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $sql = "INSERT INTO setor (nome) VALUES (:nome)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $nome]);
    header('Location: index.php');
    exit();
}
include '../templates/header.php';
?>
<h2>Adicionar Novo Setor</h2>
<form action="adicionar.php" method="post">
    <label for="nome">Nome do Setor:</label>
    <input type="text" id="nome" name="nome" required>
    <button type="submit" class="btn">Salvar</button>
</form>
<?php include '../templates/footer.php'; ?>