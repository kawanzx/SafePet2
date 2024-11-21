<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php'); // Conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dados do formulário
    $dataServico = $_POST['data_servico'];
    $petsSelecionados = isset($_POST['petsSelecionados']) ? $_POST['petsSelecionados'] : [];
    $petsIds = implode(",", $petsSelecionados);
    $mensagem = $_POST['mensagem'];
    $tutorId = $_SESSION['id'];
    $cuidadorId = $_GET['id'];

    if (empty($dataServico) || empty($petsSelecionados)) {
        echo json_encode(['status' => 'error', 'message' => 'Por favor, preencha todos os campos obrigatórios.']);
        exit;
    }

    $sql = "INSERT INTO agendamentos (tutor_id, cuidador_id, data_servico, pet_id, mensagem)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iisss", $tutorId, $cuidadorId, $dataServico, $petsIds, $mensagem);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Agendamento realizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao agendar serviço: ' . $stmt->error]);
    }

    $stmt->close();
    $mysqli->close();
}
