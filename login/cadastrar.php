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

    if($tipo_usuario === 'tutor'){
        $stmt = $mysqli->prepare("SELECT id FROM tutores WHERE email = ? OR cpf = ?");

        $stmt->bind_param("ss", $email, $cpf);
    
        $stmt->execute();
            
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            echo "E-mail ou CPF já cadastrados. Por favor, realize o <a href='formlogin.html'>login</a>.";
            exit();
        }
        
        $stmt->close();
    
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $mysqli->query("INSERT INTO tutores (nome, email, senha, telefone, endereco, dt_nascimento, cpf) VALUES ('$nome_completo', '$email', '$senha_hash', '$telefone', '$endereco', '$data_nascimento', '$cpf')");
    } elseif($tipo_usuario === 'cuidador'){
        $stmt = $mysqli->prepare("SELECT id FROM cuidadores WHERE email = ? OR cpf = ?");

        $stmt->bind_param("ss", $email, $cpf);
    
        $stmt->execute();
            
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            echo "E-mail ou CPF já cadastrados. Por favor, realize o <a href='formlogin.html'>login</a>.";
            exit();
        }
        
        $stmt->close();
    
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $mysqli->query("INSERT INTO cuidadores (nome, email, senha, telefone, endereco, dt_nascimento, cpf) VALUES ('$nome_completo', '$email', '$senha_hash', '$telefone', '$endereco', '$data_nascimento', '$cpf')");
    }
    
    echo "<script>alert('Cadastro bem sucedido. Realize o login para acessar sua conta');window.location.href='formlogin.html';</script>";
    exit();
}

