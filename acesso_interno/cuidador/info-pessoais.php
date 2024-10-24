<?php
session_start();
include '../../login/conexaobd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cuidador_id = $_SESSION['id'];
    $nome = trim($_POST['nome-cuidador']);
    $cep = trim($_POST['cep']);
    $endereco = trim($_POST['endereco']);
    $complemento = trim($_POST['complemento']);
    $bairro = trim($_POST['bairro']);
    $cidade = trim($_POST['cidade']);
    $uf = trim($_POST['uf']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $dt_nascimento = $_POST['dt_nascimento'];


    $stmt = $mysqli->prepare("SELECT id FROM cuidadores WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $cuidador_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "E-mail já cadastrado.";
        exit();
    }

    $stmt->close();

    $query = "UPDATE cuidadores SET nome = ?, email = ?, cep = ?, endereco = ?, complemento = ?, bairro = ?, cidade = ?, uf = ?, telefone = ?, dt_nascimento = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssssssssssi", $nome, $email, $cep, $endereco, $complemento, $bairro, $cidade, $uf, $telefone, $dt_nascimento, $cuidador_id);

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