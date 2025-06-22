<?php
include '../config/verifica_login.php';

include '../config/conexao.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$cliente = null; $erro = '';

if ($id <= 0) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $fone = $_POST['fone'];
    $email = $_POST['email'];

    $sql = "UPDATE cliente SET nome = :nome, cpf = :cpf, fone = :fone, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            ':nome' => $nome,
            ':cpf' => $cpf,
            ':fone' => $fone,
            ':email' => $email,
            ':id' => $id
        ]);
        header('Location: index.php?status=editado');
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == '23505') {
            $erro = "Erro: O CPF informado já pertence a outro cliente.";
        } else {
            $erro = "Erro ao editar cliente: " . $e->getMessage();
        }
    }
}

try {
    $sql = "SELECT * FROM cliente WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        header('Location: index.php');
        exit();
    }
} catch (PDOException $e) {
    die("Erro ao buscar cliente: " . $e->getMessage());
}


include '../templates/header.php';
?>
<h2>Editar Cliente</h2>
<?php if ($erro): ?><p class="erro"><?php echo $erro; ?></p><?php endif; ?>
<form action="editar.php?id=<?php echo $id; ?>" method="post">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>

    <label for="cpf">CPF:</label>
    <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cliente['cpf']); ?>" maxlength="11" required>

    <label for="fone">Telefone:</label>
    <input type="text" id="fone" name="fone" value="<?php echo htmlspecialchars($cliente['fone']); ?>" maxlength="11">

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" required>

    <button type="submit" class="btn">Atualizar</button>
    <a href="index.php" class="btn-cancelar">Cancelar</a>
</form>
<?php 

include '../templates/footer.php'; 
?>