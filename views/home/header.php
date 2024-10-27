<?php

session_start();
?>

<header>
    <?php
    if (isset($_SESSION['nome'])) {
        include __DIR__ . "/../../includes/navbar.php";
    } else { ?>
        <nav class="nav-padrao">
            <div class="navbar-container">
                <ul>
                    <li><a href="#hero">Home</a></li>
                    <li><a href="#services">Serviços</a></li>
                    <li><a href="#about">Sobre</a></li>
                    <li><a href="#contact">Contato</a></li>
                    <li><a href="auth/login.html">Login</a></li>
                </ul>
            </div>
        </nav>
    <?php } ?>
</header>