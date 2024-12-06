<?php
include('db.php');

$search = !empty($_GET['search']) ? $_GET['search'] : '';

$sql = "
    SELECT 
        c.id, 
        c.nome, 
        c.preco_hora, 
        c.cidade, 
        c.uf,
        c.ativo, 
        c.foto_perfil,
        AVG(a.nota) AS nota_media,
        GROUP_CONCAT(DISTINCT d.dia_da_semana ORDER BY d.dia_da_semana ASC SEPARATOR ', ') AS dias_disponiveis,
        GROUP_CONCAT(DISTINCT CONCAT(d.dia_da_semana, ' (', d.hora_inicio, ' - ', d.hora_fim, ')') ORDER BY d.dia_da_semana ASC SEPARATOR ', ') AS horarios_disponiveis
    FROM cuidadores c
    LEFT JOIN avaliacoes a ON c.id = a.id_cuidador
    LEFT JOIN disponibilidade_cuidador d ON c.id = d.cuidador_id
";

if ($search) {
    $sql .= " WHERE c.cidade LIKE ? OR c.nome LIKE ? OR c.uf LIKE ? OR c.preco_hora LIKE ? OR d.dia_da_semana LIKE ? OR d.hora_inicio LIKE ? OR d.hora_fim LIKE ?";
}

$sql .= " GROUP BY c.id";

$stmt = $mysqli->prepare($sql);
if ($search) {
    $searchParam = "%{$search}%";
    $stmt->bind_param('sssisss', $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
}
$stmt->execute();
$result = $stmt->get_result();
?>