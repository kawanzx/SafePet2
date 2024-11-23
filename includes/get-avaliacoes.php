<?php
include 'db.php';

$cuidador_id = $_GET['id'];

$sql = "SELECT 
            AVG(nota) AS media_nota, 
            COUNT(*) AS total_avaliacoes 
        FROM avaliacoes 
        WHERE id_cuidador = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $cuidador_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode([
    'media_rating' => round($result['media_nota'], 1),
    'total_avaliacoes' => (int)$result['total_avaliacoes']
]);
