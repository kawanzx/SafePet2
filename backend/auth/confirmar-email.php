<?php
session_start();

include_once '../includes/db.php';

$chave = filter_input(INPUT_GET, "chave", FILTER_DEFAULT);
$chave = htmlspecialchars($chave, ENT_QUOTES, 'UTF-8');
$tipo_usuario = $_SESSION['tipo_usuario'];
$user_id = $_SESSION['id'];
$tabela = ($tipo_usuario === 'tutor') ? 'tutores' : 'cuidadores';

$stmt = $mysqli->prepare("SELECT sit_usuario_id FROM $tabela WHERE id = ?");

if (!$stmt) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no servidor. Tente novamente mais tarde.']);
    exit();
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($situacao_usuario);
$stmt->fetch();
$stmt->close();

if ($situacao_usuario === 1) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'E-email confirmado']);
    exit();

} elseif(!empty($chave)){
    $stmt = $mysqli->prepare("SELECT id FROM $tabela WHERE chave = ? LIMIT 1");

    if (!$stmt) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no servidor. Tente novamente mais tarde.']);
        exit();
    }

    $stmt->bind_param("s", $chave);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows != 0) {
        $sql = "UPDATE $tabela SET sit_usuario_id = 1, chave = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $chave = NULL;
        $stmt->bind_param("si", $chave, $user_id);
        if ($stmt->execute()){
            echo json_encode(['sucesso' => true, 'mensagem' => 'E-mail confirmado']);
            header('Location: /backend/index.php');
            exit();
        } else{
            echo json_encode(['sucesso' => false, 'mensagem' => "E-mail não confirmado"]);
            header('Location: /backend/index.php');
            exit();
        }
    } else{
        echo json_encode(['sucesso' => false, 'mensagem' => "Endereço não validado"]);
        header('Location: /backend/index.php');
        exit();
    }
    $stmt->close();
} else{
    echo json_encode(['sucesso' => false, 'mensagem' => "Endereço não validado"]);
    exit();
}
