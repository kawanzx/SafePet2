<?php
include '../../includes/db.php';

$agendamento_id = $_GET['agendamento_id'];

$stmt = $mysqli->prepare("
    SELECT * FROM mensagens 
    WHERE agendamento_id = ?
    ORDER BY data_envio ASC
");
$stmt->bind_param("i", $agendamento_id);
$stmt->execute();

$result = $stmt->get_result();
$mensagens = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($mensagens);
