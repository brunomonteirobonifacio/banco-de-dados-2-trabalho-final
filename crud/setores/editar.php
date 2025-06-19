<?php
include '../config/conexao.php';
$id = (int)$_GET['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $sql = "UPDATE setor SET nome = :nome WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $nome, ':id' => $id]);
    header('Location: index.php');
    exit();
}
$sql = "SELECT * FROM setor WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$setor = $stmt->fetch(PDO::FETCH_ASSOC);
include '../templates/header.php';
?>
<h2>Editar Setor</h2>
<form action="editar.php?id=<?php echo $id; ?>" method="post">
    <label for="nome">Nome do Setor:</label>
    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($setor['nome']); ?>" required>
    <button type="submit" class="btn">Atualizar</button>
</form>
<?php include '../templates/footer.php'; ?>