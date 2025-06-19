<?php
include '../config/conexao.php';

// Busca todas as categorias para o dropdown
$categorias_stmt = $pdo->query("SELECT id, nome FROM produto_categoria ORDER BY nome");
$categorias = $categorias_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $qtde = $_POST['qtde'];
    $categoria_id = $_POST['categoria_id'];
    $codigo = $_POST['codigo'] ?? null;

    $sql = "INSERT INTO produto (nome, preco, qtde, categoria_id, codigo) VALUES (:nome, :preco, :qtde, :categoria_id, :codigo)";
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([
            ':nome' => $nome,
            ':preco' => $preco,
            ':qtde' => $qtde,
            ':categoria_id' => $categoria_id,
            ':codigo' => $codigo
        ]);
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        $erro = "Erro ao adicionar produto: " . $e->getMessage();
    }
}

include '../templates/header.php';
?>

<h2>Adicionar Novo Produto</h2>
<?php if (isset($erro)): ?><p class="erro"><?php echo $erro; ?></p><?php endif; ?>

<form action="adicionar.php" method="post">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" required>

    <label for="preco">Preço (ex: 49.90):</label>
    <input type="number" step="0.01" id="preco" name="preco" required>

    <label for="qtde">Quantidade em Estoque:</label>
    <input type="number" id="qtde" name="qtde" required>

    <label for="codigo">Código :</label>
    <input type="text" id="codigo" name="codigo">
    
    <label for="categoria_id">Categoria:</label>
    <select id="categoria_id" name="categoria_id" required>
        <option value="">Selecione uma categoria</option>
        <?php foreach ($categorias as $categoria): ?>
            <option value="<?php echo $categoria['id']; ?>">
                <?php echo htmlspecialchars($categoria['nome']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <button type="submit" class="btn">Salvar</button>
</form>

<?php
include '../templates/footer.php';
?>