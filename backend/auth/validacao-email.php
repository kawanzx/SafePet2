<?php
session_start();

$tipo_usuario = $_SESSION['tipo_usuario'];
?>
<link rel="stylesheet" href="/backend/assets/css/auth/validacao-email.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="validar-email">
    <div class="div-seta-voltar">
        <a href="../index.php"><img src="../img/seta-voltar.svg" class="seta-voltar"></a>
    </div>
    <div id="mensagem-erro">
        <p>Você precisa validar seu e-mail antes de continuar usando o site.</p>
    </div>
    <form id="form-validacao">
        <span id="tipo_usuario" style="display: none;"><?php echo $tipo_usuario ?></span>
        <button type="submit">Validar E-mail</button>
    </form>
    <button id="btn-reenviar-confirmacao">Reenviar Confirmação</button>
    <p id="mensagem-reenvio" style="display: none;"></p>
    <button id="btn-trocar-email">Trocar e-mail</a></button>
    <div id="trocar-email-container" style="display: none;">
        <input type="text" id="novo-email" placeholder="Digite o novo endereço">
        <button id="confirmar-troca">Confirmar</button>
    </div>
</div>

<script src="/backend/assets/js/auth/validacao-email.js"></script>