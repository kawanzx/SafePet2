<?php
include '../db.php';
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $notificacao_id = intval($_POST['id']);
    $agendamento_id = $_POST['agendamento_id'];
    $remetente_id = $_POST['remetente_id'];
    $tipo_remetente = $_POST['tipo_remetente'];
    $tipo_notificacao = $_POST['tipo_notificacao'];

    error_log('Dados: ' . implode(', ', [$notificacao_id, $agendamento_id, $remetente_id, $tipo_remetente, $tipo_notificacao]));

    if ($notificacao_id > 0) {
        $stmt = $mysqli->prepare("UPDATE notificacoes SET lida = 1 WHERE agendamento_id = ? AND remetente_id = ? AND tipo_remetente = ? AND tipo_notificacao = ?");
        $stmt->bind_param("iiss", $agendamento_id, $remetente_id, $tipo_remetente, $tipo_notificacao);
        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'sucesso',
                'tipo_notificacao' => $tipo_notificacao,
                'message' => 'Notificação marcada como lida.'
            ]);
        } else {
            echo json_encode(['status' => 'erro', 'message' => 'Falha ao marcar notificação como lida.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'erro', 'message' => 'ID inválido.']);
    }
} else {
    echo json_encode(['status' => 'erro', 'message' => 'Requisição inválida.']);
}
$mysqli->close();
?>
