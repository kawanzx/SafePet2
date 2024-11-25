<?php
include '../includes/db.php';

$codigo_usuario = $_POST['codigo'];

session_start();
$id_usuario = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$tabela = ($tipo_usuario === 'tutor' ? 'tutores' : 'cuidadores');

$query = "SELECT codigo_verificacao FROM $tabela WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($codigo_armazenado);
$stmt->fetch();
$stmt->close();

if ($codigo_usuario == $codigo_armazenado) {
    $query_update = "UPDATE $tabela SET telefone_validado = 1 WHERE id = ?";
    $stmt = $mysqli->prepare($query_update);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->close();

    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Código validado com sucesso!'
    ]);
} else {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Código de verificação incorreto. Tente novamente.'
    ]);
}

$mysqli->close();
?>
