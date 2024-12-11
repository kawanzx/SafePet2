<?php
require_once '../db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $agendamentoId = $data['agendamento_id'] ?? null;

    if (!$agendamentoId) {
        echo json_encode(['success' => false, 'message' => 'ID do agendamento não fornecido.']);
        exit;
    }

    $sql = "UPDATE agendamentos SET status = 'concluido' WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $agendamentoId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o status do agendamento.']);
    }

    $stmt->close();
    $mysqli->close();
}
?>
