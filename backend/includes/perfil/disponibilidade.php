<?php
include __DIR__ . '/../../auth/protect.php'; // Protege a página
include __DIR__ . '/../../includes/db.php';

$cuidador_id = $_SESSION['id'];

// Salvar disponibilidade se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $horarios = $_POST['horarios'] ?? [];
    $stmt = $mysqli->prepare("DELETE FROM disponibilidade_cuidador WHERE cuidador_id = ?");
    $stmt->bind_param("i", $cuidador_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("INSERT INTO disponibilidade_cuidador (cuidador_id, dia_da_semana, hora_inicio, hora_fim) VALUES (?, ?, ?, ?)");
    foreach ($horarios as $dia => $horario) {
        if (!empty($horario['inicio']) && !empty($horario['fim'])) {
            $stmt->bind_param("isss", $cuidador_id, $dia, $horario['inicio'], $horario['fim']);
            $stmt->execute();
        }
    }
    $stmt->close();
}
?>
