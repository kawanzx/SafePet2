<?php
include '../db.php';
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $notificacao_id = intval($_POST['id']);
    $agendamento_id = $_POST['agendamento_id'];
    $remetente_id = $_POST['remetente_id'];
    $tipo_remetente = $_POST['tipo_remetente'];

    error_log('Dados: ' . implode(', ', [$notificacao_id, $agendamento_id, $remetente_id, $tipo_remetente]));

    if ($notificacao_id > 0) {
        $stmt = $mysqli->prepare("UPDATE notificacoes SET lida = 1 WHERE agendamento_id = ? AND remetente_id = ? AND tipo_remetente = ?");
        $stmt->bind_param("iis", $agendamento_id, $remetente_id, $tipo_remetente);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'sucesso']);
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
