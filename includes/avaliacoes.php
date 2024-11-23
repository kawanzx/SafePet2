<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cuidador_id = $_POST['cuidador_id'];
    $tutor_id = $_POST['tutor_id'];
    $rating = $_POST['rating'];
    $comentario = $_POST['comentario'];

    if ($rating < 1 || $rating > 5) {
        echo json_encode(['erro' => 'Nota inválida']);
        exit;
    }

    $stmt = $mysqli->prepare("INSERT INTO avaliacoes (id_cuidador, id_tutor, nota, comentario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $cuidador_id, $tutor_id, $rating, $comentario);

    if ($stmt->execute()) {
        echo json_encode(['sucesso' => 'Avaliação salva com sucesso']);
    } else {
        echo json_encode(['erro' => 'Erro ao salvar avaliação']);
    }

    $stmt->close();
    $conn->close();
}
