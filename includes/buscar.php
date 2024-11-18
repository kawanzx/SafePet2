<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php');

$search = !empty($_GET['search']) ? $_GET['search'] : '';

$sql = "
    SELECT 
        c.id, 
        c.nome, 
        c.preco_hora, 
        c.cidade, 
        c.uf, 
        c.foto_perfil, 
        AVG(a.nota) AS nota_media
    FROM cuidadores c
    LEFT JOIN avaliacoes a ON c.id = a.id_cuidador
";

if ($search) {
    $sql .= " WHERE c.cidade LIKE ? OR c.nome LIKE ? OR c.uf LIKE ? OR c.preco_hora LIKE ?";
}

$sql .= " GROUP BY c.id, c.nome, c.preco_hora, c.cidade, c.uf, c.foto_perfil";

$stmt = $mysqli->prepare($sql);
if ($search) {
    $searchParam = "%{$search}%";
    $stmt->bind_param('sssi', $searchParam, $searchParam, $searchParam, $searchParam);
}
$stmt->execute();
$result = $stmt->get_result();
?>
