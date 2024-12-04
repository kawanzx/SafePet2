<?php
include __DIR__ . '/../db.php';
include __DIR__ . '/../functions.php';

session_start();

$agora = date('Y-m-d');
$stmt = $mysqli->prepare(
    "SELECT agendamentos.*, notificacoes.tipo_notificacao 
    FROM agendamentos
    JOIN notificacoes ON agendamentos.id = notificacoes.agendamento_id
    WHERE DATE(agendamentos.data_servico) = ? 
    AND agendamentos.notificacao_enviada = 0"
);
$stmt->bind_param("s", $agora);
$stmt->execute();
$result = $stmt->get_result();
$notificacoes = $result->fetch_all(MYSQLI_ASSOC);

if (!empty($notificacoes)) {
    foreach ($notificacoes as $notificacao) {
        $tipo_remetente = $_SESSION['tipo_usuario'];
        $remetente_id = $notificacao[$tipo_remetente === 'tutor' ? 'tutor_id' : 'cuidador_id'];
        $destinatario_id = $notificacao[$tipo_remetente === 'tutor' ? 'cuidador_id' : 'tutor_id'];
        $agendamento_id = $notificacao['id'];
        $tipo_notificacao = $notificacao['tipo_notificacao'];
        enviarNotificacao($mysqli, $agendamento_id, $remetente_id, $tipo_remetente, $tipo_notificacao, $destinatario_id, $tipo_remetente === 'tutor' ? 'cuidador' : 'tutor', "Você tem um serviço agendado para hoje!");

        $updateStmt = $mysqli->prepare("UPDATE agendamentos SET notificacao_enviada = 1 WHERE id = ?");
        $updateStmt->bind_param("i", $notificacao['id']);
        $updateStmt->execute();
        $updateStmt->close();
    }
}
$stmt->close();
$mysqli->close();
