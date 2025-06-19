<?php
// CORREÇÃO APLICADA AQUI
include '../config/conexao.php';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $fone = $_POST['fone'];
    $email = $_POST['email'];
    // Para simplificar, estamos usando um endereco_id fixo.
    $endereco_id = 1;

    $sql = "INSERT INTO cliente (nome, cpf, fone, email, endereco_id) VALUES (:nome, :cpf, :fone, :email, :endereco_id)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            ':nome' => $nome,
            ':cpf' => $cpf,
            ':fone' => $fone,
            ':email' => $email,
            ':endereco_id' => $endereco_id
        ]);
        header('Location: index.php?status=sucesso');
        exit();
    } catch(PDOException $e) {
        if ($e->getCode() == '23505') {
            $erro = "Erro: O CPF informado já está cadastrado.";
        } else {
            $erro = "Erro ao adicionar cliente: " . $e->getMessage();
        }
    }
}

// CORREÇÃO APLICADA AQUI
include '../templates/header.php';
?>
<h2>Adicionar Novo Cliente</h2>
<?php if ($erro): ?><p class="erro"><?php echo $erro; ?></p><?php endif; ?>
<form action="adicionar.php" method="post">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required>

    <label for="cpf">CPF (11 dígitos):</label>
    <input type="text" id="cpf" name="cpf" maxlength="11" required>

    <label for="fone">Telefone (11 dígitos):</label>
    <input type="text" id="fone" name="fone" maxlength="11">

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <button type="submit" class="btn">Salvar</button>
    <a href="index.php" class="btn-cancelar">Cancelar</a>
</form>
<?php 
// CORREÇÃO APLICADA AQUI
include '../templates/footer.php'; 
?>