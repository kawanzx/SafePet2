<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

$tipo_usuario = $_SESSION['tipo_usuario'];
$id_usuario = $_SESSION['id'];
$tabela = ($tipo_usuario === 'tutor' ? 'tutores' : 'cuidadores');
$novo_codigo = rand(100000, 999999);

$sql = "SELECT telefone FROM $tabela WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    $telefone = $usuario['telefone'];
    var_dump($telefone);

    if (!$telefone) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Telefone não informado.']);
        exit;
    }

    $query = "UPDATE $tabela SET codigo_verificacao = ? WHERE telefone = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("is", $novo_codigo, $telefone);
    $stmt->execute();

    // Envia o SMS com o novo código
    if (enviarSMS($telefone, $novo_codigo)) {
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao enviar SMS.']);
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Telefone não encontrado.']);
}
