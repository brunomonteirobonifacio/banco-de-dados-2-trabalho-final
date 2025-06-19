<?php
include '../config/conexao.php';
include '../templates/header.php';

$sql = "SELECT v.id, v.data_hora, v.total, c.nome AS cliente_nome 
        FROM venda v 
        JOIN cliente c ON v.cliente_id = c.id
        ORDER BY v.data_hora DESC";
$stmt = $pdo->query($sql);
?>

<h2>Relatório de Vendas</h2>
<table>
    <thead>
        <tr>
            <th>ID Venda</th>
            <th>Data e Hora</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Detalhes</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($venda = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($venda['id']) . "</td>";
            echo "<td>" . date('d/m/Y H:i:s', strtotime($venda['data_hora'])) . "</td>";
            echo "<td>" . htmlspecialchars($venda['cliente_nome']) . "</td>";
            echo "<td>R$ " . number_format($venda['total'], 2, ',', '.') . "</td>";
            echo "<td><a href='ver.php?id=" . $venda['id'] . "' class='btn-ver'>Ver Itens</a></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php
include '../templates/footer.php';
?>