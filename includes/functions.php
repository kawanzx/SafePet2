<?php
require_once 'db.php';

function validarNome($nome_completo) {
    return preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ\s]{2,}$/', $nome_completo);
}

function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validarCPF($cpf) {
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

function validarTelefone($telefone) {
    return preg_match('/^\d{10,11}$/', $telefone);
}

function validarSenha($senha) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{6,}$/', $senha);
}

function getTutorProfile($mysqli, $tutor_id)
{
    $sql = "SELECT nome, bio, foto_perfil FROM tutores WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $tutor_id);
    $stmt->execute();
    $stmt->bind_result($nome, $bio, $foto_perfil);
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
        'foto_perfil' => !empty($foto_perfil) ? '/assets/uploads/fotos-tutores/' . $foto_perfil : '/img/profile-circle-icon.png'
    ];
}

function getCuidadorProfile($mysqli, $cuidador_id)
{
    $sql = "SELECT nome, bio, foto_perfil, experiencia, preco_hora FROM cuidadores WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $cuidador_id);
    $stmt->execute();
    $stmt->bind_result($nome, $bio, $foto_perfil, $experiencia, $preco_hora);
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
        $preco_hora = '';
    };

    if ($experiencia != '' || $experiencia != null) {
        $experiencia;
    } else {
        $experiencia = 'Ainda não há experiências';
    };

    return [
        'nome' => htmlspecialchars($nome),
        'bio' => htmlspecialchars($bio),
        'foto_perfil' => !empty($foto_perfil) ? '/assets/uploads/fotos-cuidadores/' . $foto_perfil : '/img/profile-circle-icon.png',
        'experiencia' => htmlspecialchars($experiencia),
        'preco_hora' => htmlspecialchars($preco_hora)
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
