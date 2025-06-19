<?php
include '../config/conexao.php';
include '../templates/header.php';
$stmt = $pdo->query("SELECT id, nome FROM tipo_usuario ORDER BY id");
?>
<h2>Gestão de Tipos de Usuário</h2>
<a href="adicionar.php" class="btn">Adicionar Novo Tipo</a>
<table>
    <thead><tr><th>ID</th><th>Nome</th><th>Ações</th></tr></thead>
    <tbody>
        <?php while ($tipo = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo htmlspecialchars($tipo['id']); ?></td>
            <td><?php echo htmlspecialchars($tipo['nome']); ?></td>
            <td>
                <a href="editar.php?id=<?php echo $tipo['id']; ?>" class="btn-editar">Editar</a>
                <a href="remover.php?id=<?php echo $tipo['id']; ?>" class="btn-remover" onclick="return confirm('Confirma a remoção?')">Remover</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include '../templates/footer.php'; ?>