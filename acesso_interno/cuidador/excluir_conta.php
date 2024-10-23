<?php
session_start();
require '../../login/conexaobd.php';

if (!isset($_SESSION['id'])) {
    header('Location: ../../login/formlogin.html'); 
    exit();
}

$cuidador_id = $_SESSION['id'];

if (isset($_POST['confirmar_exclusao'])) {
    $sql = "DELETE FROM cuidadores WHERE id = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('i', $cuidador_id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            session_destroy();
            header('Location: conta_excluida.php');
        } else {
            echo "Erro ao tentar excluir a conta. Tente novamente mais tarde.";
        }
        
        $stmt->close();
    } else {
        echo "Erro de conexão com o banco de dados.";
    }
}

$mysqli->close();