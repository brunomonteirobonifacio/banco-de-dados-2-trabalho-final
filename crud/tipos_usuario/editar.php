<?php
include '../config/conexao.php';
$id = (int)$_GET['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $sql = "UPDATE tipo_usuario SET nome = :nome WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $nome, ':id' => $id]);
    header('Location: index.php');
    exit();
}
$sql = "SELECT * FROM tipo_usuario WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$tipo = $stmt->fetch(PDO::FETCH_ASSOC);
include '../templates/header.php';
?>
<h2>Editar Tipo de Usuário</h2>
<form action="editar.php?id=<?php echo $id; ?>" method="post">
    <label for="nome">Nome do Tipo:</label>
    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($tipo['nome']); ?>" required>
    <button type="submit" class="btn">Atualizar</button>
</form>
<?php include '../templates/footer.php'; ?>