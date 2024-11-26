<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');


include '../../includes/db.php';

$data = json_decode(file_get_contents('php://input'), true);

error_log('Dados recebidos: ' . print_r($data, true));  // Log para verificar os dados recebidos

if (isset($data['id_remetente'], $data['id_destinatario'], $data['mensagem'], $data['agendamento_id'])) {
    $id_remetente = $data['id_remetente'];
    $id_destinatario = $data['id_destinatario'];
    $mensagem = $data['mensagem'];
    $agendamento_id = $data['agendamento_id'];

    // Inserir mensagem no banco de dados
    $stmt = $mysqli->prepare("INSERT INTO mensagens (id_remetente, id_destinatario, agendamento_id, mensagem) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $id_remetente, $id_destinatario, $agendamento_id, $mensagem);
    $stmt->execute();

    // Retornar sucesso
    echo json_encode(['success' => true]);
} else {
    // Retornar erro se os dados estiverem faltando
    echo json_encode(['success' => false, 'error' => 'Dados incompletos']);
}