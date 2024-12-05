<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

$tipo_usuario = $_SESSION['tipo_usuario'];
$id_usuario = $_SESSION['id'];
$tabela = ($tipo_usuario === 'tutor' ? 'tutores' : 'cuidadores');

// Recuperar dados do usuário
$sql = "SELECT email, nome, chave FROM $tabela WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    $email = $usuario['email'];
    $nome_completo = $usuario['nome'];
    $chave_hash = $usuario['chave'];

    if (!$email) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail não encontrado.']);
        exit;
    }

    try {
        enviarEmail($email, $nome_completo, $chave_hash);
        echo json_encode(['sucesso' => true]);
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao reenviar o e-mail.']);
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário não encontrado.']);
}