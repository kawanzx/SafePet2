<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

$data = json_decode(file_get_contents("php://input"), true);
$novo_email = $data['novo_email'];
$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$nome = $_SESSION['nome'];
$tabela = ($tipo_usuario === 'tutor' ? 'tutores' : 'cuidadores');

if (!$novo_email) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail não informado.']);
    exit;
}

$nova_chave_hash = password_hash($novo_email . date("Y-m-d H:i:s"), PASSWORD_DEFAULT);
$query = "UPDATE $tabela SET email = ?, chave = ?, sit_usuario_id = 3 WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ssi", $novo_email, $nova_chave_hash, $id_usuario);
if ($stmt->execute()) {
    if (enviarEmail($novo_email, $nome, $nova_chave_hash)) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'E-mail atualizado e confirmação enviado com sucesso.']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail atualizado, mas erro ao enviar a confirmação.']);
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar o e-mail.']);
}
?>
