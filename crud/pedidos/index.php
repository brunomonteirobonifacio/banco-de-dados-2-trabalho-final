<?php
include '../config/verifica_login.php';
include '../config/conexao.php';
include '../templates/header.php';

$sql = "SELECT p.id, p.data_hora, p.total, u.nome AS responsavel, f.nome AS fornecedor
        FROM pedido p
        JOIN usuario u ON p.responsavel_id = u.id
        JOIN fornecedor f ON p.fornecedor_id = f.id
        ORDER BY p.data_hora DESC";
$stmt = $pdo->query($sql);
?>

<h2>Relatório de Pedidos de Compra</h2>
<a href="adicionar.php" class="btn">Adicionar Novo Pedido</a>
<table>
    <thead>
        <tr>
            <th>ID Pedido</th>
            <th>Data/Hora</th>
            <th>Responsável</th>
            <th>Fornecedor</th>
            <th>Total</th>
            <th>Detalhes</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($pedido = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo htmlspecialchars($pedido['id']); ?></td>
            <td><?php echo date('d/m/Y H:i:s', strtotime($pedido['data_hora'])); ?></td>
            <td><?php echo htmlspecialchars($pedido['responsavel']); ?></td>
            <td><?php echo htmlspecialchars($pedido['fornecedor']); ?></td>
            <td>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
            <td><a href="ver.php?id=<?php echo $pedido['id']; ?>" class="btn-ver">Ver Itens</a></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php
include '../templates/footer.php';
?>