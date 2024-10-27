<?php
session_start();
require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bio'])) {
    $novaBio = $_POST['bio'];
    $usuario_id = $_SESSION['id'];
    $tipo_usuario = $_SESSION['tipo_usuario'];

    $tabela = ($tipo_usuario === 'tutor') ? 'tutores' : 'cuidadores';

    $sql = "UPDATE $tabela SET bio = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('si', $novaBio, $usuario_id);
        if ($stmt->execute()) {
            $redirectUrl = ($tipo_usuario === 'tutor') ? '../../views/tutor/perfil.php' : '../../views/cuidador/perfil.php';
            header("Location: $redirectUrl");
        } else {
            echo "<p>Erro ao atualizar a bio.</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Erro na preparação da consulta.</p>";
    }
} else {
    echo "<p>Dados inválidos.</p>";
}

$mysqli->close();
