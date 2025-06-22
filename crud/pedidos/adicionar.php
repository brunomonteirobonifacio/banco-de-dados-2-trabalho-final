<?php
include '../config/verifica_login.php';
include '../config/conexao.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fornecedor_id = $_POST['fornecedor_id'];
    $responsavel_id = $_SESSION['usuario_id']; 
    

    $produtos = $_POST['produto_id'] ?? [];
    $quantidades = $_POST['qtde'] ?? [];
    $valores = $_POST['valor'] ?? [];


    $total_pedido = 0;
    if (!empty($produtos)) {
        for ($i = 0; $i < count($produtos); $i++) {
            if (isset($quantidades[$i]) && isset($valores[$i])) {
                $total_pedido += $quantidades[$i] * $valores[$i];
            }
        }
    }


    $pdo->beginTransaction();

    try {

        $sql_pedido = "INSERT INTO pedido (data_hora, total, responsavel_id, fornecedor_id) VALUES (NOW(), :total, :responsavel_id, :fornecedor_id)";
        $stmt_pedido = $pdo->prepare($sql_pedido);
        $stmt_pedido->execute([
            ':total' => $total_pedido,
            ':responsavel_id' => $responsavel_id,
            ':fornecedor_id' => $fornecedor_id
        ]);


        $pedido_id = $pdo->lastInsertId();

        if (!empty($produtos)) {
            $sql_item = "INSERT INTO pedido_item (pedido_id, produto_id, valor, qtde) VALUES (:pedido_id, :produto_id, :valor, :qtde)";
            $stmt_item = $pdo->prepare($sql_item);

            for ($i = 0; $i < count($produtos); $i++) {
                if (isset($produtos[$i]) && isset($quantidades[$i]) && isset($valores[$i])) {
                    $stmt_item->execute([
                        ':pedido_id' => $pedido_id,
                        ':produto_id' => $produtos[$i],
                        ':valor' => $valores[$i],
                        ':qtde' => $quantidades[$i]
                    ]);
                }
            }
        }


        $pdo->commit();
        header('Location: index.php?status=sucesso');
        exit();

    } catch (Exception $e) {

        $pdo->rollBack();
        die("Erro ao salvar o pedido: " . $e->getMessage());
    }
}



$fornecedores_stmt = $pdo->query("SELECT id, nome FROM fornecedor ORDER BY nome");
$fornecedores = $fornecedores_stmt->fetchAll(PDO::FETCH_ASSOC);

$produtos_stmt = $pdo->query("SELECT id, nome, preco FROM produto ORDER BY nome");
$lista_produtos = $produtos_stmt->fetchAll(PDO::FETCH_ASSOC);


include '../templates/header.php';
?>

<h2>Adicionar Novo Pedido de Compra</h2>

<form action="adicionar.php" method="post" id="form-pedido">
    <fieldset>
        <legend>Dados Gerais</legend>
        <label for="fornecedor_id">Fornecedor:</label>
        <select id="fornecedor_id" name="fornecedor_id" required>
            <option value="">Selecione um fornecedor</option>
            <?php foreach ($fornecedores as $fornecedor): ?>
                <option value="<?php echo $fornecedor['id']; ?>"><?php echo htmlspecialchars($fornecedor['nome']); ?></option>
            <?php endforeach; ?>
        </select>
        <p><strong>Responsável:</strong> <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></p>
    </fieldset>

    <fieldset>
        <legend>Itens do Pedido</legend>
        <table id="tabela-itens" class="table-itens">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
        </table>
        <button type="button" id="btn-adicionar-item" class="btn">Adicionar Produto</button>
    </fieldset>

    <div class="total-pedido">
        <h3>Total do Pedido: R$ <span id="total-pedido-display">0.00</span></h3>
    </div>
    
    <button type="submit" class="btn">Salvar Pedido</button>
    <a href="index.php" class="btn-cancelar">Cancelar</a>
</form>

<style>

.table-itens th, .table-itens td {
    vertical-align: middle;
}
.table-itens input, .table-itens select {
    width: 100%;
}
.total-pedido {
    text-align: right;
    font-size: 1.5rem;
    margin-top: 20px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnAdicionar = document.getElementById('btn-adicionar-item');
    const tabelaItensBody = document.querySelector('#tabela-itens tbody');
    const totalDisplay = document.getElementById('total-pedido-display');
    
    const produtosDisponiveis = <?php echo json_encode($lista_produtos); ?>;

    function adicionarNovaLinha() {
        const newRow = document.createElement('tr');
        
        let optionsHtml = '<option value="">Selecione...</option>';
        produtosDisponiveis.forEach(produto => {
            optionsHtml += `<option value="${produto.id}" data-preco="${produto.preco}">${produto.nome}</option>`;
        });

        newRow.innerHTML = `
            <td>
                <select name="produto_id[]" class="produto-select" required>${optionsHtml}</select>
            </td>
            <td>
                <input type="number" name="qtde[]" value="1" min="1" required class="item-recalc">
            </td>
            <td>
                <input type="number" name="valor[]" step="0.01" required class="item-recalc">
            </td>
            <td>
                <button type="button" class="btn-remover btn-remover-item">Remover</button>
            </td>
        `;
        
        tabelaItensBody.appendChild(newRow);
    }

    function calcularTotal() {
        let total = 0;
        const rows = tabelaItensBody.querySelectorAll('tr');
        rows.forEach(row => {
            const qtde = parseFloat(row.querySelector('input[name="qtde[]"]').value) || 0;
            const valor = parseFloat(row.querySelector('input[name="valor[]"]').value) || 0;
            total += qtde * valor;
        });
        totalDisplay.textContent = total.toFixed(2).replace('.', ',');
    }

    btnAdicionar.addEventListener('click', adicionarNovaLinha);

    tabelaItensBody.addEventListener('change', function(e) {

        if (e.target && e.target.classList.contains('produto-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const preco = selectedOption.getAttribute('data-preco');
            const valorInput = e.target.closest('tr').querySelector('input[name="valor[]"]');
            valorInput.value = preco || '0.00';
            calcularTotal();
        }

        if (e.target && e.target.classList.contains('item-recalc')) {
            calcularTotal();
        }
    });
    
    tabelaItensBody.addEventListener('keyup', function(e) {

        if (e.target && e.target.classList.contains('item-recalc')) {
            calcularTotal();
        }
    });

    tabelaItensBody.addEventListener('click', function(e) {

        if (e.target && e.target.classList.contains('btn-remover-item')) {
            e.target.closest('tr').remove();
            calcularTotal();
        }
    });


    adicionarNovaLinha();
});
</script>

<?php
include '../templates/footer.php';
?>