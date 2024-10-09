<?php

include("conexaobd.php");

if (isset($_POST['email']) || isset($_POST['senha']) || isset($_POST['tipo_usuario'])) {

    if (strlen($_POST['email']) == 0) {
        echo "Preencha seu e-mail";
    } else if (strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
    } else if (strlen($_POST['tipo_usuario']) == 0) {
        echo "Preencha o tipo de usuário";
    } else {
        $email = ($_POST['email']);
        $senha = ($_POST['senha']);
        $tipo_usuario = ($_POST['tipo_usuario']);

        if ($tipo_usuario === 'tutor') {
            $sql_exec = $mysqli->query("SELECT * FROM tutores WHERE email = '$email' LIMIT 1 ") or die("Falha na conexão com o banco de dados: " . $mysqli->error);
        } else if ($tipo_usuario === 'cuidador') {
            $sql_exec = $mysqli->query("SELECT * FROM cuidadores WHERE email = '$email' LIMIT 1 ") or die("Falha na conexão com o banco de dados: " . $mysqli->error);
        }

        $usuario = $sql_exec->fetch_assoc();
        if (password_verify($senha, $usuario['senha'])) {

            if (!isset($_SESSION)) {
                session_start();
            }

            // $_SESSION['id'] = $usuario['id'];
            // $_SESSION['nome'] = $usuario['nome'];
            // $_SESSION['tipo_usuario'] ;

            if ($tipo_usuario === 'tutor') {  
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['tipo_usuario'] = 'tutor' ;
                header("Location: ../acesso_interno/tutor/pesquisar.php");
            } else if ($tipo_usuario === 'cuidador') {
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['tipo_usuario'] = 'cuidador';
                header("Location: ../acesso_interno/cuidador/pesquisar.php");
            }
        } else {
            echo "E-mail ou senha incorretos. Verifique se o tipo de usuário informado corresponde ao cadastrado";
        }
    }
}
