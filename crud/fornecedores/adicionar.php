<?php
include '../config/conexao.php';
$erro = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome']; $cnpj = $_POST['cnpj'];
    $sql = "INSERT INTO fornecedor (nome, cnpj) VALUES (:nome, :cnpj)";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([':nome' => $nome, ':cnpj' => $cnpj]);
        header('Location: index.php'); exit();
    } catch (PDOException $e) {
        if ($e->getCode() == '23505') { $erro = "Erro: O CNPJ informado já está cadastrado."; }
        else { $erro = "Erro ao adicionar fornecedor: " . $e->getMessage(); }
    }
}
include '../templates/header.php';
?>
<h2>Adicionar Fornecedor</h2>
<?php if ($erro): ?><p class="erro"><?php echo $erro; ?></p><?php endif; ?>
<form action="adicionar.php" method="post">
    <label for="nome">Nome:</label> <input type="text" id="nome" name="nome" required>
    <label for="cnpj">CNPJ (14 dígitos):</label> <input type="text" id="cnpj" name="cnpj" maxlength="14" required>
    <button type="submit" class="btn">Salvar</button>
</form>
<?php include '../templates/footer.php'; ?>