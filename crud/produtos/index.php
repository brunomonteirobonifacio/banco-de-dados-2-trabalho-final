<?php
include '../config/conexao.php';
include '../templates/header.php';

$sql = "SELECT p.id, p.nome, p.preco, p.qtde, pc.nome AS categoria 
        FROM produto p 
        JOIN produto_categoria pc ON p.categoria_id = pc.id 
        ORDER BY p.id";
$stmt = $pdo->query($sql);
?>

<h2>Lista de Produtos</h2>
<a href="adicionar.php" class="btn">Adicionar Novo Produto</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($produto = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($produto['id']) . "</td>";
            echo "<td>" . htmlspecialchars($produto['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($produto['categoria']) . "</td>";
            echo "<td>R$ " . number_format($produto['preco'], 2, ',', '.') . "</td>";
            echo "<td>" . htmlspecialchars($produto['qtde']) . "</td>";
            echo "<td>";
            echo "<a href='editar.php?id=" . $produto['id'] . "' class='btn-editar'>Editar</a> ";
            echo "<a href='remover.php?id=" . $produto['id'] . "' class='btn-remover' onclick='return confirm(\"Confirma a remoção?\")'>Remover</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php
include '../templates/footer.php';
?>