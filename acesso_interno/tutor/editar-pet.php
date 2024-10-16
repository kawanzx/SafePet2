<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    require '../../login/conexaobd.php';

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
        $foto_atual = $_POST['foto_atual'];

        // Verificar se foi enviada uma nova foto
        if (!empty($_FILES['foto']['name'])) {
            $foto_nome = basename($_FILES['foto']['name']);
            $foto_extensao = strtolower(pathinfo($foto_nome, PATHINFO_EXTENSION));
            $foto_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($foto_extensao, $foto_permitidas)) {
                $foto_caminho = 'uploads/' . $foto_nome;

                // Verificar se o upload foi bem-sucedido
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $foto_caminho)) {
                    $foto = $foto_caminho; // Definir o caminho da nova foto
                } else {
                    echo "Erro ao fazer upload da foto.";
                    exit;
                }
            } else {
                echo "Formato de arquivo inválido. Permitidos: jpg, jpeg, png, gif.";
                exit;
            }
        }else {
            // Manter a foto antiga
            $foto = $foto_atual;
        }

        $sql = "UPDATE pets SET nome = ?, raca = ?, especie = ?, idade = ?, sexo = ?, peso = ?, castrado = ?, descricao = ?, foto = ? WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sssisdsssi", $nome, $raca, $especie, $idade, $sexo, $peso, $castrado, $descricao, $foto, $petId);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "sucesso";
                } else {
                    echo "Nenhuma alteração foi realizada no banco de dados.";
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
