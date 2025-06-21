<?php
include '../config/verifica_login.php';
include '../config/conexao.php';
include '../templates/header.php';
$stmt = $pdo->query("SELECT id, nome FROM setor ORDER BY id");
?>
<h2>Gestão de Setores</h2>
<a href="adicionar.php" class="btn">Adicionar Novo Setor</a>
<table>
    <thead><tr><th>ID</th><th>Nome</th><th>Ações</th></tr></thead>
    <tbody>
        <?php while ($setor = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo htmlspecialchars($setor['id']); ?></td>
            <td><?php echo htmlspecialchars($setor['nome']); ?></td>
            <td>
                <a href="editar.php?id=<?php echo $setor['id']; ?>" class="btn-editar">Editar</a>
                <a href="remover.php?id=<?php echo $setor['id']; ?>" class="btn-remover" onclick="return confirm('Confirma a remoção?')">Remover</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include '../templates/footer.php'; ?>