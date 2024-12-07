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
    $nome_completo = $_SESSION['nome'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $tabela = $tipo_usuario === 'tutor' ? 'tutores' : 'cuidadores';

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
            $mail->Subject = 'Nova Senha';
            $mail->Body    = "Prezado(a) " . $nome_completo . "<br><br>Sua nova senha é: $nova_senha.<br><br><a href='http://localhost:8000/backend/index.php'>Clique aqui</a> Para voltar ao site.<br><br>Esta mensagem foi enviada a você pela empresa SafePet.<br>Nenhum e-mail enviado pela SafePet tem arquivos anexados os solicita o preenchimento de senhas e informações pessoais.<br><br>";
            $mail->AltBody = "Prezado(a) " . $nome_completo . "\n\nSua nova senha é: $nova_senha.\n\nClique no link abaixo para retornar ao site\n\n http://localhost:8000/backend/index.php \n\nEsta mensagem foi enviada a você pela empresa SafePet.\nNenhum e-mail enviado pela SafePet tem arquivos anexados os solicita o preenchimento de senhas e informações pessoais.\n\n";
    
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
