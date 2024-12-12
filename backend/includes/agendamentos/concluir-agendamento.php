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
        $query = "SELECT a.hora_inicio, a.hora_fim, a.data_servico, a.cuidador_id, c.preco_hora FROM agendamentos a LEFT JOIN cuidadores c ON c.id = a.cuidador_id WHERE a.id = ?";
        $stmt2 = $mysqli->prepare($query);
        $stmt2->bind_param('i', $agendamentoId);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $agendamento = $result->fetch_assoc();

        if ($agendamento) {
            $horaInicio = strtotime($agendamento['hora_inicio']);
            $horaFim = strtotime($agendamento['hora_fim']);
            $horasTrabalhadas = ($horaFim - $horaInicio) / 3600;
            $valorTotal = $horasTrabalhadas * $agendamento['preco_hora'];

            $queryInsert = "INSERT INTO ganhos_cuidador (id_agendamento, cuidador_id, valor, data) VALUES (?, ?, ?, NOW())";
            $stmt3 = $mysqli->prepare($queryInsert);
            $stmt3->bind_param('iid', $agendamentoId, $agendamento['cuidador_id'], $valorTotal);

            if ($stmt3->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao registrar os ganhos do cuidador.']);
            }

            $stmt3->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Agendamento não encontrado.']);
        }

        $stmt2->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o status do agendamento.']);
    }

    $stmt->close();
    $mysqli->close();
}
?>
