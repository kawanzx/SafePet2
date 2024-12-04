<?php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

include('../includes/db.php');
include('../includes/functions.php');
require_once '../vendor/autoload.php';

error_reporting(0);
ini_set('display_errors', '0');

if (isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];
    $nome_completo = trim($_POST['nome_completo']);
    $telefone = preg_replace('/\D/', '', $_POST['telefone']);
    $codigo = rand(100000, 999999);
    $tipo_usuario = $_POST['tipo_usuario'];
    $data_nascimento = $_POST['data_nascimento'];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $confirmar_senha = $_POST['confirmar_senha'];
    $genero = $_POST['genero'];

    if (!validarNome($nome_completo)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Nome inválido']);
        exit();
    } elseif (!validarEmail($email)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail inválido.']);
        exit();
    } elseif (!validarCPF($cpf)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'CPF inválido.']);
        exit();
    } elseif (!validarTelefone($telefone)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Telefone inválido.']);
        exit();
    } elseif (!validarSenha($senha)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'A senha deve ter pelo menos 6 caracteres, incluindo números, letras maiúsculas e minúsculas']);
        exit();
    } elseif ($senha !== $confirmar_senha) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'As senhas não coincidem.']);
        exit();
    } elseif (!validarDataNascimento($data_nascimento)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Você deve ter pelo menos 18 anos.']);
        exit();
    }


    $tabela = ($tipo_usuario === 'tutor') ? 'tutores' : 'cuidadores';
    $stmt = $mysqli->prepare("SELECT id FROM $tabela WHERE email = ? OR cpf = ?");

    if (!$stmt) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no servidor. Tente novamente mais tarde.']);
        exit();
    }

    $stmt->bind_param("ss", $email, $cpf);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail ou CPF já cadastrados. Por favor, realize o login.']);
        $stmt->close();
        exit();
    }
    $stmt->close();

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $sql = "INSERT INTO $tabela (nome, email, senha, telefone, dt_nascimento, cpf, genero, codigo_verificacao) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro no servidor. Tente novamente mais tarde.']);
        exit();
    }

    $stmt->bind_param("ssssssss", $nome_completo, $email, $senha_hash, $telefone, $data_nascimento, $cpf, $genero, $codigo);
    if ($stmt->execute()) {
        session_start();
        $_SESSION['id'] = $stmt->insert_id;
        $_SESSION['nome'] = $nome_completo;
        $_SESSION['tipo_usuario'] = $tipo_usuario;

        $smsResult = enviarSMS($telefone, $codigo);

        if (!$smsResult['sucesso']) {
            echo json_encode(['sucesso' => false, 'mensagem' => $smsResult['mensagem']]);
            exit();
        }

        echo json_encode(['sucesso' => true, 'mensagem' => 'Cadastro bem-sucedido!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao cadastrar usuário. Tente novamente.']);
        exit();
    }

    $stmt->close();
    exit();
}
