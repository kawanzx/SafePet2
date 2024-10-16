<?php
session_start();
include '../../login/conexaobd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tutor_id = $_SESSION['id'];
    $nome = trim($_POST['nome-tutor']);
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $dt_nascimento = $_POST['dt_nascimento'];


    $stmt = $mysqli->prepare("SELECT id FROM tutores WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $tutor_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "E-mail já cadastrado.";
        exit();
    }

    $stmt->close();

    $query = "UPDATE tutores SET nome = ?, email = ?, endereco = ?, telefone = ?, dt_nascimento = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssssi", $nome, $email, $endereco, $telefone, $dt_nascimento, $tutor_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "sucesso";
        } else {
            echo "Nenhuma alteração foi realizada no banco de dados.";
        }
    } else {
        echo "Erro ao atualizar informações: " . $stmt->error;
    }

    $stmt->close();
}
