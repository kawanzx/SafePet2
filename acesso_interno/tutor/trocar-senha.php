<?php
session_start();
require '../../login/conexaobd.php';

$senha_antiga = $_POST['senha_antiga'];
$nova_senha = $_POST['nova_senha'];
$confirmar_senha = $_POST['confirmar_senha'];

if (strlen($nova_senha) < 6) {
    echo "<script>alert('A nova senha deve ter pelo menos 6 caracteres.'); window.history.back();</script>";
    exit();
}

if ($nova_senha !== $confirmar_senha) {
    echo "<script>alert('As senhas não coincidem!'); window.location.href = 'perfil.php';</script>";
    exit();
}

$tutor_id = $_SESSION['id'];

$sql = "SELECT senha FROM tutores WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $tutor_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if (!password_verify($senha_antiga, $usuario['senha'])) {
    echo "<script>alert('Senha antiga incorreta!'); window.location.href = 'perfil.php';</script>";
    exit();
}

$nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);


$sql = "UPDATE tutores SET senha = ? WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('si', $nova_senha_hash, $tutor_id);

if ($stmt->execute()) {
    echo "<script>alert('Senha alterada com sucesso!'); window.location.href = 'perfil.php';</script>";
} else {
    echo "<script>alert('Erro ao alterar senha.'); window.location.href = 'trocar-senha.php';</script>";
}