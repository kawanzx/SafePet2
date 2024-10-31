<?php
session_start();
require '../db.php';

if (!isset($_SESSION['id'])) {
    header('Location: /auth/login.html'); 
    exit();
}

$user_id = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$tabela = ($tipo_usuario === 'tutor') ? 'tutores' : 'cuidadores';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_exclusao'])) {

    $sql = "DELETE FROM $tabela WHERE id = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            session_destroy();
            header('Location: /views/shared/conta_excluida.php');
            exit();
        } else {
            echo "Erro ao tentar excluir a conta. Tente novamente mais tarde.";
        }
        
        $stmt->close();
    } else {
        echo "Erro de conexão com o banco de dados.";
    }
} else {
    echo "Ação inválida.";
}

$mysqli->close();