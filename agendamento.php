<?php

    include('login/protect.php');

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuidadores Disponíveis - SafePet</title>
    <link rel="stylesheet" href="agendamento.css">
</head>
<body> 
    <!-- Navbar -->
    <header>
        <nav class="transparent-nav">
            
            <ul>
                <li><a href="login/logout.php">Sair</a></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php/#services">Serviços</a></li>
                <li><a href="index.php/#about">Sobre</a></li>
                <li><a href="index.php/#contact">Contato</a></li>
            </ul>
        </nav>
    </header>

    <!-- Seção de Cuidadores Disponíveis -->
    <section class="caregivers">
        <h2>Cuidadores Disponíveis</h2>

        <div class="caregiver">
            <div class="avatar"></div>
            <div class="details">
                <h3>Nome do Cuidador</h3>
                <p>Avaliação: ⭐⭐⭐⭐☆</p>
                <p>Preço: R$ 50,00/hora</p>
                <p>Localização: São Paulo, SP</p>
            </div>
            <a href="#schedule" class="schedule-button">Agendar</a>
        </div>

        <div class="caregiver">
            <div class="avatar"></div>
            <div class="details">
                <h3>Nome do Cuidador</h3>
                <p>Avaliação: ⭐⭐⭐⭐⭐</p>
                <p>Preço: R$ 60,00/hora</p>
                <p>Localização: Rio de Janeiro, RJ</p>
            </div>
            <a href="#schedule" class="schedule-button">Agendar</a>
        </div>

        <div class="caregiver">
            <div class="avatar"></div>
            <div class="details">
                <h3>Nome do Cuidador</h3>
                <p>Avaliação: ⭐⭐⭐⭐☆</p>
                <p>Preço: R$ 55,00/hora</p>
                <p>Localização: Belo Horizonte, MG</p>
            </div>
            <a href="#schedule" class="schedule-button">Agendar</a>
        </div>
    </section>
</body>
</html>