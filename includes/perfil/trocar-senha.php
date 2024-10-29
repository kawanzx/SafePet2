<?php
session_start();
require '../db.php';

$senha_antiga = $_POST['senha_antiga'];
$nova_senha = $_POST['nova_senha'];
$confirmar_senha = $_POST['confirmar_senha'];
$user_id = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$tabela = ($tipo_usuario === 'tutor') ? 'tutores' : 'cuidadores';

$redirectUrl = ($tipo_usuario === 'tutor') ? '../../views/tutor/perfil.php' : '../../views/cuidador/perfil.php';

if (strlen($nova_senha) < 6) {
    echo "<script>alert('A nova senha deve ter pelo menos 6 caracteres.'); window.history.back();</script>";
    exit();
}

if ($nova_senha !== $confirmar_senha) {
    echo "<script>alert('As senhas não coincidem!'); window.location.href = '$redirectUrl';</script>";
    exit();
}

$sql = "SELECT senha FROM $tabela WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if (!password_verify($senha_antiga, $usuario['senha'])) {   
    echo "<script>alert('Senha antiga incorreta!'); window.location.href = '$redirectUrl';</script>";
    exit();
}

$nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);


$sql = "UPDATE $tabela SET senha = ? WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('si', $nova_senha_hash, $user_id);

if ($stmt->execute()) {
    echo "<script>alert('Senha alterada com sucesso!'); window.location.href = '$redirectUrl';</script>";
} else {
    echo "<script>alert('Erro ao alterar senha.'); window.location.href = 'trocar-senha.php';</script>";
}