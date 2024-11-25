<?php
session_start();
include '../db.php';
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //$tutor_id = $_SESSION['id'];
    $tipo_usuario = $_SESSION['tipo_usuario'] ?? 'tutor';
    $usuario_id = $_SESSION['id'];
    $nome = trim($_POST['nome-tutor']);
    $cep = trim($_POST['cep']);
    $endereco = trim($_POST['endereco']);
    $complemento = trim($_POST['complemento']);
    $bairro = trim($_POST['bairro']);
    $cidade = trim($_POST['cidade']);
    $uf = trim($_POST['uf']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $dt_nascimento = $_POST['dt_nascimento'];

    $tabela = ($tipo_usuario === 'tutor') ? 'tutores' : 'cuidadores';

    $stmt = $mysqli->prepare("SELECT id FROM $tabela WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $usuario_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "E-mail já cadastrado.";
        exit();
    } elseif (!validarEmail($email)){
        echo ('E-mail inválido.');
        exit();
    } elseif (!validarDataNascimento($dt_nascimento)) {
        echo ('Você deve ter entre 18 e 110 anos.');
        exit();
    } elseif (!validarTelefone($telefone)){
        echo ('Número de telefone inválido');
    }

    $stmt->close();

    $query = "UPDATE $tabela SET nome = ?, email = ?, cep = ?, endereco = ?, complemento = ?, bairro = ?, cidade = ?, uf = ?, telefone = ?, dt_nascimento = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssssssssssi", $nome, $email, $cep, $endereco, $complemento, $bairro, $cidade, $uf, $telefone, $dt_nascimento, $usuario_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "sucesso";
        } else {
            echo "Nenhuma alteração foi realizada.";
        }
    } else {
        echo "Erro ao atualizar informações: " . $stmt->error;
    }

    $stmt->close();
}
