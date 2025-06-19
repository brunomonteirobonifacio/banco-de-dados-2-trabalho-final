<?php
include '../config/conexao.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$erro = '';

// Busca todas as categorias para o dropdown
$categorias_stmt = $pdo->query("SELECT id, nome FROM produto_categoria ORDER BY nome");
$categorias = $categorias_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome']; $preco = $_POST['preco']; $qtde = $_POST['qtde'];
    $categoria_id = $_POST['categoria_id']; $codigo = $_POST['codigo'] ?? null;

    $sql = "UPDATE produto SET nome=:nome, preco=:preco, qtde=:qtde, categoria_id=:categoria_id, codigo=:codigo WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute([
            ':nome' => $nome, ':preco' => $preco, ':qtde' => $qtde,
            ':categoria_id' => $categoria_id, ':codigo' => $codigo, ':id' => $id
        ]);
        header('Location: index.php'); exit();
    } catch (PDOException $e) {
        $erro = "Erro ao editar produto: " . $e->getMessage();
    }
}

// Busca os dados do produto para preencher o formulário
$sql_produto = "SELECT * FROM produto WHERE id = :id";
$stmt_produto = $pdo->prepare($sql_produto);
$stmt_produto->execute([':id' => $id]);
$produto = $stmt_produto->fetch(PDO::FETCH_ASSOC);
if (!$produto) { header('Location: index.php'); exit(); }

include '../templates/header.php';
?>
<h2>Editar Produto</h2>
<?php if ($erro): ?><p class="erro"><?php echo $erro; ?></p><?php endif; ?>
<form action="editar.php?id=<?php echo $id; ?>" method="post">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
    
    <label for="preco">Preço:</label>
    <input type="number" step="0.01" id="preco" name="preco" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>
    
    <label for="qtde">Quantidade:</label>
    <input type="number" id="qtde" name="qtde" value="<?php echo htmlspecialchars($produto['qtde']); ?>" required>
    
    <label for="codigo">Código:</label>
    <input type="text" id="codigo" name="codigo" value="<?php echo htmlspecialchars($produto['codigo']); ?>">
    
    <label for="categoria_id">Categoria:</label>
    <select id="categoria_id" name="categoria_id" required>
        <?php foreach ($categorias as $categoria): ?>
            <option value="<?php echo $categoria['id']; ?>" <?php echo ($produto['categoria_id'] == $categoria['id']) ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($categoria['nome']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <button type="submit" class="btn">Atualizar</button>
</form>
<?php include '../templates/footer.php'; ?>