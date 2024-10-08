<?php
include('../../login/protect.php');
include 'navbar.php';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../assets/favicon.ico" alt="">
                <h2>SafePet</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="#" onclick="showContent('conteudo-1', this)"><span class="material-symbols-outlined">account_circle</span>Meu Perfil</a></li>
                    <li><a href="#" onclick="showContent('conteudo-2', this)"><span class="material-symbols-outlined">pets</span>Meus Pets</a></li>
                    <li><a href="#" onclick="showContent('conteudo-3', this)"><span class="material-symbols-outlined">person</span>Informações Pessoais</a></li>
                    <li><a href="#" onclick="showContent('conteudo-4', this)"><span class="material-symbols-outlined">info</span>Suporte</a></li>
                    <li><a href="#" onclick="showContent('conteudo-5', this)"><span class="material-symbols-outlined">lock</span>Política de Privacidade</a></li>
                </ul>
            </nav>

        </aside>
        <main class="conteudo">
            <div id="conteudo-1" class="content-section active">
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Beatae excepturi nihil sapiente suscipit quibusdam quisquam totam! Rerum quod maiores iste eum assumenda, laboriosam beatae amet debitis repellendus nam sunt deleniti.</p>
            </div>
            <div id="conteudo-2" class="content-section">
                <p>conteudo 2</p>
            </div>
            <div id="conteudo-3" class="content-section">
                <p>conteudo 3</p>
            </div>
            <div id="conteudo-4" class="content-section">
                <p>conteudo 4</p>
            </div>
            <div id="conteudo-5" class="content-section">
                <p>conteudo 5</p>
            </div>
        </main>
    </div>

    <script type="text/javascript" src="script.js"></script>
</body>

</html>