<?php
session_start();

include_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = $_POST['petId'];

    $tutor_id = $_SESSION['id'];

    // Deletar o pet
    $sql = "DELETE FROM pets WHERE id = ? AND tutor_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $pet_id, $tutor_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Pet excluído com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir o pet: ' . $stmt->error]);
    }

    $stmt->close();
    $mysqli->close();
}
?>