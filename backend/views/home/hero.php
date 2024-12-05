<section id="hero" class="hero">
    <h1>Seu Parceiro para Passeios com Pets</h1>
    <p>Conecte-se com cuidadores certificados para momentos de diversão e segurança.</p>
    <?php if (isset($_SESSION['nome'])) {
        if ($_SESSION['tipo_usuario'] == 'tutor') { ?>
            <a href="/backend/views/tutor/buscar.php" class="cta-button">Começar Agora</a>
        <?php } elseif ($_SESSION['tipo_usuario'] == 'cuidador') { ?>
            <a href="/backend/views/cuidador/agendamentos.php" class="cta-button">Começar Agora</a>
        <?php }
    } else { ?>
        <a href="/backend/auth/login.html" class="cta-button">Começar Agora</a>
    <?php } ?>
    <div class="dog-walking-container">
        <img src="/backend/img/dog-walking.svg" alt="dog walking" class="dog-walking">
    </div>
</section>
