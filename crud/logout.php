<?php
include 'config/verifica_login.php';
session_start(); // Inicia a sessão
session_destroy(); // Destrói todos os dados da sessão

// Inclui a conexão só para pegar a BASE_URL para o redirecionamento
require_once 'config/conexao.php';
header('Location: ' . BASE_URL . '/login.php'); // Redireciona para a página de login
exit();