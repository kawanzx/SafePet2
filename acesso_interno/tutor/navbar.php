<link rel="stylesheet" href="\acesso_interno\tutor\navbar.css">
<nav class="transparent-nav">
    <ul>
        <li><a href="/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
        <li><a href="/acesso_interno/tutor/pesquisar.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'pesquisar.php' ? 'active' : ''; ?>">Pesquisar</a></li>
        <li><a href="/acesso_interno/tutor/agendamentos.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'agendamentos.php' ? 'active' : ''; ?>">Agendamentos</a></li>
        <li><a href="/acesso_interno/tutor/perfil.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'perfil.php' ? 'active' : ''; ?>">Perfil</a></li>
        <li><a href="/login/logout.php" id="logoutLink">Sair</a></li>
    </ul>
</nav>


