<?php
header('Content-Type: application/json');

session_start();
include '../db.php';
include '../functions.php';

json_decode(file_get_contents('php://input'), true);

$usuario_id = $_SESSION['id'];
$codigo_usuario = $_POST['codigo'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$tabela = ($tipo_usuario === 'tutor') ? 'tutores' : 'cuidadores';

$query = "SELECT codigo_verificacao FROM $tabela WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($codigo_armazenado);
$stmt->fetch();
$stmt->close();

if ($codigo_usuario) {
    if ($codigo_usuario == $codigo_armazenado) {
        echo json_encode([
            'sucesso' => true,
            'mensagem' => 'Código validado com sucesso!'
        ]);
        $sql = "UPDATE $tabela SET `codigo_verificacao` = '' WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $stmt->close();
        exit;
    } else {
        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Código de verificação incorreto. Tente novamente.'
        ]);
        exit;
    }
}

$nome = trim($_POST['nome'] ?? '');
$cep = trim($_POST['cep'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$email = trim($_POST['email'] ?? '');
$complemento = trim($_POST['complemento'] ?? '');
$bairro = trim($_POST['bairro'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$uf = trim($_POST['uf'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$dt_nascimento = trim($_POST['dt_nascimento'] ?? '');

$stmt = $mysqli->prepare("SELECT id FROM $tabela WHERE email = ? AND id != ?");
$stmt->bind_param("si", $email, $usuario_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail já cadastrado.']);
    exit();
} elseif (!validarNome($nome)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nome inválido']);
    exit();
} elseif (!validarEmail($email)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'E-mail inválido.']);
    exit();
}  elseif (!validarTelefone($telefone)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Telefone inválido.']);
    exit();
}  elseif (!validarDataNascimento($dt_nascimento)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Você deve ter pelo menos 18 anos.']);
    exit();
}

$stmt->close();

$query = "UPDATE $tabela SET nome = ?, email = ?, cep = ?, endereco = ?, complemento = ?, bairro = ?, cidade = ?, uf = ?, telefone = ?, dt_nascimento = ? WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("ssssssssssi", $nome, $email, $cep, $endereco, $complemento, $bairro, $cidade, $uf, $telefone, $dt_nascimento, $usuario_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Dados atualizados!']);
        exit;
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhuma alteração foi realizada.']);
        exit;
    }
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar informações: ' . $stmt->error]);
    exit;
}

$stmt->close();
$mysqli->close();
