<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['experiencia'])) {
    $novaExperiencia = $_POST['experiencia'];
    $usuario_id = $_SESSION['id'];
  



    $sql = "UPDATE cuidadores SET experiencia = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('si', $novaExperiencia, $usuario_id);
        if ($stmt->execute()) {
            header("Location: ../../../../views/cuidador/perfil.php");
        } else {
            echo "<p>Erro ao atualizar a experiencia.</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Erro na preparação da consulta.</p>";
    }
} else {
    echo "<p>Dados inválidos.</p>";
}

$mysqli->close();
