<?php

include('../../login/protect.php');
include 'navbar.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Completo do Cuidador</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!-- Fonte do Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Container Principal -->
    <div class="container">
        <!-- Sidebar (Menu Lateral) -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../assets/favicon.ico" alt="">
                <h2>SafePet</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="#" onclick="showContent('conteudo-1', this)"><span class="material-symbols-outlined">account_circle</span>Meu Perfil</a></li>
                    <li><a href="#" onclick="showContent('conteudo-2', this)"><span class="material-symbols-outlined">finance</span>Ganhos</a></li>
                    <li><a href="#" onclick="showContent('conteudo-3', this)"><span class="material-symbols-outlined">person</span>Informações Pessoais</a></li>
                    <li><a href="#" onclick="showContent('conteudo-4', this)"><span class="material-symbols-outlined">info</span>Suporte</a></li>
                    <li><a href="#" onclick="showContent('conteudo-5', this)"><span class="material-symbols-outlined">lock</span>Política de Privacidade</a></li>
                </ul>
            </nav>

        </aside>
        <main class="conteudo">
        <div class="content-1">
            <h1>Perfil do Cuidador</h1>
            <!-- Seção Completa do Perfil -->
            <div class="perfil-completo">
                <div class="perfil-header">
                    <img src="https://cdn-icons-png.flaticon.com/512/9706/9706583.png" alt="Foto do Cuidador" class="cuidador-avatar">
                    <div>
                        <h2>Nome do Cuidador</h2>
                        <p><span class="info-label">Avaliação:</span> ⭐⭐⭐⭐ (4.8)</p>
                    </div>
                </div>
                <div class="section">
                    <h3>Bio</h3>
                    <p>Apaixonado por animais, cuidando de pets há 5 anos. Sempre tive uma ligação forte com animais e dedico meu tempo a proporcionar o melhor cuidado possível aos pets.</p>
                </div>
                <div class="section">
                    <h3>Experiência</h3>
                    <p>Especialista em raças de pequeno porte e animais idosos. Tenho experiência com passeios, alimentação e cuidados gerais, além de lidar com pets que possuem necessidades especiais.</p>
                </div>
                <div class="section">
                    <h3>Disponibilidade</h3>
                    <p>Segunda a Sexta: 08:00 - 18:00</p>
                    <p>Sábados: 10:00 - 14:00</p>
                </div>
            </div>
        </div>
        </main>
        <!-- Conteúdo Principal -->

    </div>
</body>

</html>