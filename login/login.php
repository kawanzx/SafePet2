<?php

include("conexaobd.php");

if(isset ($_POST['email']) || isset($_POST['senha'])){

    if(strlen($_POST['email']) == 0){
        echo "Preencha seu e-mail";
    }else if(strlen($_POST['senha']) == 0){
        echo "Preencha sua senha";
    }else{
        $email = ($_POST['email']);
        $senha = ($_POST['senha']);

        $sql_exec = $mysqli->query("SELECT * FROM cadastro WHERE email = '$email' LIMIT 1 ") or die("Falha na conexão com o banco de dados: " . $mysqli->error);

        $usuario = $sql_exec->fetch_assoc(); 
        if(password_verify($senha, $usuario['senha'])){

            if(!isset($_SESSION)){
                session_start();
            }

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome_completo'];

            header("Location: ../agendamento.php");
        }else{
            echo "E-mail ou senha incorretos";
        }
    }
    
}


