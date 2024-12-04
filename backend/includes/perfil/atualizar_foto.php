<?php
session_start();
include_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se o pet_id e o arquivo de foto foram enviados
    if (isset($_POST['pet_id']) && isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $pet_id = $_POST['pet_id'];
        $foto = $_FILES['foto']['name'];

        $target_dir = "../../assets/uploads/fotos-pets/";
        $target_file = $target_dir . basename($foto);

        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verifica se o arquivo é uma imagem real
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check === false) {
            echo json_encode(['sucesso' => false, 'mensagem' => "O arquivo não é uma imagem."]);
            $uploadOk = 0;
        }

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
            echo json_encode(['sucesso' => false, 'mensagem' => "Apenas arquivos JPG, JPEG e PNG são permitidos."]);
            $uploadOk = 0;
        }

        if ($_FILES["foto"]["size"] > 2 * 1024 * 1024) {
            echo json_encode(['sucesso' => false, 'mensagem' => "O arquivo excede o tamanho permitido de 2MB."]);
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                $sql = "UPDATE pets SET foto = ? WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("si", $foto, $pet_id);

                if ($stmt->execute()) {
                    echo json_encode(['sucesso' => true, 'mensagem' => "Foto atualizada com sucesso."]);
                } else {
                    echo json_encode(['sucesso' => false, 'mensagem' => "Erro ao atualizar a foto."]);
                }

                $stmt->close();
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => "Erro ao mover o arquivo."]);
            }
        }
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => "Dados inválidos."]);
    }
}
