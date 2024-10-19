<?php
session_start();

include_once '../../login/conexaobd.php';

if (!$mysqli) {
    die("Erro na conexão com o banco de dados.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $pet_id = $_POST['petId'];
    $nome = $_POST['nome-pet'];
    $especie = $_POST['especie'];
    $raca = $_POST['raca'];
    $idade = $_POST['idade'];
    $sexo = $_POST['sexo'];
    $peso = $_POST['peso'];
    $castrado = $_POST['castrado'];
    $descricao = $_POST['descricao'];
    $tutor_id = $_SESSION['id']; 

    $foto = '';

    // Verifica se o arquivo de imagem foi enviado
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($foto);

        // Validação do arquivo de imagem
        $uploadOk = 1;

        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check === false) {
            echo "O arquivo não é uma imagem.";
            $uploadOk = 0;
        }

        // Verificar o tamanho do arquivo (exemplo: 2MB máximo)
        if ($_FILES["foto"]["size"] > 2 * 1024 * 1024) {
            echo "O arquivo excede o tamanho permitido de 2MB.";
            $uploadOk = 0;
        }

        // Limitar o tipo de arquivo permitido (somente JPEG e PNG)
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Apenas arquivos JPG, JPEG e PNG são permitidos.";
            $uploadOk = 0;
        }

        // Verificar se ocorreu algum erro na validação da imagem
        if ($uploadOk == 1) {
            // Mover o arquivo para o diretório de uploads
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                $foto = $target_file; // Caminho da foto para ser salvo no banco de dados
            } else {
                echo json_encode(['status' => 'error', 'message' => "Erro ao enviar a imagem. Por favor, tente novamente."]);
                exit;
            }
        }
    }

    // Conectar ao banco e inserir o pet
    $sql = "INSERT INTO pets (nome, especie, raca, idade, sexo, peso, castrado, descricao, tutor_id, foto) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssisissss", $nome, $especie, $raca, $idade, $sexo, $peso, $castrado, $descricao, $tutor_id, $foto);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => "Pet cadastrado com sucesso!"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => "Erro ao cadastrar o pet: " . $stmt->error]);
    }

    $stmt->close();
    $mysqli->close();
}
