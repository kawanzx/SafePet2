<?php
session_start();
include '../../login/conexaobd.php'; 

if (isset($_FILES['nova_foto']) && $_FILES['nova_foto']['error'] === UPLOAD_ERR_OK) {
    $nomeArquivo = $_FILES['nova_foto']['name'];
    $extensao = pathinfo($nomeArquivo, PATHINFO_EXTENSION);

    // Verificar se o arquivo é uma imagem
    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($extensao, $extensoesPermitidas)) {
        // Definir o novo nome do arquivo e diretório
        $novoNome = uniqid() . '.' . $extensao;
        $diretorioUpload = 'uploads/fotos_cuidadores/';
        $caminhoArquivo = $diretorioUpload . $novoNome;

        // Mover o arquivo para o diretório de upload
        if (move_uploaded_file($_FILES['nova_foto']['tmp_name'], $caminhoArquivo)) {
            // Atualizar o caminho da foto no banco de dados
            $id_cuidador = $_SESSION['id'];
            $query = "UPDATE cuidadores SET foto_perfil = ? WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('si', $novoNome, $id_cuidador);

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