<?php
session_start();
include __DIR__ . '/../../auth/protect.php'; // Certifique-se de proteger a página

// Conexão com o banco de dados
include __DIR__ . '/../../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cuidador_id = $_SESSION['id']; // Assumindo que você já tem a variável $cuidador
    $dia_da_semana = $_POST['dia_da_semana'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];

    // Inserir a disponibilidade no banco de dados
    $sql = "INSERT INTO disponibilidade_cuidador (cuidador_id, dia_da_semana, hora_inicio, hora_fim) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("isss", $cuidador_id, $dia_da_semana, $hora_inicio, $hora_fim);
    
    if ($stmt->execute()) {
        header("Location: /views/cuidador/perfil.php"); // Redireciona após o sucesso
    } else {
        echo "Erro ao adicionar a disponibilidade: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>
