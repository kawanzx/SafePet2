<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

include '../db.php';
include '../functions.php';

$data = json_decode(file_get_contents('php://input'), true);

error_log('Dados recebidos no criar notificacoes: ' . print_r($data, true)); 

$id_agendamento = $data['agendamento_id'];
$id_destinatario = $data['id_destinatario'];
$id_remetente = $data['id_remetente'];
$mensagem = $data['mensagem'];
$tipo_remetente = $data['tipo_remetente'];
$tipo_destinatario = $tipo_remetente === 'tutor' ? 'cuidador' : 'tutor';

if (empty($id_remetente) || empty($id_destinatario) || empty($mensagem)) {
    echo json_encode(['status' => 'error', 'message' => 'Dados incompletos para criar notificação.']);
    exit;
}

criarNotificacao($mysqli, $id_agendamento, $id_remetente, $tipo_remetente, $id_destinatario, $tipo_destinatario, $mensagem);
echo json_encode(['status' => 'success']);

