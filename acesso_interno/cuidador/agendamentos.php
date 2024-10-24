<?php
include('../../login/protect.php');
include 'navbar.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento Cuidador - SafePet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .sidebar {
            width: 200px;
            background-color: #007bff; /* Azul */
            color: white;
            padding: 15px;
            position: fixed;
            height: 100%;
        }

        .sidebar h2 {
            margin: 0;
            font-size: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .content {
            margin-left: 220px; /* Espaço para a sidebar */
            padding: 20px;
            padding-top: 60px; /* Espaço para a navbar */
            flex-grow: 1;
        }

        .section {
            display: none; /* Esconder todas seções inicialmente */
        }

        .section.active {
            display: block; /* Exibir apenas a seção ativa */
        }
    </style>
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
            <!-- Adicione suas informações de agendamentos em breve aqui -->
        </div>

        <div id="concluidos" class="section">
            <h2>Concluídos</h2>
            <p>Aqui estão as solicitações que você já concluiu.</p>
            <!-- Adicione suas informações de agendamentos concluídos aqui -->
        </div>

        <div id="cancelados" class="section">
            <h2>Cancelados</h2>
            <p>Aqui estão as solicitações que foram canceladas.</p>
            <!-- Adicione suas informações de agendamentos cancelados aqui -->
        </div>

        <div id="solicitacoes" class="section">
            <h2>Solicitações</h2>
            <p>Aqui estão as solicitações pendentes que você pode aceitar ou recusar.</p>
            <!-- Adicione suas informações de solicitações aqui -->
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            // Esconder todas as seções
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => {
                section.classList.remove('active');
            });

            // Exibir a seção clicada
            const activeSection = document.getElementById(sectionId);
            activeSection.classList.add('active');
        }
    </script>

</body>
</html>
