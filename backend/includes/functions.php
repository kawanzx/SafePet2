<?php
require 'db.php';
require_once __DIR__ . '/../lib/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Twilio\Rest\Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function validarNome($nome_completo)
{
    return preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ\s\'-]{2,}$/u', $nome_completo);
}

function validarEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return false;
    }

    $dominio = substr(strrchr($email, '@'), 1);

    if (!checkdnsrr($dominio, 'MX')) {
        return false;
    }

    return true;
}

function validarCPF($cpf)
{
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Validação dos dígitos verificadores
    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }

    return true;
}

function validarTelefone($telefone)
{
    return preg_match('/^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/', $telefone);
}

function validarSenha($senha)
{
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{6,}$/', $senha);
}

function validarDataNascimento($data_nascimento)
{
    $dataNascimento = DateTime::createFromFormat('Y-m-d', $data_nascimento);

    if (!$dataNascimento) {
        return false;
    }

    $dataAtual = new DateTime();
    if ($dataNascimento > $dataAtual) {
        return false;
    }

    $idade = $dataAtual->diff($dataNascimento)->y;

    $idadeMaxima = 110;
    if ($idade > $idadeMaxima) {
        return false;
    }

    return $idade >= 18;
}

function getTutorProfile($mysqli, $tutor_id)
{
    $sql = "SELECT nome, bio, foto_perfil, ativo, cep FROM tutores WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $tutor_id);
    $stmt->execute();
    $stmt->bind_result($nome, $bio, $foto_perfil, $ativo, $cep);
    $stmt->fetch();
    $stmt->close();

    if (isset($bio)) {
        $bio;
    } else {
        $bio = '';
    };

    return [
        'nome' => htmlspecialchars($nome),
        'bio' => htmlspecialchars($bio),
        'foto_perfil' => !empty($foto_perfil) ? '/backend/assets/uploads/fotos-tutores/' . $foto_perfil : '/backend/img/profile-circle-icon.png',
        'ativo' => htmlspecialchars($ativo),
        'cep' => htmlspecialchars($cep),
    ];
}

function getCuidadorProfile($mysqli, $cuidador_id)
{
    $sql = "SELECT nome, bio, foto_perfil, experiencia, preco_hora, ativo FROM cuidadores WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $cuidador_id);
    $stmt->execute();
    $stmt->bind_result($nome, $bio, $foto_perfil, $experiencia, $preco_hora, $ativo);
    $stmt->fetch();
    $stmt->close();

    if (isset($bio)) {
        $bio;
    } else {
        $bio = '';
    };

    if ($preco_hora != '' || $preco_hora != null) {
        $preco_hora;
    } else {
        $preco_hora = '00,00';
    };

    if ($experiencia != '' || $experiencia != null) {
        $experiencia;
    } else {
        $experiencia = 'Ainda não há experiências';
    };

    return [
        'nome' => htmlspecialchars($nome),
        'bio' => htmlspecialchars($bio),
        'foto_perfil' => !empty($foto_perfil) ? '/backend/assets/uploads/fotos-cuidadores/' . $foto_perfil : '/backend/img/profile-circle-icon.png',
        'experiencia' => htmlspecialchars($experiencia),
        'preco_hora' => htmlspecialchars($preco_hora),
        'ativo' => htmlspecialchars($ativo)
    ];
}

function getPetsByTutor($mysqli, $tutor_id)
{
    $sql = "SELECT id, nome, especie, raca, idade, sexo, peso, castrado, descricao, foto FROM pets WHERE tutor_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $tutor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pets = [];

    while ($row = $result->fetch_assoc()) {
        $pets[] = $row;
    }

    $stmt->close();
    return $pets;
}

function getPetNamesByIds($mysqli, $petIds)
{
    if (empty($petIds)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($petIds), '?'));
    $sql = "SELECT id, nome FROM pets WHERE id IN ($placeholders)";
    $stmt = $mysqli->prepare($sql);

    $types = str_repeat('i', count($petIds));
    $stmt->bind_param($types, ...$petIds);
    $stmt->execute();
    $result = $stmt->get_result();

    $pets = [];
    while ($row = $result->fetch_assoc()) {
        $pets[$row['id']] = $row['nome'];
    }

    $stmt->close();
    return $pets;
}

function getPets($mysqli, $tutor_id)
{
    $sql = "SELECT id, nome, especie, raca, idade, sexo, peso, castrado, descricao, foto FROM pets WHERE tutor_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $tutor_id);
    $stmt->execute();
    return $stmt->get_result();
}

function getInformacoesUsuario($mysqli, $usuario_id, $tipo_usuario)
{
    $tabela = ($tipo_usuario === 'tutor') ? 'tutores' : 'cuidadores';
    $sql = "SELECT nome, cep, endereco, complemento, bairro, cidade, uf, telefone, email, dt_nascimento, cpf FROM $tabela WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    $stmt->bind_result($nome, $cep, $endereco, $complemento, $bairro, $cidade, $uf, $telefone, $email, $dt_nascimento, $cpf);
    $stmt->fetch();
    $stmt->close();

    return [
        'nome' => $nome,
        'cep' => $cep,
        'endereco' => $endereco,
        'complemento' => $complemento,
        'bairro' => $bairro,
        'cidade' => $cidade,
        'uf' => $uf,
        'telefone' => $telefone,
        'email' => $email,
        'dt_nascimento' => $dt_nascimento,
        'cpf' => $cpf
    ];
}

function getAgendamentosByUser($mysqli, $user_id, $tipo_usuario, $status)
{
    $id = ($tipo_usuario === 'tutor') ? 'tutor_id' : 'cuidador_id';
    $sql = "SELECT *  FROM agendamentos WHERE $id = ? AND status = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('is', $user_id, $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $agendamentos = [];

    while ($row = $result->fetch_assoc()) {
        $agendamentos[] = $row;
    }

    $stmt->close();

    return $agendamentos;
}

function updateAgendamentoStatus($mysqli, $agendamento_id, $novo_status)
{
    $sql = "UPDATE agendamentos SET status = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('si', $novo_status, $agendamento_id);
        if ($stmt->execute()) {
            return true;
        }
        $stmt->close();
    }
    return false;
}

function enviarEmail($email, $nome_completo, $chave_hash)
{
    $mail = new PHPMailer(true);

    $mail->CharSet = "UTF-8";
    $mail->isSMTP();
    $mail->Host       = $_ENV['MAILTRAP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['MAILTRAP_USERNAME'];
    $mail->Password   = $_ENV['MAILTRAP_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 2525;

    //Recipients
    $mail->setFrom('suporte.safepet@gmail.com', 'SafePet');
    $mail->addAddress($email, $nome_completo);

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Confirmar o e-mail';
    $mail->Body    = "Prezado(a) " . $nome_completo . "<br><br>Agradecemos a sua solicitação de cadastro em nosso site!<br><br>Para que possamos liberar o seu acesso ao sistema, solicitamos a confirmação do e-mail clicando no link abaixo: <br><br> <a href='http://localhost:8000/backend/auth/confirmar-email.php?chave=$chave_hash'>Clique aqui</a><br><br>Esta mensagem foi enviada a você pela empresa SafePet.<br>Nenhum e-mail enviado pela SafePet tem arquivos anexados os solicita o preenchimento de senhas e informações pessoais.<br><br>";
    $mail->AltBody = "Prezado(a) " . $nome_completo . "\n\nAgradecemos a sua solicitação de cadastro em nosso site!\n\nPara que possamos liberar o seu acesso ao sistema, solicitamos a confirmação do e-mail clicando no link abaixo: \n\n http://localhost:8000/backend/auth/confirmar-email.php?chave=$chave_hash \n\nEsta mensagem foi enviada a você pela empresa SafePet.\n>Nenhum e-mail enviado pela SafePet tem arquivos anexados os solicita o preenchimento de senhas e informações pessoais.\n\n ";

    $mail->send();
    return true;
}

function enviarSMS($telefone, $codigo)
{
    if (!validarTelefone($telefone)) {
        return ['sucesso' => false, 'mensagem' => 'Número de telefone inválido.'];
    }

    $telefone = preg_replace('/\D/', '', $telefone);
    $telefone = '+55' . $telefone;

    // Credenciais do Twilio
    $sid = $_ENV['TWILIO_SID'] ?? null;
    $token = $_ENV['TWILIO_TOKEN'] ?? null;
    $twilioNumber = $_ENV['TWILIO_NUMBER'] ?? null;

    if (!$sid || !$token || !$twilioNumber) {
        error_log('Credenciais Twilio ausentes.');
        return ['sucesso' => false, 'mensagem' => 'Erro interno. Serviço de SMS indisponível.'];
    }

    try {
        $client = new Client($sid, $token);
        $message = $client->messages->create(
            $telefone,
            [
                'from' => $twilioNumber,
                'body' => "Seu código de verificação é: $codigo"
            ]
        );

        return ['sucesso' => true, 'sid' => $message->sid];
    } catch (Exception $e) {
        error_log("Erro ao enviar SMS para o número $telefone: " . $e->getMessage());
        return ['sucesso' => false, 'mensagem' => "Ocorreu um erro ao enviar SMS! Tente novamente mais tarde."];
    }
}

function criarNotificacao($mysqli, $agendamento_id, $id_remetente, $tipo_remetente, $tipo_notificacao, $id_destinatario, $tipo_destinatario, $mensagem)
{
    $stmt = $mysqli->prepare("
        INSERT INTO notificacoes (agendamento_id, remetente_id, tipo_remetente, tipo_notificacao, destinatario_id, tipo_destinatario, mensagem, lida, data_criacao) 
        VALUES (?, ?, ?, ?, ?, ?, ?, FALSE, NOW())
    ");
    if ($stmt) {
        $stmt->bind_param("iississ", $agendamento_id, $id_remetente, $tipo_remetente, $tipo_notificacao, $id_destinatario, $tipo_destinatario, $mensagem);
        if (!$stmt->execute()) {
            error_log("Erro ao executar a query: " . $stmt->error);
            echo json_encode(['status' => 'error', 'message' => 'Erro ao executar a query']);
            exit;
        }
        $stmt->close();
    } else {
        error_log("Erro ao preparar o statement: " . $mysqli->error);
        error_log("Erro ao preparar o statement: " . $mysqli->error);
    }
}

function buscarNotificacoesNaoLidas($mysqli, $user_id, $tipo_usuario)
{
    $stmt = $mysqli->prepare("
        SELECT id, agendamento_id, remetente_id, tipo_remetente, mensagem, data_criacao, tipo_notificacao 
        FROM notificacoes 
        WHERE destinatario_id = ? AND tipo_destinatario = ? AND lida = FALSE 
        ORDER BY data_criacao DESC
    ");
    $stmt->bind_param("is", $user_id, $tipo_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $notificacoes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $notificacoes;
}

function enviarNotificacao($mysqli, $agendamento_id, $id_remetente, $tipo_remetente, $tipo_notificacao, $id_destinatario, $tipo_destinatario, $mensagem)
{
    criarNotificacao($mysqli, $agendamento_id, $id_remetente, $tipo_remetente, $tipo_notificacao, $id_destinatario, $tipo_destinatario, $mensagem);

    // Simular envio de notificação via WebSocket
    enviarNotificacaoWebSocket($id_destinatario, $mensagem);
}


function enviarNotificacaoWebSocket($userId, $mensagem)
{
    $data = json_encode(['userId' => $userId, 'mensagem' => $mensagem]);

    $ch = curl_init('http://localhost:3000');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ]);
    curl_exec($ch);
    curl_close($ch);
}