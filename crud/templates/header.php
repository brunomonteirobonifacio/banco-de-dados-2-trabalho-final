<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestão</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/estilo.css">
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <h1>Sistema de Gestão</h1>
            
            <?php if (isset($_SESSION['usuario_nome'])): ?>
                <div class="user-info">
                    <span>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
                    <a href="<?php echo BASE_URL; ?>/logout.php" class="btn-sair">Sair</a>
                </div>
            <?php endif; ?>
        </div>
        
        <nav>
            <a href="<?php echo BASE_URL; ?>/index.php">Início</a>
            <a href="<?php echo BASE_URL; ?>/clientes/">Clientes</a>
            <a href="<?php echo BASE_URL; ?>/produtos/">Produtos</a>
            <a href="<?php echo BASE_URL; ?>/fornecedores/">Fornecedores</a>
            <a href="<?php echo BASE_URL; ?>/usuarios/">Usuários</a>
            <a href="<?php echo BASE_URL; ?>/vendas/">Vendas</a>
            <a href="<?php echo BASE_URL; ?>/pedidos/">Pedidos</a>
        </nav>
    </header>
    <div class="container">