<?php
require '../../login/conexaobd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bio'])) {
    $novaBio = $_POST['bio'];
    $cuidador_id = $_POST['cuidador_id'];

    $sql = "UPDATE cuidadores SET bio = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('si', $novaBio, $cuidador_id);
        if ($stmt->execute()) {
            header('Location: perfil.php');
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