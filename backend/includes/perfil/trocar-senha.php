<?php

require '../db.php';

$data = json_decode(file_get_contents("php://input"), true);

$senha_antiga = $data['senha_antiga'] ?? null;
$nova_senha = $data['nova_senha'] ?? null;
$confirmar_senha = $data['confirmar_senha'] ?? null;
$user_id = $_SESSION['id'] ?? null; 
$tipo_usuario = $_SESSION['tipo_usuario'] ?? null; 

if (!$user_id || !$tipo_usuario) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não autenticado.']);
    exit();
}

$tabela = ($tipo_usuario === 'tutor') ? 'tutores' : 'cuidadores';

if (is_null($senha_antiga) || is_null($nova_senha) || is_null($confirmar_senha)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados de senha inválidos.']);
    exit();
}

if (strlen($nova_senha) < 6) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'A nova senha deve ter pelo menos 6 caracteres.']);
    exit();
}

if ($nova_senha !== $confirmar_senha) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'As senhas não coincidem!']);
    exit();
}

$sql = "SELECT senha FROM $tabela WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($senhaHash);
$stmt->fetch();
$stmt->close();

if (!password_verify($senha_antiga, $senhaHash)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Senha antiga incorreta.']);
    exit();
}

$nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
$sql = "UPDATE $tabela SET senha = ? WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('si', $nova_senha_hash, $user_id);

if ($stmt->execute()) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Senha alterada com sucesso!']);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao alterar a senha.']);
}

$stmt->close();
exit();