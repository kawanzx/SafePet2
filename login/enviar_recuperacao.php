<?php
include("conexaobd.php");

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $tipo_usuario = $_POST['tipo_usuario'];

    if($tipo_usuario === 'tutor'){
        $stmt = $mysqli->prepare("SELECT id FROM tutores WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $nova_senha = bin2hex(random_bytes(4));
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare("UPDATE tutores SET senha = ? WHERE email = ?");
            $stmt->bind_param("ss", $nova_senha_hash, $email);
            $stmt->execute();

            mail($email, "Nova Senha", "Sua nova senha é: $nova_senha");

            echo "Uma nova senha foi enviada para seu e-mail.";
        } else {
            echo "E-mail não encontrado.";
        }
    } else if($tipo_usuario === 'cuidador'){
        $stmt = $mysqli->prepare("SELECT id FROM cuidadores WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $nova_senha = bin2hex(random_bytes(4));
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare("UPDATE cuidadores SET senha = ? WHERE email = ?");
            $stmt->bind_param("ss", $nova_senha_hash, $email);
            $stmt->execute();

            mail($email, "Nova Senha", "Sua nova senha é: $nova_senha");

            echo "Uma nova senha foi enviada para seu e-mail.";
        } else {
            echo "E-mail não encontrado.";
        }
    }
}
?>