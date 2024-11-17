<?php
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include ('../includes/db.php');
include ('../includes/functions.php');

if(isset($_POST['email'])){
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];
    $nome_completo = trim($_POST['nome_completo']);
    $telefone = preg_replace('/\D/', '', $_POST['telefone']);
    $tipo_usuario = $_POST['tipo_usuario'];
    $data_nascimento = $_POST['data_nascimento'];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);
    $confirmar_senha = $_POST['confirmar_senha'];

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
    }

    if($tipo_usuario === 'tutor'){
        $stmt = $mysqli->prepare("SELECT id FROM tutores WHERE email = ? OR cpf = ?");

        $stmt->bind_param("ss", $email, $cpf);
    
        $stmt->execute();
            
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail ou CPF já cadastrados. Por favor, realize o login']);
            exit();
        }
        
        $stmt->close();
    
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $mysqli->query("INSERT INTO tutores (nome, email, senha, telefone, dt_nascimento, cpf) VALUES ('$nome_completo', '$email', '$senha_hash', '$telefone', '$data_nascimento', '$cpf')");
    } elseif($tipo_usuario === 'cuidador'){
        $stmt = $mysqli->prepare("SELECT id FROM cuidadores WHERE email = ? OR cpf = ?");

        $stmt->bind_param("ss", $email, $cpf);
    
        $stmt->execute();
            
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail ou CPF já cadastrados. Por favor, realize o login.']);
            exit();
        }
        
        $stmt->close();
    
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $mysqli->query("INSERT INTO cuidadores (nome, email, senha, telefone, dt_nascimento, cpf) VALUES ('$nome_completo', '$email', '$senha_hash', '$telefone', '$data_nascimento', '$cpf')");
    }
    echo json_encode(['sucesso' => true, 'mensagem' => 'Cadastro bem-sucedido. Realize o login']);
    exit();
}

