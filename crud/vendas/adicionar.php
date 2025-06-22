<?php
include '../config/verifica_login.php';
include '../config/conexao.php';

$erro = '';
// --- LÓGICA PARA SALVAR A VENDA ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_POST['cliente_id'];
    
    $produtos_venda = $_POST['produto_id'] ?? [];
    $quantidades_venda = $_POST['qtde'] ?? [];
    

    $pdo->beginTransaction();

    try {
        if (empty($produtos_venda)) {
            throw new Exception("É necessário adicionar pelo menos um produto à venda.");
        }

        
        foreach ($produtos_venda as $index => $produto_id) {
            $qtde_solicitada = $quantidades_venda[$index];


            $sql_estoque = "SELECT nome, qtde FROM produto WHERE id = :id FOR UPDATE";
            $stmt_estoque = $pdo->prepare($sql_estoque);
            $stmt_estoque->execute([':id' => $produto_id]);
            $produto_info = $stmt_estoque->fetch(PDO::FETCH_ASSOC);

            if ($produto_info['qtde'] < $qtde_solicitada) {
 
                throw new Exception("Estoque insuficiente para o produto '{$produto_info['nome']}'. Disponível: {$produto_info['qtde']}, Solicitado: {$qtde_solicitada}.");
            }
        }


        $total_venda = 0;
        $valores_venda = [];
        

        $placeholders = implode(',', array_fill(0, count($produtos_venda), '?'));
        $sql_precos = "SELECT id, preco FROM produto WHERE id IN ($placeholders)";
        $stmt_precos = $pdo->prepare($sql_precos);
        $stmt_precos->execute($produtos_venda);
        $precos_produtos = $stmt_precos->fetchAll(PDO::FETCH_KEY_PAIR);
        
        foreach ($produtos_venda as $index => $produto_id) {
            $preco_unitario = $precos_produtos[$produto_id];
            $valores_venda[$index] = $preco_unitario;
            $total_venda += $quantidades_venda[$index] * $preco_unitario;
        }

        $sql_venda = "INSERT INTO venda (data_hora, total, cliente_id) VALUES (NOW(), :total, :cliente_id)";
        $stmt_venda = $pdo->prepare($sql_venda);
        $stmt_venda->execute([
            ':total' => $total_venda,
            ':cliente_id' => $cliente_id
        ]);
        $venda_id = $pdo->lastInsertId();

        $sql_item = "INSERT INTO venda_item (venda_id, produto_id, valor, qtde) VALUES (:venda_id, :produto_id, :valor, :qtde)";
        $stmt_item = $pdo->prepare($sql_item);

        $sql_update_estoque = "UPDATE produto SET qtde = qtde - :qtde WHERE id = :id";
        $stmt_update_estoque = $pdo->prepare($sql_update_estoque);

        foreach ($produtos_venda as $index => $produto_id) {

            $stmt_item->execute([
                ':venda_id' => $venda_id,
                ':produto_id' => $produto_id,
                ':valor' => $valores_venda[$index],
                ':qtde' => $quantidades_venda[$index]
            ]);

            $stmt_update_estoque->execute([
                ':qtde' => $quantidades_venda[$index],
                ':id' => $produto_id
            ]);
        }


        $pdo->commit();
        header('Location: index.php?status=sucesso');
        exit();

    } catch (Exception $e) {

        $pdo->rollBack();
        $erro = $e->getMessage();
    }
}


$clientes_stmt = $pdo->query("SELECT id, nome FROM cliente ORDER BY nome");
$clientes = $clientes_stmt->fetchAll(PDO::FETCH_ASSOC);


$produtos_stmt = $pdo->query("SELECT id, nome, preco, qtde FROM produto WHERE qtde > 0 ORDER BY nome");
$lista_produtos = $produtos_stmt->fetchAll(PDO::FETCH_ASSOC);

include '../templates/header.php';
?>

<h2>Adicionar Nova Venda</h2>

<?php if ($erro): ?><p class="erro"><?php echo "Erro ao salvar a venda: " . htmlspecialchars($erro); ?></p><?php endif; ?>

<form action="adicionar.php" method="post" id="form-venda">
    <fieldset>
        <legend>Dados da Venda</legend>
        <label for="cliente_id">Cliente:</label>
        <select id="cliente_id" name="cliente_id" required>
            <option value="">Selecione um cliente</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?php echo $cliente['id']; ?>"><?php echo htmlspecialchars($cliente['nome']); ?></option>
            <?php endforeach; ?>
        </select>
    </fieldset>

    <fieldset>
        <legend>Itens da Venda</legend>
        <table id="tabela-itens">
            <thead>
                <tr>
                    <th>Produto (Estoque)</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                    <th>Subtotal</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
        </table>
        <button type="button" id="btn-adicionar-item" class="btn">Adicionar Produto</button>
    </fieldset>

    <div class="total-pedido">
        <h3>Total da Venda: R$ <span id="total-venda-display">0.00</span></h3>
    </div>
    
    <button type="submit" class="btn">Finalizar Venda</button>
    <a href="index.php" class="btn-cancelar">Cancelar</a>
</form>

<style> 
    #tabela-itens th, #tabela-itens td { vertical-align: middle; }
    #tabela-itens input, #tabela-itens select { width: 100%; box-sizing: border-box; }
    #tabela-itens .valor-unitario { background-color: #e9ecef; } 
    .total-pedido { text-align: right; font-size: 1.5rem; margin-top: 20px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnAdicionar = document.getElementById('btn-adicionar-item');
    const tabelaItensBody = document.querySelector('#tabela-itens tbody');
    const totalDisplay = document.getElementById('total-venda-display');
    
    const produtosDisponiveis = <?php echo json_encode($lista_produtos); ?>;

    function adicionarNovaLinha() {
        const newRow = document.createElement('tr');
        
        let optionsHtml = '<option value="">Selecione...</option>';
        produtosDisponiveis.forEach(p => {
            optionsHtml += `<option value="${p.id}" data-preco="${p.preco}" data-estoque="${p.qtde}">${p.nome} (Estoque: ${p.qtde})</option>`;
        });

        newRow.innerHTML = `
            <td><select name="produto_id[]" class="produto-select" required>${optionsHtml}</select></td>
            <td><input type="number" name="qtde[]" value="1" min="1" required class="item-recalc"></td>
            <td><input type="text" name="valor[]" readonly class="valor-unitario"></td>
            <td class="subtotal">R$ 0.00</td>
            <td><button type="button" class="btn-remover btn-remover-item">Remover</button></td>
        `;
        
        tabelaItensBody.appendChild(newRow);
    }

    function calcularTotais() {
        let totalGeral = 0;
        const rows = tabelaItensBody.querySelectorAll('tr');
        rows.forEach(row => {
            const qtdeInput = row.querySelector('input[name="qtde[]"]');
            const valorInput = row.querySelector('input[name="valor[]"]');
            const subtotalCell = row.querySelector('.subtotal');
            
            const qtde = parseFloat(qtdeInput.value) || 0;
            const valor = parseFloat(valorInput.value) || 0;
            const subtotal = qtde * valor;
            
            subtotalCell.textContent = 'R$ ' + subtotal.toFixed(2).replace('.', ',');
            totalGeral += subtotal;

            const selectProduto = row.querySelector('.produto-select');
            const estoqueDisponivel = parseInt(selectProduto.options[selectProduto.selectedIndex].getAttribute('data-estoque')) || 0;
            if (qtde > estoqueDisponivel) {
                qtdeInput.style.border = '2px solid red';
            } else {
                qtdeInput.style.border = '1px solid #ccc';
            }
        });
        totalDisplay.textContent = totalGeral.toFixed(2).replace('.', ',');
    }

    btnAdicionar.addEventListener('click', adicionarNovaLinha);

    tabelaItensBody.addEventListener('change', function(e) {
        if (e.target.classList.contains('produto-select')) {
            const option = e.target.options[e.target.selectedIndex];
            const preco = option.getAttribute('data-preco');
            const valorInput = e.target.closest('tr').querySelector('.valor-unitario');
            valorInput.value = parseFloat(preco || 0).toFixed(2);
        }
        calcularTotais();
    });
    
    tabelaItensBody.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-recalc')) {
            calcularTotais();
        }
    });

    tabelaItensBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remover-item')) {
            e.target.closest('tr').remove();
            calcularTotais();
        }
    });

    adicionarNovaLinha();
});
</script>

<?php
include '../templates/footer.php';
?>