<?php
include '../db.php';
include '../functions.php';

header('Content-Type: application/json');
session_start(); 

$user_id = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];

$notificacoes = buscarNotificacoesNaoLidas($mysqli, $user_id, $tipo_usuario);

if (!$notificacoes) {
    echo json_encode(['status' => 'erro', 'message' => 'Nenhuma notificação encontrada.']);
    exit;
} else {
    echo json_encode($notificacoes);
    exit;
}

foreach ($notificacoes as &$notificacao) {
    $notificacao['remetente_id'] = $notificacao['id_remetente'];
    $notificacao['tipo_remetente'] = $notificacao['tipo_remetente'];
}

echo json_encode($notificacoes);

