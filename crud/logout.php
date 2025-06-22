<?php
include 'config/verifica_login.php';
session_start(); 
session_destroy(); 


require_once 'config/conexao.php';
header('Location: ' . BASE_URL . '/login.php'); // Redireciona para a página de login
exit();