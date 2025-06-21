<?php
// É crucial iniciar a sessão em todas as páginas que a utilizam
session_start();

// Inclui o arquivo de conexão
require_once 'config/conexao.php';

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha_digitada = $_POST['senha'];

    // Prepara a consulta para buscar o usuário pelo e-mail
    $sql = "SELECT id, nome, cpf, tipo_usuario_id FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se o usuário foi encontrado
    if ($usuario) {
        // Pega os 6 primeiros dígitos do CPF armazenado no banco
        $senha_correta = substr($usuario['cpf'], 0, 6);

        // Compara a senha digitada com os 6 primeiros dígitos do CPF
        if ($senha_digitada == $senha_correta) {
            // Login bem-sucedido!
            // Armazena os dados do usuário na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo_usuario_id'];

            // Redireciona para a página principal do sistema
            header('Location: index.php');
            exit();
        }
    }

    // Se o usuário não foi encontrado ou a senha estava incorreta,
    // redireciona de volta para a página de login com uma mensagem de erro.
    header('Location: login.php?erro=1');
    exit();
} else {
    // Se alguém tentar acessar este arquivo diretamente, redireciona para o login
    header('Location: login.php');
    exit();
}