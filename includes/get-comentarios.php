<?php
include 'db.php';

$cuidador_id = $_GET['id'];

$sql = "
    SELECT 
        t.id AS tutor_id,
        t.nome,
        t.foto_perfil, 
        a.comentario, 
        a.nota, 
        a.data_avaliacao
    FROM avaliacoes a
    INNER JOIN tutores t ON t.id = a.id_tutor
    WHERE a.id_cuidador = ?
    ORDER BY a.data_avaliacao DESC 
    LIMIT 5
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $cuidador_id);
$stmt->execute();
$result = $stmt->get_result();

$comentarios = [];
while ($row = $result->fetch_assoc()) {
    $comentarios[] = [
        'comentario' => htmlspecialchars($row['comentario'] ?? ''),
        'nome' => htmlspecialchars($row['nome'] ?? ''),
        'nota' => (int)($row['nota'] ?? 0),
        'data_avaliacao' => $row['data_avaliacao'],
        'foto_perfil' => $row['foto_perfil'] ? '../../assets/uploads/fotos-tutores/' . $row['foto_perfil'] : '../../img/profile-circle-icon.png',
    ];
}

echo json_encode($comentarios);
