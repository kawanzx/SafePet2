<?php

$tipo_usuario = $_SESSION['tipo_usuario'] ?? 'tutor';
$id = $_SESSION['id'];

?>


<link rel="stylesheet" href="/assets/css/navbar.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        </ul>
    </div>
</nav>

<script type="text/javascript" src="/assets/js/navbar.js"></script>
<script type="text/javascript" src="/assets/js/perfil/logout.js"></script>
