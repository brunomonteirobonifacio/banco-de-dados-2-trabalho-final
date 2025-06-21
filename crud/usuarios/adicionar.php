<?php
include '../config/verifica_login.php';
include '../config/conexao.php';
$erro = '';

// Busca setores e tipos para os dropdowns
$setores_stmt = $pdo->query("SELECT id, nome FROM setor ORDER BY nome");
$setores = $setores_stmt->fetchAll(PDO::FETCH_ASSOC);
$tipos_stmt = $pdo->query("SELECT id, nome FROM tipo_usuario ORDER BY nome");
$tipos = $tipos_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome']; $cpf = $_POST['cpf']; $fone = $_POST['fone']; $email = $_POST['email'];
    $setor_id = $_POST['setor_id']; $tipo_usuario_id = $_POST['tipo_usuario_id'];
    
    $sql = "INSERT INTO usuario (nome, cpf, fone, email, setor_id, tipo_usuario_id) VALUES (:nome, :cpf, :fone, :email, :setor_id, :tipo_usuario_id)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            ':nome' => $nome, ':cpf' => $cpf, ':fone' => $fone, ':email' => $email,
            ':setor_id' => $setor_id, ':tipo_usuario_id' => $tipo_usuario_id
        ]);
        header('Location: index.php'); exit();
    } catch (PDOException $e) {
        if ($e->getCode() == '23505') { $erro = "Erro: O CPF informado já está cadastrado."; }
        else { $erro = "Erro ao adicionar usuário: " . $e->getMessage(); }
    }
}
include '../templates/header.php';
?>
<h2>Adicionar Novo Usuário</h2>
<?php if ($erro): ?><p class="erro"><?php echo $erro; ?></p><?php endif; ?>
<form action="adicionar.php" method="post">
    <label for="nome">Nome:</label><input type="text" id="nome" name="nome" required>
    <label for="cpf">CPF:</label><input type="text" id="cpf" name="cpf" maxlength="11" required>
    <label for="fone">Telefone:</label><input type="text" id="fone" name="fone" maxlength="11">
    <label for="email">Email:</label><input type="email" id="email" name="email">
    
    <label for="setor_id">Setor:</label>
    <select id="setor_id" name="setor_id" required>
        <option value="">Selecione...</option>
        <?php foreach ($setores as $setor): ?>
            <option value="<?php echo $setor['id']; ?>"><?php echo htmlspecialchars($setor['nome']); ?></option>
        <?php endforeach; ?>
    </select>

    <label for="tipo_usuario_id">Tipo de Usuário:</label>
    <select id="tipo_usuario_id" name="tipo_usuario_id" required>
        <option value="">Selecione...</option>
        <?php foreach ($tipos as $tipo): ?>
            <option value="<?php echo $tipo['id']; ?>"><?php echo htmlspecialchars($tipo['nome']); ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn">Salvar</button>
</form>
<?php include '../templates/footer.php'; ?>