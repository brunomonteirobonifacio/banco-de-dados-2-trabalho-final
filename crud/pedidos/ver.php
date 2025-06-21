<?php
include '../config/verifica_login.php';
include '../config/conexao.php';
$pedido_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($pedido_id <= 0) { header('Location: index.php'); exit(); }

$sql_pedido = "SELECT p.id, p.data_hora, p.total, u.nome AS responsavel, f.nome AS fornecedor
               FROM pedido p
               JOIN usuario u ON p.responsavel_id = u.id
               JOIN fornecedor f ON p.fornecedor_id = f.id
               WHERE p.id = :id";
$stmt_pedido = $pdo->prepare($sql_pedido);
$stmt_pedido->execute([':id' => $pedido_id]);
$pedido = $stmt_pedido->fetch(PDO::FETCH_ASSOC);

if (!$pedido) { header('Location: index.php'); exit(); }

$sql_itens = "SELECT pi.qtde, pi.valor, pr.nome AS produto_nome
              FROM pedido_item pi
              JOIN produto pr ON pi.produto_id = pr.id
              WHERE pi.pedido_id = :id";
$stmt_itens = $pdo->prepare($sql_itens);
$stmt_itens->execute([':id' => $pedido_id]);

include '../templates/header.php';
?>
<h2>Detalhes do Pedido #<?php echo htmlspecialchars($pedido['id']); ?></h2>
<a href="index.php" class="btn-cancelar">Voltar para a Lista</a>
<hr>
<p><strong>Responsável:</strong> <?php echo htmlspecialchars($pedido['responsavel']); ?></p>
<p><strong>Fornecedor:</strong> <?php echo htmlspecialchars($pedido['fornecedor']); ?></p>
<p><strong>Data:</strong> <?php echo date('d/m/Y H:i:s', strtotime($pedido['data_hora'])); ?></p>
<p><strong>Valor Total:</strong> R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></p>
<h3>Itens do Pedido</h3>
<table>
    <thead><tr><th>Produto</th><th>Quantidade</th><th>Valor Unitário</th><th>Subtotal</th></tr></thead>
    <tbody>
        <?php while ($item = $stmt_itens->fetch(PDO::FETCH_ASSOC)): ?>
        <?php $subtotal = $item['qtde'] * $item['valor']; ?>
        <tr>
            <td><?php echo htmlspecialchars($item['produto_nome']); ?></td>
            <td><?php echo htmlspecialchars($item['qtde']); ?></td>
            <td>R$ <?php echo number_format($item['valor'], 2, ',', '.'); ?></td>
            <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include '../templates/footer.php'; ?>