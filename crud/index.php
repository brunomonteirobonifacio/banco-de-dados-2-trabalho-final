<?php

include 'config/verifica_login.php';
include 'config/conexao.php';
include 'templates/header.php';
?>


<h2>Bem-vindo ao Sistema de Gestão</h2>
<p>Selecione uma das opções abaixo para começar:</p>

<div class="dashboard-links">
    <a href="clientes/">Gerenciar Clientes</a>
    <a href="produtos/">Gerenciar Produtos</a>
    <a href="fornecedores/">Gerenciar Fornecedores</a>
    <a href="usuarios/">Gerenciar Usuários</a>
    <a href="categorias_produto/">Gerenciar Categorias</a>
    <a href="setores/">Gerenciar Setores</a>
    <a href="tipos_usuario/">Gerenciar Tipos de Usuário</a>
    <a href="vendas/">Relatório de Vendas</a>
    <a href="pedidos/">Relatório de Pedidos</a>
</div>

<?php 

include 'templates/footer.php'; 
?>