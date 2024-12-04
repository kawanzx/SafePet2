<?php
session_start();

$tipo_usuario = $_SESSION['tipo_usuario'];
?>
<link rel="stylesheet" href="/backend/assets/css/auth/validacao-telefone.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="validar-telefone">
    <div id="mensagem-erro">
        <p>Você precisa validar seu telefone antes de continuar usando o site.</p>
    </div>
    <form id="form-validacao">
        <span id="tipo_usuario" style="display: none;"><?php echo $tipo_usuario ?></span>
        <input type="text" name="codigo" placeholder="Digite o código de verificação" required>
        <button type="submit">Validar Código</button>
    </form>
    <button id="btn-reenviar-codigo">Reenviar Código</button>
    <p id="mensagem-reenvio" style="display: none;"></p>
    <button id="btn-trocar-telefone">Trocar Número</button>
    <div id="trocar-telefone-container" style="display: none;">
        <input type="text" id="novo-telefone" placeholder="Digite o novo número">
        <button id="confirmar-troca">Confirmar</button>
    </div>
</div>

<script src="/backend/assets/js/auth/validacao-telefone.js"></script>