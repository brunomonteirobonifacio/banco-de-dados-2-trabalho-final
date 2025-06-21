<?php
include '../config/verifica_login.php';
include '../config/conexao.php';
include '../templates/header.php';
$sql = "SELECT u.id, u.nome, u.email, s.nome AS setor, tu.nome AS tipo
        FROM usuario u
        JOIN setor s ON u.setor_id = s.id
        JOIN tipo_usuario tu ON u.tipo_usuario_id = tu.id
        ORDER BY u.id";
$stmt = $pdo->query($sql);
?>
<h2>Gestão de Usuários</h2>
<a href="adicionar.php" class="btn">Adicionar Novo Usuário</a>
<table>
    <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Setor</th><th>Tipo</th><th>Ações</th></tr></thead>
    <tbody>
        <?php while ($usuario = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo htmlspecialchars($usuario['id']); ?></td>
            <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
            <td><?php echo htmlspecialchars($usuario['setor']); ?></td>
            <td><?php echo htmlspecialchars($usuario['tipo']); ?></td>
            <td>
                <a href="editar.php?id=<?php echo $usuario['id']; ?>" class="btn-editar">Editar</a>
                <a href="remover.php?id=<?php echo $usuario['id']; ?>" class="btn-remover" onclick="return confirm('Confirma a remoção?')">Remover</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include '../templates/footer.php'; ?>