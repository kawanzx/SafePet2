<link rel="stylesheet" href="/acesso_interno/cuidador/navbar.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<nav class="transparent-nav">
    <div class="nav-container">
        <button class="menu-toggle" aria-label="Menu"><span></span></button>
        <ul class="nav-list">
            <li><a href="/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="/acesso_interno/cuidador/agendamentos.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'agendamentos.php' ? 'active' : ''; ?>">Agendamentos</a></li>
            <li><a href="/acesso_interno/cuidador/perfil.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'active' : ''; ?>">Perfil</a></li>
            <li><a href="/login/logout.php" id="logoutLink">Sair</a></li>
        </ul>
    </div>
</nav>

<script type="text/javascript" src="/acesso_interno/cuidador/navbar.js"></script>