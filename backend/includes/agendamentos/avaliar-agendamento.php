<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $nota = isset($input['rating']) ? (int)$input['rating'] : 0;
    $comentario = isset($input['comments']) ? trim($input['comments']) : '';
    $tutor_id = isset($input['tutor_id']) ? (int)$input['tutor_id'] : null;
    $cuidador_id = isset($input['cuidador_id']) ? (int)$input['cuidador_id'] : null;
    $agendamento_id = isset($input['agendamento_id']) ? (int)$input['agendamento_id'] : null;

    error_log("Nota: $nota, Comentário: $comentario, Tutor: $tutor_id, Cuidador: $cuidador_id, Agendamento: $agendamento_id");
    
    if ($nota < 1 || $nota > 5) {
        echo json_encode(["sucesso" => false, "mensagem" => "Nota inválida. A nota deve ser entre 1 e 5."]);
        exit();
    }

    if (!$tutor_id || !$agendamento_id || !$cuidador_id) {
        echo json_encode(["sucesso" => false, "mensagem" => "ID dos usuários ou agendamento inválido."]);
        exit();
    }

    // Verificar se já existe uma avaliação
    $query = "SELECT COUNT(*) AS total FROM avaliacoes WHERE id_agendamento = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $agendamento_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    error_log("Total de avaliações encontradas: " . $row['total']);

    if ($row['total'] > 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Este agendamento já foi avaliado.']);
        exit;
    }

    // Inserir avaliação
    $stmt = $mysqli->prepare("INSERT INTO avaliacoes (id_tutor, id_cuidador, id_agendamento, nota, comentario, data_avaliacao) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param('iiiis', $tutor_id, $cuidador_id, $agendamento_id, $nota, $comentario);

    if ($stmt->execute()) {
        echo json_encode(["sucesso" => true, "mensagem" => "Avaliação enviada com sucesso!"]);
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "Erro ao enviar a avaliação: " . $stmt->error]);
    }

    $stmt->close();
    $mysqli->close();
}

