<?php
include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/db.php'; 
require_once '../../includes/functions.php';
include __DIR__ . '/../../includes/navbar.php';

$cuidador_id = $_GET['id'];
$cuidador = getCuidadorProfile($mysqli, $cuidador_id);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do cuidador</title>
    <link rel="stylesheet" href="/views/tutor/main.css">
</head>
<body>
<div id="conteudo-1" class="content-section active">
    <h1>Perfil do Cuidador</h1>
    <div class="meu-perfil">
        <div class="section">
            <span style="position: absolute; margin: -20px; cursor:pointer; font-size:27px" class="material-symbols-outlined" onclick="history.back()">arrow_back</span>
            <div class="perfil-header">
                <img src="<?php echo $cuidador['foto_perfil'] . '?' . time(); ?>" alt="Foto do cuidador" class="cuidador-avatar" id="preview-avatar">
                <div>
                    <h2><?php echo $cuidador['nome']; ?></h2>
                    <p><span class="info-label">Avaliação:</span> ⭐⭐⭐⭐ (4.8)</p>
                </div>
                <a href="agendar.php?id=<?php echo $cuidador_id; ?>" class="schedule-button">Agendar</a>
            </div>
            <div class="bio">
                <h3>Bio</h3>
                <p><span class="bioText"><?php echo $cuidador['bio'];?></span></p>
            </div>
            <div class="experiencia">
                <h3>Experiência</h3>
                <p><span class="experienciaText"><?php echo $cuidador['experiencia'];?></span></p>
            </div>
            <div class="disponibilidade">
                <h3>Disponibilidade</h3>
                <?php
                $sql = "SELECT dia_da_semana, hora_inicio, hora_fim FROM disponibilidade_cuidador WHERE cuidador_id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("i", $cuidador_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<p>{$row['dia_da_semana']}: {$row['hora_inicio']} - {$row['hora_fim']}</p>";
                    }
                } else {
                    echo "<p>Nenhuma disponibilidade cadastrada.</p>";
                }

                $stmt->close();
                ?>
            </div>

        </div>
    </div>
</div>
</body>
</html>
