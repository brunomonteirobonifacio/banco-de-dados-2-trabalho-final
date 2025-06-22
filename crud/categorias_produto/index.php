<?php
include '../config/verifica_login.php'; 
include '../config/conexao.php';      
include '../templates/header.php';      

$stmt = $pdo->query("SELECT id, nome FROM produto_categoria ORDER BY id");
?>
<h2>Gestão de Categorias de Produto</h2>
<a href="adicionar.php" class="btn">Adicionar Nova Categoria</a>
<table>
    <thead><tr><th>ID</th><th>Nome</th><th>Ações</th></tr></thead>
    <tbody>
        <?php while ($cat = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo htmlspecialchars($cat['id']); ?></td>
            <td><?php echo htmlspecialchars($cat['nome']); ?></td>
            <td>
                <a href="editar.php?id=<?php echo $cat['id']; ?>" class="btn-editar">Editar</a>
                <a href="remover.php?id=<?php echo $cat['id']; ?>" class="btn-remover" onclick="return confirm('Confirma a remoção?')">Remover</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include '../templates/footer.php'; ?>