<?php
session_start();
include('/includes/db.php'); // Conexão com o banco de dados

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data_servico = $_POST['data_servico'];
    $mensagem = $_POST['mensagem'];
    $pets = isset($_POST['pets']) ? $_POST['pets'] : [];

    // Obtém o ID do tutor e do cuidador (supondo que esteja armazenado na sessão)
    $id_tutor = $_SESSION['id_tutor'];
    $id_cuidador = $_GET['id_cuidador']; // ID do cuidador (pode ser passado como parâmetro na URL)

    // Validação (você pode personalizar conforme necessário)
    if (empty($data_servico) || empty($pets)) {
        echo "Por favor, preencha todos os campos.";
        exit;
    }

    // Inserir o agendamento no banco de dados
    $sql = "INSERT INTO agendamentos (id_tutor, id_cuidador, data_servico, mensagem) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $id_tutor, $id_cuidador, $data_servico, $mensagem);
    $stmt->execute();

    // Obter o ID do agendamento inserido
    $agendamento_id = $stmt->insert_id;

    // Relacionar os pets selecionados ao agendamento
    foreach ($pets as $pet_id) {
        $sql_pet = "INSERT INTO agendamento_pets (id_agendamento, id_pet) VALUES (?, ?)";
        $stmt_pet = $conn->prepare($sql_pet);
        $stmt_pet->bind_param("ii", $agendamento_id, $pet_id);
        $stmt_pet->execute();
    }

    echo "Serviço agendado com sucesso!";
}
?>