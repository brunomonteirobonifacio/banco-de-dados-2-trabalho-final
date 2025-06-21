<?php
include '../config/verifica_login.php';
include '../config/conexao.php';
$id = (int)$_GET['id']; $erro = '';
$setores_stmt = $pdo->query("SELECT id, nome FROM setor ORDER BY nome");
$setores = $setores_stmt->fetchAll(PDO::FETCH_ASSOC);
$tipos_stmt = $pdo->query("SELECT id, nome FROM tipo_usuario ORDER BY nome");
$tipos = $tipos_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome']; $cpf = $_POST['cpf']; $fone = $_POST['fone']; $email = $_POST['email'];
    $setor_id = $_POST['setor_id']; $tipo_usuario_id = $_POST['tipo_usuario_id'];
    $sql = "UPDATE usuario SET nome=:nome, cpf=:cpf, fone=:fone, email=:email, setor_id=:setor_id, tipo_usuario_id=:tipo_usuario_id WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            ':nome' => $nome, ':cpf' => $cpf, ':fone' => $fone, ':email' => $email,
            ':setor_id' => $setor_id, ':tipo_usuario_id' => $tipo_usuario_id, ':id' => $id
        ]);
        header('Location: index.php'); exit();
    } catch (PDOException $e) {
        if ($e->getCode() == '23505') { $erro = "Erro: O CPF informado já pertence a outro usuário."; }
        else { $erro = "Erro ao editar usuário: " . $e->getMessage(); }
    }
}

$sql = "SELECT * FROM usuario WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
include '../templates/header.php';
?>
<h2>Editar Usuário</h2>
<?php if ($erro): ?><p class="erro"><?php echo $erro; ?></p><?php endif; ?>
<form action="editar.php?id=<?php echo $id; ?>" method="post">
    <label for="nome">Nome:</label><input type="text" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
    <label for="cpf">CPF:</label><input type="text" name="cpf" value="<?php echo htmlspecialchars($usuario['cpf']); ?>" required>
    <label for="fone">Telefone:</label><input type="text" name="fone" value="<?php echo htmlspecialchars($usuario['fone']); ?>">
    <label for="email">Email:</label><input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>">
    
    <label for="setor_id">Setor:</label>
    <select name="setor_id" required>
        <?php foreach ($setores as $setor): ?>
            <option value="<?php echo $setor['id']; ?>" <?php echo ($usuario['setor_id'] == $setor['id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($setor['nome']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="tipo_usuario_id">Tipo de Usuário:</label>
    <select name="tipo_usuario_id" required>
        <?php foreach ($tipos as $tipo): ?>
            <option value="<?php echo $tipo['id']; ?>" <?php echo ($usuario['tipo_usuario_id'] == $tipo['id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($tipo['nome']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <button type="submit" class="btn">Atualizar</button>
</form>
<?php include '../templates/footer.php'; ?>