<?php
session_start();
include '../db.php'; 

$tipo_usuario = $_SESSION['tipo_usuario'];
$tabela = ($tipo_usuario === 'tutor') ? 'tutores' : 'cuidadores';
$redirectUrl = ($tipo_usuario === 'tutor') ? '/assets/uploads/fotos-tutores/' : '/assets/uploads/fotos-cuidadores/';

if (isset($_FILES['nova_foto']) && $_FILES['nova_foto']['error'] === UPLOAD_ERR_OK) {
    $nomeArquivo = $_FILES['nova_foto']['name'];
    $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);

    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($extensao, $extensoesPermitidas)) {
        $novoNome = uniqid() . '.' . $extensao;
        $diretorioUpload = $_SERVER['DOCUMENT_ROOT'] . $redirectUrl;
        $caminhoArquivo = $diretorioUpload . $novoNome;

        if (move_uploaded_file($_FILES['nova_foto']['tmp_name'], $caminhoArquivo)) {
            $user_id = $_SESSION['id'];
            $query = "UPDATE $tabela SET foto_perfil = ? WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('si', $novoNome, $user_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'nova_foto' => $novoNome]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao atualizar a foto no banco de dados.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Erro ao mover o arquivo.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Formato de arquivo inválido.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Erro ao mover o arquivo: ' . $_FILES['nova_foto']['error']]);
}

