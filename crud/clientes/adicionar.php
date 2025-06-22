<?php
include '../config/verifica_login.php';
include '../config/conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Inicia uma transação para garantir a consistência dos dados
    $pdo->beginTransaction();

    try {
        // --- LÓGICA DO ENDEREÇO GENÉRICO ---
  
        $cep_padrao = '00000000';
        $rua_padrao = 'Não informado';
        $bairro_padrao = 'Não informado';
        $numero_padrao = 0;

        // 1. Inserir o Endereço Genérico no banco
        $sql_endereco = "INSERT INTO endereco (cidade_id, cep, rua, bairro, numero, complemento) VALUES (:cidade_id, :cep, :rua, :bairro, :numero, :complemento)";
        $stmt_endereco = $pdo->prepare($sql_endereco);
        $stmt_endereco->execute([
            ':cidade_id'   => $_POST['cidade_id'], 
            ':cep'         => $cep_padrao,         
            ':rua'         => $rua_padrao,         
            ':bairro'      => $bairro_padrao,      
            ':numero'      => $numero_padrao,    
            ':complemento' => null                 
        ]);
        
        
        $endereco_id = $pdo->lastInsertId();

      
        $sql_cliente = "INSERT INTO cliente (nome, cpf, fone, email, endereco_id) VALUES (:nome, :cpf, :fone, :email, :endereco_id)";
        $stmt_cliente = $pdo->prepare($sql_cliente);
        $stmt_cliente->execute([
            ':nome'        => $_POST['nome'],
            ':cpf'         => $_POST['cpf'],
            ':fone'        => $_POST['fone'],
            ':email'       => $_POST['email'],
            ':endereco_id' => $endereco_id
        ]);

   
        $pdo->commit();
        
        header('Location: index.php?status=sucesso');
        exit();

    } catch (PDOException $e) {
    
        $pdo->rollBack();
        if ($e->getCode() == '23505') {
            $erro = "Erro: O CPF informado já está cadastrado.";
        } else {
            $erro = "Erro ao adicionar cliente: " . $e->getMessage();
        }
    }
}
include '../templates/header.php';
?>

<h2>Adicionar Novo Cliente</h2>
<?php if ($erro): ?><p class="erro"><?php echo $erro; ?></p><?php endif; ?>

<form action="adicionar.php" method="post">
    <fieldset>
        <legend>Dados Pessoais</legend>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="cpf">CPF (11 dígitos):</label>
        <input type="text" id="cpf" name="cpf" maxlength="11" required>

        <label for="fone">Telefone (11 dígitos):</label>
        <input type="text" id="fone" name="fone" maxlength="11">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </fieldset>

    <fieldset>
        <legend>Localização</legend>
        
        <label for="estado">Estado:</label>
        <select id="estado" name="estado_id" required>
            <option value="">Carregando...</option>
        </select>

        <label for="cidade">Cidade:</label>
        <select id="cidade" name="cidade_id" required disabled>
            <option value="">Selecione um estado primeiro</option>
        </select>
    </fieldset>

    <button type="submit" class="btn">Salvar Cliente</button>
    <a href="index.php" class="btn-cancelar">Cancelar</a>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const estadoSelect = document.getElementById('estado');
    const cidadeSelect = document.getElementById('cidade');

    fetch('../api/get_estados.php')
        .then(response => response.json())
        .then(data => {
            estadoSelect.innerHTML = '<option value="">Selecione um estado</option>';
            data.forEach(estado => {
                const option = document.createElement('option');
                option.value = estado.id;
                option.textContent = estado.nome;
                estadoSelect.appendChild(option);
            });
        });

    estadoSelect.addEventListener('change', function() {
        const estadoId = this.value;
        cidadeSelect.innerHTML = '<option value="">Carregando cidades...</option>';
        cidadeSelect.disabled = true;

        if (estadoId) {
            fetch(`../api/get_cidades.php?estado_id=${estadoId}`)
                .then(response => response.json())
                .then(data => {
                    cidadeSelect.innerHTML = '<option value="">Selecione uma cidade</option>';
                    data.forEach(cidade => {
                        const option = document.createElement('option');
                        option.value = cidade.id;
                        option.textContent = cidade.nome;
                        cidadeSelect.appendChild(option);
                    });
                    cidadeSelect.disabled = false;
                });
        } else {
            cidadeSelect.innerHTML = '<option value="">Selecione um estado primeiro</option>';
            cidadeSelect.disabled = true;
        }
    });
});
</script>

<?php
include '../templates/footer.php';
?>