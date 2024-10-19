<?php

include('../../login/protect.php');
include 'navbar.php';

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuidadores Disponíveis - SafePet</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="pesquisar.css">
</head>

<body>
    <!-- Seção de Cuidadores Disponíveis -->
    <section class="caregivers">

        <div id="divBusca">
            <img src="search3.png" alt="Buscar..." />
            <input type="text" id="txtBusca" placeholder="Buscar..." />
            <button id="btnBusca">Buscar</button>
        </div>

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
    <script type="text/javascript" src="script.js"></script>
</body>

</html>