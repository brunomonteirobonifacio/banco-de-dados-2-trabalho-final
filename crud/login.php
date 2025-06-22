<?php
session_start();

// Se o usuário já estiver logado, redireciona para a página principal
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

// Inclui a definição da URL base para o link do CSS
require_once 'config/conexao.php'; 

// Verifica se há uma mensagem de erro vinda do processamento
$erro = isset($_GET['erro']) ? 'E-mail ou senha inválidos.' : '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gestão</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/estilo.css">
    <style>
        /* Estilos específicos para a página de login */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .login-container {
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Acessar o Sistema</h1>
        <p>Use seu e-mail e os 6 primeiros dígitos do seu CPF como senha.</p>
        
        <?php if ($erro): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form action="processa_login.php" method="post">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            
            <button type="submit" class="btn">Entrar</button>
        </form>
    </div>
</body>
</html>