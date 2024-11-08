<?php
include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/navbar.php';
include __DIR__ . '/../../includes/db.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento Cuidador - SafePet</title>
    <link rel="stylesheet" href="/assets/css/perfil/sidebar.css">
    <link rel="stylesheet" href="/assets/css/agendamentos.css">
</head>
<body>
    
    <!-- Sidebar -->
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="/img/favicon.ico" alt="SafePet">
                <h2>SafePet</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="#" onclick="showContent('conteudo-1', this)"><span class="material-symbols-outlined">account_circle</span><span class="item-description">Solicitações</span></a></li>
                    <li><a href="#" onclick="showContent('conteudo-2', this)"><span class="material-symbols-outlined">finance</span><span class="item-description">Em Breve</span></a></li>
                    <li><a href="#" onclick="showContent('conteudo-3', this)"><span class="material-symbols-outlined">info</span><span class="item-description">Cancelados</span></a></li>
                    <li><a href="#" onclick="showContent('conteudo-4', this)"><span class="material-symbols-outlined">lock</span><span class="item-description">Concluídos</span></a></li>
                </ul>
            </nav>
        </aside>

    <script src="/assets/js/perfil/sidebar.js"></script>
</body>
</html>
