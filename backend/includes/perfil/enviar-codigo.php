<?php
header('Content-Type: application/json');

require_once '../db.php';  
require_once '../functions.php';

$input = json_decode(file_get_contents('php://input'), true);
if (empty($input['telefone'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Número de telefone não fornecido.']);
    exit;
}

$telefone = $input['telefone'];

session_start();
$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$tabela = ($tipo_usuario === 'tutor' ? 'tutores' : 'cuidadores');

$codigo_verificacao = rand(100000, 999999); 

$smsResult = enviarSMS($telefone, $codigo_verificacao);

if (!$smsResult['sucesso']) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao enviar o código.']);
    exit();
}

$query = "UPDATE $tabela SET codigo_verificacao = ? WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ii", $codigo_verificacao, $id_usuario);
$stmt->execute();
$stmt->close();

echo json_encode(['sucesso' => true, 'mensagem' => 'Código enviado!']);

$mysqli->close();
?>
