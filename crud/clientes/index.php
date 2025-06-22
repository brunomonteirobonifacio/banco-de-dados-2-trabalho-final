<?php
include '../config/verifica_login.php'; 
include '../config/conexao.php';      
include '../templates/header.php';    


// 4. A lógica da página começa aqui...
$sql = "SELECT cl.id, cl.nome, cl.cpf, cl.email, ci.nome AS cidade, es.uf AS estado
        FROM cliente cl
        JOIN endereco en ON cl.endereco_id = en.id
        JOIN cidade ci ON en.cidade_id = ci.id
        JOIN estado es ON ci.estado_id = es.id
        ORDER BY cl.id";
$stmt = $pdo->query($sql);
?>
<h2>Gestão de Clientes</h2>
<a href="adicionar.php" class="btn">Adicionar Novo Cliente</a>
<table>
    <thead>
        <tr>
            <th>ID</th> <th>Nome</th> <th>CPF</th> <th>Email</th> <th>Cidade/UF</th> <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($cliente = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($cliente['id']) . "</td>";
            echo "<td>" . htmlspecialchars($cliente['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($cliente['cpf']) . "</td>";
            echo "<td>" . htmlspecialchars($cliente['email']) . "</td>";
            echo "<td>" . htmlspecialchars($cliente['cidade']) . "/" . htmlspecialchars($cliente['estado']) . "</td>";
            echo "<td>";
            echo "<a href='editar.php?id=" . $cliente['id'] . "' class='btn-editar'>Editar</a> ";
            echo "<a href='remover.php?id=" . $cliente['id'] . "' class='btn-remover' onclick='return confirm(\"Confirma a remoção?\")'>Remover</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php 
// Inclui o rodapé da página
include '../templates/footer.php'; 
?>