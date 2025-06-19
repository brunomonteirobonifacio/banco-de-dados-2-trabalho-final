<?php
include '../config/conexao.php';

$venda_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($venda_id <= 0) {
    header('Location: index.php');
    exit();
}

// Busca os dados principais da venda
$sql_venda = "SELECT v.id, v.data_hora, v.total, c.nome AS cliente_nome, c.cpf
              FROM venda v 
              JOIN cliente c ON v.cliente_id = c.id
              WHERE v.id = :id";
$stmt_venda = $pdo->prepare($sql_venda);
$stmt_venda->execute([':id' => $venda_id]);
$venda = $stmt_venda->fetch(PDO::FETCH_ASSOC);

// Se não achar a venda, volta para a lista
if (!$venda) {
    header('Location: index.php');
    exit();
}

// Busca os itens da venda
$sql_itens = "SELECT vi.qtde, vi.valor, p.nome AS produto_nome
              FROM venda_item vi
              JOIN produto p ON vi.produto_id = p.id
              WHERE vi.venda_id = :id";
$stmt_itens = $pdo->prepare($sql_itens);
$stmt_itens->execute([':id' => $venda_id]);

include '../templates/header.php';
?>

<h2>Detalhes da Venda #<?php echo htmlspecialchars($venda['id']); ?></h2>
<a href="index.php" class="btn-cancelar">Voltar para a Lista</a>
<hr>

<h3>Dados do Cliente</h3>
<p><strong>Cliente:</strong> <?php echo htmlspecialchars($venda['cliente_nome']); ?></p>
<p><strong>CPF:</strong> <?php echo htmlspecialchars($venda['cpf']); ?></p>

<h3>Dados da Venda</h3>
<p><strong>Data:</strong> <?php echo date('d/m/Y H:i:s', strtotime($venda['data_hora'])); ?></p>
<p><strong>Valor Total:</strong> R$ <?php echo number_format($venda['total'], 2, ',', '.'); ?></p>

<h3>Itens Vendidos</h3>
<table>
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Valor Unitário</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($item = $stmt_itens->fetch(PDO::FETCH_ASSOC)) {
            $subtotal = $item['qtde'] * $item['valor'];
            echo "<tr>";
            echo "<td>" . htmlspecialchars($item['produto_nome']) . "</td>";
            echo "<td>" . htmlspecialchars($item['qtde']) . "</td>";
            echo "<td>R$ " . number_format($item['valor'], 2, ',', '.') . "</td>";
            echo "<td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php
include '../templates/footer.php';
?>