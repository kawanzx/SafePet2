<?php
include_once '../functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $agendamento_id = $data['id'];

    session_start();

    if (!isset($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
        exit;
    }

    if (updateAgendamentoStatus($mysqli, $agendamento_id, 'aceito')) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ocorreu um erro ao tentar aceitar o atendimento. Tente novamente mais tarde.']);
    }
}