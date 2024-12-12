<?php
ob_start();

include_once("../includes/db.php");
require_once __DIR__ . '/../lib/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../includes/');
$dotenv->load();

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $tabela = $tipo_usuario === 'tutor' ? 'tutores' : 'cuidadores';

    $sql = "SELECT nome FROM $tabela WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($nome_completo);
    $stmt->fetch();
    $stmt->close();

    $stmt = $mysqli->prepare("SELECT id FROM $tabela WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $nova_senha = bin2hex(random_bytes(4));
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $chave_hash = password_hash($email . date("Y-m-d H:i:s"), PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("UPDATE $tabela SET senha = ? WHERE email = ?");
        $stmt->bind_param("ss", $nova_senha_hash, $email);
        $stmt->execute();

        try {
            $mail = new PHPMailer(true);

            $mail->CharSet = "UTF-8";
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAILTRAP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAILTRAP_USERNAME'];
            $mail->Password   = $_ENV['MAILTRAP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 2525;

            // Recipients
            $mail->setFrom('suporte.safepet@gmail.com', 'SafePet');
            $mail->addAddress($email, $nome_completo);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Redefinição de Senha - SafePet';
            $mail->Body    = "
            <div style='background-color: #f5f5f5; padding: 20px; font-family: Arial, sans-serif;'>
                <div style='max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>
                    <h1 style='text-align: center; color: #333;'>Redefinição de Senha</h1>
                    <p>Olá, <strong>$nome_completo</strong>,</p>
                    <p>Recebemos uma solicitação para redefinir a sua senha na plataforma <strong>SafePet</strong>. Para sua segurança, geramos uma nova senha temporária:</p>
                    <div style='background-color: #f0f0f0; padding: 15px; text-align: center; border-radius: 5px; margin: 20px 0;'>
                        <strong style='font-size: 18px; color: #2f4d77;'>$nova_senha</strong>
                    </div>
                    <p>Recomendamos que você altere sua senha assim que possível, acessando a área de configurações da sua conta.</p>
                    <p>Clique no botão abaixo para fazer login no site:</p>
                    <div style='text-align: center; margin: 20px 0;'>
                        <a href='http://localhost:8000/backend/index.php' 
                            style='background-color: #2f4d77; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block;'>Acesse sua conta
                        </a>
                    </div>
                    <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>
                    <p style='color: #888;'>Esta mensagem foi enviada automaticamente pela equipe da SafePet. Não é necessário responder a este e-mail.</p>
                    <p style='color: #888;'><em>Importante:</em> A SafePet nunca solicita informações pessoais ou senhas por e-mail. Se você receber mensagens suspeitas, entre em contato conosco imediatamente.</p>
                    <p style='color: #333;'>Atenciosamente,<br>Equipe SafePet</p>
                    </div>
            </div>
            ";
            $mail->AltBody = "
            Olá, $nome_completo,

            Recebemos uma solicitação para redefinir a sua senha na plataforma SafePet. Para sua segurança, geramos uma nova senha temporária:

            Sua nova senha: $nova_senha

            Recomendamos que você altere sua senha assim que possível, acessando a área de configurações da sua conta.

            Clique no link abaixo para fazer login no site:
            http://localhost:8000/backend/index.php

            -------------------------------
            Esta mensagem foi enviada automaticamente pela equipe da SafePet. Não é necessário responder a este e-mail.

            Importante: A SafePet nunca solicita informações pessoais ou senhas por e-mail. Se você receber mensagens suspeitas, entre em contato conosco imediatamente.

            Atenciosamente,
            Equipe SafePet
            ";

            $mail->send();
            ob_clean();
            echo json_encode(['sucesso' => true, 'mensagem' => 'Uma nova senha foi enviada para seu e-mail.']);
        } catch (Exception $e) {
            ob_clean();
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao enviar e-mail.']);
        }
        exit();
    } else {
        ob_clean();
        echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail não encontrado.']);
        exit();
    }
}
