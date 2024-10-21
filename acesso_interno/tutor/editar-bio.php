<?php
require '../../login/conexaobd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bio'])) {
    $novaBio = $_POST['bio'];
    $tutor_id = $_POST['tutor_id'];

    $sql = "UPDATE tutores SET bio = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('si', $novaBio, $tutor_id);
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
