<?php
session_start();
include_once '../../login/conexaobd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se o pet_id e o arquivo de foto foram enviados
    if (isset($_POST['pet_id']) && isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $pet_id = $_POST['pet_id'];
        $foto = $_FILES['foto']['name'];

        // Diretório onde a imagem será armazenada
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($foto);

        // Validação do arquivo de imagem
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verifica se o arquivo é uma imagem real
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check === false) {
            echo "O arquivo não é uma imagem.";
            $uploadOk = 0;
        }

        // Limita os tipos de arquivo permitidos (somente JPEG e PNG)
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Apenas arquivos JPG, JPEG e PNG são permitidos.";
            $uploadOk = 0;
        }

        // Limita o tamanho do arquivo (máximo 2MB)
        if ($_FILES["foto"]["size"] > 2 * 1024 * 1024) {
            echo "O arquivo excede o tamanho permitido de 2MB.";
            $uploadOk = 0;
        }

        // Se a validação da imagem estiver OK, mova para o diretório de uploads
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                // Atualiza o caminho da foto no banco de dados
                $sql = "UPDATE pets SET foto = ? WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("si", $target_file, $pet_id);

                if ($stmt->execute()) {
                    echo "Foto atualizada com sucesso.";
                } else {
                    echo "Erro ao atualizar a foto no banco de dados.";
                }

                $stmt->close();
            } else {
                echo "Erro ao mover o arquivo.";
            }
        }
    } else {
        echo "Dados inválidos.";
    }
}