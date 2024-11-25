<?php
session_start();
require '../db.php';

if (!isset($_SESSION['id'])) {
    header('Location: /auth/login.html'); 
    exit();
}

$user_id = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$data_exclusao = date('Y-m-d H:i:s');
$email_ficticio = "excluido{$user_id}@safepet.com";
$cpf_ficticio = "EXCLUIDO-{$user_id}";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_exclusao'])) {
    if ($tipo_usuario === 'tutor'){
        $sql = "UPDATE tutores SET ativo = 0, nome = 'Usuário Excluído', email = ?, senha = 'EXCLUIDO', telefone = NULL, cep = NULL, endereco = NULL, complemento = NULL, bairro = NULL, cidade = NULL, uf = NULL, dt_nascimento = NULL, foto_perfil = NULL, bio = NULL, cpf = ?, data_exclusao = ? WHERE id = ?";
    } else {
        $sql = "UPDATE cuidadores SET ativo = 0, nome = 'Usuário Excluído', email = ?, senha = 'EXCLUIDO', telefone = NULL, cep = NULL, endereco = NULL, complemento = NULL, bairro = NULL, cidade = NULL, uf = NULL, dt_nascimento = NULL, foto_perfil = NULL, bio = NULL, cpf = ?, preco_hora = NULL, experiencia = NULL, data_exclusao = ? WHERE id = ?";
    }
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('sssi',$email_ficticio, $cpf_ficticio, $data_exclusao, $user_id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            session_destroy();
            header('Location: ../../views/shared/conta_excluida.php');
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