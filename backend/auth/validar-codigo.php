<?php
include '../includes/db.php';

session_start();
$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$tabela = ($tipo_usuario === 'tutor' ? 'tutores' : 'cuidadores');

$query = "SELECT sit_usuario_id FROM $tabela WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($chave_armazenada);
$stmt->fetch();
$stmt->close();

if ($codigo_usuario == $chave_armazenada) {
    $query_update = "UPDATE $tabela SET sit_usuario_id = 1 WHERE id = ?";
    $stmt = $mysqli->prepare($query_update);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->close();

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'E-mail validado com sucesso!'
    ]);
} else {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Código de verificação incorreto. Tente novamente.'
    ]);
}

$mysqli->close();
?>
