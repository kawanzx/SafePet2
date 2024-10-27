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
    <link rel="stylesheet" href="/assets/css/agendamentos.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Agendamento</h2>
        <a href="#" onclick="showSection('em_breve')">Em Breve</a>
        <a href="#" onclick="showSection('concluidos')">Concluídos</a>
        <a href="#" onclick="showSection('cancelados')">Cancelados</a>
        <a href="#" onclick="showSection('solicitacoes')">Solicitações</a>
    </div>

    <!-- Conteúdo -->
    <div class="content">
        <div id="em_breve" class="section active">
            <h2>Em Breve</h2>
            <p>Aqui estão as solicitações agendadas que ainda não foram concluídas.</p>
        </div>

        <div id="concluidos" class="section">
            <h2>Concluídos</h2>
            <p>Aqui estão as solicitações que você já concluiu.</p>
        </div>

        <div id="cancelados" class="section">
            <h2>Cancelados</h2>
            <p>Aqui estão as solicitações que foram canceladas.</p>    
        </div>

        <div id="solicitacoes" class="section">
            <h2>Solicitações</h2>
            <p>Aqui estão as solicitações pendentes que você pode aceitar ou recusar.</p> 
        </div>
    </div>

    <script src="/assets/js/agendamentos.js"></script>
</body>
</html>
