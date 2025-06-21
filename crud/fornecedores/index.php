<?php

include '../config/verifica_login.php'; // 1. Inicia a sessão e protege
include '../config/conexao.php';      // 2. Define BASE_URL e conecta ao DB
include '../templates/header.php';      // 3. Mostra o cabeçalho


$stmt = $pdo->query("SELECT id, nome, cnpj FROM fornecedor ORDER BY nome");
?>

<h2>Lista de Fornecedores</h2>
<a href="adicionar.php" class="btn">Adicionar Novo Fornecedor</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>CNPJ</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($fornecedor = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($fornecedor['id']) . "</td>";
            echo "<td>" . htmlspecialchars($fornecedor['nome']) . "</td>";
            echo "<td>" . htmlspecialchars($fornecedor['cnpj']) . "</td>";
            echo "<td>";
            echo "<a href='editar.php?id=" . $fornecedor['id'] . "' class='btn-editar'>Editar</a> ";
            echo "<a href='remover.php?id=" . $fornecedor['id'] . "' class='btn-remover' onclick='return confirm(\"Confirma a remoção?\")'>Remover</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php
include '../templates/footer.php';
?>