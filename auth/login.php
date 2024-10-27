<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/protect.php';
include("../includes/db.php");

if (isset($_POST['email']) || isset($_POST['senha']) || isset($_POST['tipo_usuario'])) {

    if (strlen($_POST['email']) == 0) {
        echo "<script>alert ('Preencha seu e-mail.'); window.location.href = 'login.html'; </script>";
    } else if (strlen($_POST['senha']) == 0) {
        echo "Preencha sua senha";
        echo "<script>alert ('Preencha sua senha.'); window.location.href = 'login.html'; </script>";
    } else if (strlen($_POST['tipo_usuario']) == 0) {
        echo "<script>alert ('Preencha o tipo de usuário.'); window.location.href = 'login.html'; </script>";
    } else {
        $email = ($_POST['email']);
        $senha = ($_POST['senha']);
        $tipo_usuario = ($_POST['tipo_usuario']);
        
        if ($tipo_usuario === 'tutor') {
            $sql_exec = $mysqli->query("SELECT * FROM tutores WHERE email = '$email' LIMIT 1 ") or die("Falha na conexão com o banco de dados: " . $mysqli->error);
        } else if ($tipo_usuario === 'cuidador') {
            $sql_exec = $mysqli->query("SELECT * FROM cuidadores WHERE email = '$email' LIMIT 1 ") or die("Falha na conexão com o banco de dados: " . $mysqli->error);
        }

        if ($sql_exec->num_rows > 0) {
            $usuario = $sql_exec->fetch_assoc();

            if (password_verify($senha, $usuario['senha'])) {

                if (!isset($_SESSION)) {
                    session_start();
                }

                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];


                if ($tipo_usuario === 'tutor') {

                    $_SESSION['tipo_usuario'] = 'tutor';
                    header("Location: /views/tutor/buscar.php");
                } else if ($tipo_usuario === 'cuidador') {

                    $_SESSION['tipo_usuario'] = 'cuidador';
                    header("Location: /views/shared/agendamentos.php");
                }
            } else {
                echo "<script>alert ('Senha incorreta. Verifique se o tipo de usuário informado corresponde ao cadastrado.'); window.location.href = 'login.html'; </script>";
            }
        } else {
            echo "<script>alert('E-mail não encontrado. Verifique se o tipo de usuário informado está correto.'); window.location.href = 'login.html'; </script>";
        }
    }
}
