<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../includes/functions.php';
include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/db.php';

$tutor_id = $_SESSION['id'];
$tutor = getTutorProfile($mysqli, $tutor_id);
$pets = getPetsByTutor($mysqli, $tutor_id);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="/img/favicon.ico" alt="">
                <h2>SafePet</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="#1" onclick="showContent('conteudo-1', this)"><span class="material-symbols-outlined">account_circle</span><span class="item-description">Meu Perfil</span></a></li>
                    <li><a href="#2" onclick="showContent('conteudo-2', this)"><span class="material-symbols-outlined">pets</span><span class="item-description">Meus Pets</span></a></li>
                    <li>
                        <a href="#" onclick="alternarSubmenu(event)"><span class="material-symbols-outlined">person</span><span class="item-description">Conta</span></a>
                        <ul class="submenu">
                            <li><a href="#3" onclick="showContent('conteudo-3', this)" data-sub-content="info-pessoais">Informações Pessoais</a></li>
                            <li><a href="#4" onclick="showContent('conteudo-3', this)" data-sub-content="trocar-senha">Trocar Senha</a></li>
                            <li><a href="#5" onclick="showContent('conteudo-3', this)" data-sub-content="excluir-conta">Excluir Conta</a></li>
                        </ul>
                    </li>
                    <li><a href="#6" onclick="showContent('conteudo-4', this)"><span class="material-symbols-outlined">info</span><span class="item-description">Suporte</span></a></li>
                    <li><a href="#7" onclick="showContent('conteudo-5', this)"><span class="material-symbols-outlined">lock</span><span class="item-description">Política de Privacidade</span></a></li>
                </ul>
            </nav>
        </aside>
        <main class="conteudo">
            <?php 
            require 'perfil-tutor.php';
            require 'pets-tutor.php';
            require '../shared/conta.php';
            require 'suporte-tutor.php';
            require '../shared/politica-privacidade.php';  
            ?>
        </main>
    </div>
    <script>
        const tipoUsuario = "<?php echo $_SESSION['tipo_usuario']; ?>";
    </script>
    <script type="module" src="main.js"></script>
</body>