<?php

$tipo_usuario = $_SESSION['tipo_usuario'] ?? 'tutor';
$id = $_SESSION['id'];

?>

<head>
    <link rel="stylesheet" href="/assets/css/navbar.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body data-user-id="<?php echo $_SESSION['id']; ?>">
    <nav class="transparent-nav">
        <div class="nav-container">
            <button class="menu-toggle" aria-label="Menu"><span></span></button>
            <ul class="nav-list">
                <li><a href="/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <?php if ($tipo_usuario === 'tutor'): ?>
                    <li><a href="/views/tutor/buscar.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'buscar.php' ? 'active' : ''; ?>">Buscar</a></li>
                <?php endif; ?>
                <li><a href="/views/shared/agendamentos.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'agendamentos.php' ? 'active' : ''; ?>">Agendamentos</a></li>
                <li><a href="/views/<?php echo $tipo_usuario; ?>/perfil.php?id=<?php echo $id; ?>" class="<?php echo basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'active' : ''; ?>">Perfil</a></li>
                <li><a href="/auth/logout.php" id="logoutLink">Sair</a></li>
                <li>
                    <div id="notificacao-icon">
                        <span class="material-symbols-outlined sino">notifications</span>
                        <span id="contador-notificacoes"></span>
                        <div id="notificacoes-dropdown" style="display: none;">
                            <h3>Notificações</h3>
                            <ul id="lista-notificacoes"></ul>
                            <p id="sem-notificacoes" class="hidden">Você não tem notificações.</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
    <script type="text/javascript" src="/assets/js/navbar.js"></script>
    <script type="text/javascript" src="/assets/js/perfil/logout.js"></script>
</body>