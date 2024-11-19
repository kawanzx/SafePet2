<?php

include __DIR__ . '/../../auth/protect.php'; // Certifique-se de proteger a página
include __DIR__ . '/../../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cuidador_id']) || empty($_SESSION['cuidador_id'])) {
    die("Erro: cuidador_id não está definido. Por favor, faça login novamente.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Exclui a disponibilidade anterior do cuidador
    $stmt = $mysqli->prepare("DELETE FROM disponibilidade_cuidador WHERE cuidador_id = ?");
    $stmt->bind_param("i", $_SESSION['cuidador_id']);
    $stmt->execute();
    $stmt->close();

    $sucesso = true;
    $mensagemErro = "";

    // Percorre os dias e horários enviados no formulário
    foreach ($_POST['horarios'] as $dia => $horario) {
        // Verifica se o dia foi marcado como disponível
        if (isset($horario['disponivel']) && $horario['disponivel']) {
            $hora_inicio = $horario['inicio'];
            $hora_fim = $horario['fim'];

            // Valida se as horas de início e fim são válidas
            if (!empty($hora_inicio) && !empty($hora_fim) && $hora_inicio < $hora_fim) {
                // Insere a nova disponibilidade no banco de dados
                $stmt = $mysqli->prepare("INSERT INTO disponibilidade_cuidador (cuidador_id, dia_da_semana, hora_inicio, hora_fim) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $_SESSION['cuidador_id'], $dia, $hora_inicio, $hora_fim);
                if (!$stmt->execute()) {
                    $mensagemErro .= "Erro ao inserir a disponibilidade para $dia: " . $stmt->error . "<br>";
                    $sucesso = false;
                }
                $stmt->close();
            } else {
                // Caso os horários sejam inválidos, adiciona mensagem de erro
                $mensagemErro .= "Horário inválido para o dia $dia: início ($hora_inicio) deve ser antes do fim ($hora_fim).<br>";
                $sucesso = false;
            }
        }
    }

    $mysqli->close();

    // Redireciona para o perfil ou exibe erro
    if ($sucesso) {
        header("Location: /views/cuidador/perfil.php"); 
        exit();
    } else {
        echo "<div style='color: red;'>Ocorreu um erro ao salvar as informações:<br>$mensagemErro</div>";
    }
}
?>
