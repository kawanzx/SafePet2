<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require '../db.php';

    if (isset($_POST['petId'], $_POST['nome'], $_POST['especie'], $_POST['raca'], $_POST['idade'], $_POST['sexo'], $_POST['peso'], $_POST['castrado'], $_POST['descricao'])) {
        $petId = $_POST['petId'];
        $nome = $_POST['nome'];
        $especie = $_POST['especie'];
        $raca = $_POST['raca'];
        $idade = $_POST['idade'];
        $sexo = $_POST['sexo'];
        $peso = $_POST['peso'];
        $castrado = $_POST['castrado'];
        $descricao = $_POST['descricao'];

        $sql = "UPDATE pets SET nome = ?, raca = ?, especie = ?, idade = ?, sexo = ?, peso = ?, castrado = ?, descricao = ? WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sssisdssi", $nome, $raca, $especie, $idade, $sexo, $peso, $castrado, $descricao,$petId);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "sucesso";
                } else {
                    echo " Nenhuma alteração foi realizada.";
                }
            } else {
                echo "Erro ao executar a atualização: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Erro ao preparar a consulta SQL: " . $mysqli->error;
        }
        $mysqli->close();

    } else {
        echo "Dados faltando!";
    }
}
