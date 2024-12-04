<?php
include __DIR__ . '/../../includes/db.php'; // Conectar ao banco de dados

// Verificar se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $cuidador_id = $_SESSION['id']; // ID do cuidador

    // Obter o preço da hora do formulário
    $preco_hora = $_POST['preco_hora'] ?? 0.00;

    // Atualizar o preço no banco de dados
    $stmt = $mysqli->prepare("UPDATE cuidadores SET preco_hora = ? WHERE id = ?");
    $stmt->bind_param("di", $preco_hora, $cuidador_id);
    $stmt->execute();
    $stmt->close();

    // Redirecionar de volta para a página do perfil
    header('Location: /backend/views/cuidador/perfil.php?id=' . $cuidador_id);
    exit;
}
?>
