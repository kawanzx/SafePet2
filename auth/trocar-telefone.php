<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

$data = json_decode(file_get_contents("php://input"), true);
$novo_telefone = $data['novo_telefone'];
$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$tabela = ($tipo_usuario === 'tutor' ? 'tutores' : 'cuidadores');

if (!$novo_telefone) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Número de telefone não informado.']);
    exit;
}

$novo_codigo = rand(100000, 999999);
$query = "UPDATE $tabela SET telefone = ?, codigo_verificacao = ?, telefone_validado = 0 WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ssi", $novo_telefone, $novo_codigo, $id_usuario);
if ($stmt->execute()) {
    if (enviarSMS($novo_telefone, "Seu código de verificação é: $novo_codigo")) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Número atualizado e código enviado com sucesso.']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Número atualizado, mas erro ao enviar SMS.']);
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar o número.']);
}
?>
