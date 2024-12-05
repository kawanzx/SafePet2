<?php

include __DIR__ . '/../includes/db.php';

if(!isset($_SESSION)){
    session_start();
}

if(!isset($_SESSION['id'])){
    header("Location: /backend/auth/login.html");
    exit();
}

$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$tabela = ($tipo_usuario === 'tutor' ? 'tutores' : 'cuidadores');
$query = "SELECT sit_usuario_id FROM $tabela WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($email_validado);
$stmt->fetch();
$stmt->close();

if ($email_validado != 1) {
    header("Location: /backend/auth/validacao-email.php");
    exit;
}

