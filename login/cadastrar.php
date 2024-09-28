<?php
include('conexaobd.php');

if(isset($_POST['email'])){
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];
    $nome_completo = trim($_POST['nome_completo']);
    $telefone = preg_replace('/\D/', '', $_POST['telefone']);
    $tipo_usuario = $_POST['tipo_usuario'];
    $endereco = trim($_POST['endereco']);
    $data_nascimento = $_POST['data_nascimento'];
    $cpf = preg_replace('/\D/', '', $_POST['cpf']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("E-mail inválido.");
    }
    if (!preg_match('/^\d{11}$/', $cpf)) {
        die ('CPF inválido. Deve conter 11 dígitos.');
    }
    if (!preg_match('/^\d{10,11}$/', $telefone)) {
        die("Telefone inválido. Deve conter 10 ou 11 dígitos.");
    }
    if (strlen($senha) < 6) {
        die("A senha deve ter pelo menos 6 caracteres.");
    }

    $stmt = $mysqli->prepare("SELECT id FROM cadastro WHERE email = ? OR cpf = ?");

    $stmt->bind_param("ss", $email, $cpf);

    $stmt->execute();
        
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "E-mail ou CPF já cadastrados. Por favor, realize o <a href='login.php'>login</a>.";
        exit();
    }
    
    $stmt->close();


    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $mysqli->query("INSERT INTO cadastro (nome_completo, email, senha, telefone, tipo_usuario, endereco, data_nascimento, cpf) VALUES ('$nome_completo', '$email', '$senha_hash', '$telefone', '$tipo_usuario', '$endereco', '$data_nascimento', '$cpf')");
    
    echo "<script>alert('Cadastro bem sucedido. Realize o login para acessar sua conta');window.location.href='formlogin.html';</script>";
    exit();
}

