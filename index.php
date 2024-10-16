<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SafePet - Passeios com Cães</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">

</head>

<body>
    <header>
        <?php
        if (isset($_SESSION['nome'])) {
            if ($_SESSION['tipo_usuario'] == 'tutor') {
                include 'acesso_interno/tutor/navbar.php';
            } else if ($_SESSION['tipo_usuario'] == 'cuidador') {
                include 'acesso_interno/cuidador/navbar.php';
            }
        } else { ?>
            <nav class="transparent-nav">
                <ul>
                    <li><a href="#hero">Home</a></li>
                    <li><a href="#services">Serviços</a></li>
                    <li><a href="#about">Sobre</a></li>
                    <li><a href="#contact">Contato</a></li>
                    <li><a href="login/formlogin.html">Login</a></li>
                </ul>
            </nav>
        <?php } ?>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="hero">
        <h1>Seu Parceiro para Passeios com Pets</h1>
        <p>Conecte-se com cuidadores certificados para momentos de diversão e segurança.</p>
        <?php if (isset($_SESSION['nome'])) {
            if ($_SESSION['tipo_usuario'] == 'tutor') { ?>
                <a href="acesso_interno/tutor/pesquisar.php" class="cta-button">Começar Agora</a>
            <?php } elseif ($_SESSION['tipo_usuario'] == 'cuidador') { ?>
                <a href="acesso_interno/cuidador/pesquisar.php" class="cta-button">Começar Agora</a>
            <?php }
        } else { ?>
            <a href="login/formlogin.html" class="cta-button">Começar Agora</a>
        <?php } ?>
    </section>

    <!-- Cards com Serviços - Sessão grande -->
    <section id="services" class="services-section">
        <h2>Nossos Serviços</h2>
        <div class="card-container">
            <div class="card">
                <h3>Rastreamento em Tempo Real</h3>
                <p>Acompanhe o passeio do seu pet ao vivo e com segurança.</p>
            </div>
            <div class="card">
                <h3>Cuidadores Verificados</h3>
                <p>Escolha entre cuidadores de confiança e qualificados.</p>
            </div>
            <div class="card">
                <h3>Fotos e Atualizações</h3>
                <p>Receba fotos e mensagens sobre o seu pet durante o passeio.</p>
            </div>
        </div>
    </section>

    <!-- Seção Expansiva de Destaques -->
    <section class="highlights">
        <h2>Destaques do SafePet</h2>
        <p class="p-Highlight"> Com o SafePet, garantimos que cada momento com seu pet seja seguro e divertido. Oferecemos serviços que vão além do básico, proporcionando tranquilidade a você e diversão ao seu pet.</p>
        <div class="highlight-container">
            <div class="highlight">
                <h3>Serviço Personalizado</h3>
                <p>Adapte os passeios de acordo com as necessidades do seu pet.</p>
            </div>
            <div class="highlight">
                <h3>Relatórios Detalhados</h3>
                <p>Saiba tudo sobre a experiência do seu pet durante o passeio.</p>
            </div>
            <div class="highlight">
                <h3>Suporte Completo</h3>
                <p>Nossa equipe está sempre disponível para responder suas dúvidas.</p>
            </div>
        </div>
    </section>

    <!-- Sobre o SafePet - Sessão maior -->
    <section id="about" class="about-section">
        <h2>Sobre o SafePet</h2>
        <p>O SafePet é dedicado à segurança e bem-estar dos seus pets durante os passeios. Nossa plataforma permite que você se conecte com cuidadores qualificados e acompanhe o passeio em tempo real.</p>
        <p>Contamos com uma equipe altamente treinada e apaixonada por animais, pronta para oferecer o melhor cuidado ao seu pet. Queremos garantir que cada passeio seja uma experiência positiva tanto para você quanto para o seu companheiro de quatro patas.</p>
    </section>

    <!-- Sessão de Testemunhos -->
    <section class="testimonials">
        <h2>O que Nossos Clientes Dizem</h2>
        <div class="testimonial-container">
            <div class="testimonial">
                <p>"O SafePet é incrível! Meu cachorro ama os passeios e eu posso ficar tranquila sabendo que ele está em boas mãos."</p>
                <p>- Maria, cliente satisfeita</p>
            </div>
            <div class="testimonial">
                <p>"Adoro o fato de poder acompanhar o passeio em tempo real. Dá uma sensação de segurança!"</p>
                <p>- João, tutor de pets</p>
            </div>
            <div class="testimonial">
                <p>"Os cuidadores são super atenciosos e sempre mandam atualizações. Recomendo muito!"</p>
                <p>- Ana, cliente fiel</p>
            </div>
        </div>
    </section>

    <!-- Rodapé -->
    <footer id="contact" class="footer">
        <div class="newsletter">
            <h3>Assine nossa Newsletter</h3>
            <input type="email" placeholder="Seu email">
            <button>Inscrever</button>
        </div>
        <div class="social-media">
            <a href="#">Facebook</a>
            <a href="#">Instagram</a>
            <a href="#">Twitter</a>
        </div>
        <p>&copy; 2024 SafePet. Todos os direitos reservados.</p>
    </footer>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>