<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

include("../includes/db.php");
include_once("../includes/functions.php");

if (isset($_POST['email']) || isset($_POST['senha']) || isset($_POST['tipo_usuario'])) {

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $tipo_usuario = $_POST['tipo_usuario'] ?? '';

    if (empty($email)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha seu e-mail.']);
        exit();
    }

    if (empty($senha)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha sua senha.']);
        exit();
    }

    if (empty($tipo_usuario)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha o tipo de usuário.']);
        exit();
    }

    $tabela = ($tipo_usuario === 'tutor') ? 'tutores' : 'cuidadores';
    $sql = "SELECT * FROM $tabela WHERE email = ? LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['tipo_usuario'] = $tipo_usuario;

            if($tipo_usuario === 'cuidador'){
                if (verificarPerfilCuidador($mysqli, $_SESSION['id'])) {
                    $_SESSION['notificacao'] = "Para estar elegível para ser procurado por tutores, preencha seu perfil.";
                } else {
                    unset($_SESSION['notificacao']); 
                }
            }

            echo json_encode(['sucesso' => true, 'mensagem' => 'Login realizado com sucesso.']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Senha incorreta. Verifique o tipo de usuário.']);
        }
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail não encontrado. Verifique o tipo de usuário.']);
    }

    $stmt->close();
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados de login incompletos.']);
}
exit();