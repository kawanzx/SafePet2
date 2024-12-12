<?php
session_start();

include('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dataServico = $_POST['data_servico'] ?? '';
    $horaInicio = $_POST['hora_inicio'] ?? '';
    $horaFim = $_POST['hora_fim'] ?? '';
    $petsSelecionados = isset($_POST['petsSelecionados']) ? $_POST['petsSelecionados'] : [];
    $mensagem = $_POST['mensagem'] ?? '';
    $tutorId = $_SESSION['id'] ?? null;
    $cuidadorId = $_GET['id'] ?? null;;

    $dataServicoConvertida = DateTime::createFromFormat('d-m-Y', $dataServico);
    if (!$dataServicoConvertida) {
        echo json_encode(['status' => 'error', 'message' => 'Data inválida.']);
        exit;
    }
    $dataServico = $dataServicoConvertida->format('Y-m-d');

    $datetimeInicio = DateTime::createFromFormat('Y-m-d H:i', $dataServico . ' ' . $horaInicio);
    if (!$datetimeInicio) {
        echo json_encode(['status' => 'error', 'message' => 'Hora de início inválida.']);
        exit;
    }

    $agora = new DateTime();
    $intervalo = $agora->diff($datetimeInicio);

    if ($datetimeInicio <= $agora || $intervalo->h < 2 && $intervalo->days === 0) {
        echo json_encode(['status' => 'error', 'message' => 'O agendamento deve ser feito com pelo menos 2 horas de antecedência.']);
        exit;
    }

    if (empty($dataServico) || empty($petsSelecionados) || empty($horaInicio) || empty($horaFim)) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, preencha todos os campos obrigatórios.']);
        exit;
    }

    $tempoInicio = strtotime($horaInicio);
    $tempoFim = strtotime($horaFim);

    if ($tempoFim <= $tempoInicio) {
        echo json_encode(['status' => 'error', 'message' => 'A hora de término deve ser maior que a hora de início.']);
        exit;
    }

    $petsIds = implode(",", $petsSelecionados);
    

    $sql = "INSERT INTO agendamentos (tutor_id, cuidador_id, data_servico, hora_inicio, hora_fim, pet_id, mensagem)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iisssss", $tutorId, $cuidadorId, $dataServico, $horaInicio, $horaFim, $petsIds, $mensagem);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Agendamento realizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao agendar serviço: ' . $stmt->error]);
    }

    $stmt->close();
    $mysqli->close();
}
