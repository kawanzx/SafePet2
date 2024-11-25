<?php

include __DIR__ . '/../includes/db.php';

if(!isset($_SESSION)){
    session_start();
}

if(!isset($_SESSION['id'])){

    header("Location: /auth/login.html");
    exit();
}

$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$tabela = ($tipo_usuario === 'tutor' ? 'tutores' : 'cuidadores');
$query = "SELECT telefone_validado FROM $tabela WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($telefone_validado);
$stmt->fetch();
$stmt->close();

if (!$telefone_validado) {
    header("Location: /auth/validacao-telefone.php");
    exit;
}

