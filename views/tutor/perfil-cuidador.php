<?php
include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/db.php';
require_once '../../includes/functions.php';
include __DIR__ . '/../../includes/navbar.php';

$cuidador_id = intval($_GET['id']);
$cuidador = getCuidadorProfile($mysqli, $cuidador_id);

// Buscar a disponibilidade do cuidador
$query = "SELECT dia_da_semana, hora_inicio, hora_fim FROM disponibilidade_cuidador WHERE cuidador_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $cuidador_id);
$stmt->execute();
$result = $stmt->get_result();

// Inicializa o array de disponibilidade
$disponibilidade = [];

// Preenche o array de disponibilidade com os dados da consulta
while ($row = $result->fetch_assoc()) {
    $disponibilidade[$row['dia_da_semana']] = [
        'hora_inicio' => $row['hora_inicio'],
        'hora_fim' => $row['hora_fim']
    ];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do cuidador</title>
    <link rel="stylesheet" href="/views/tutor/main.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div id="conteudo-1" class="content-section active">
        <h1>Perfil do Cuidador</h1>
        <div class="meu-perfil">
            <div class="section">
                <span style="position: absolute; margin: -16px; cursor:pointer; font-size:1.5rem" class="material-symbols-outlined" onclick="history.back()">arrow_back</span>
                <div class="perfil-header">
                    <img src="<?php echo $cuidador['foto_perfil'] . '?' . time(); ?>" alt="Foto do cuidador" class="cuidador-avatar" id="preview-avatar">
                    <div class="header">
                        <h2><?php echo $cuidador['nome']; ?></h2>
                        <div class="avaliacoes" id="avaliacoes">
                            <p id="media">Média: --</p>
                            <div id="estrelas" class="estrelas"></div>            
                            <p id="total">Total de avaliações: --</p>
                        </div>
                    </div>
                    <a href="agendar.php?id=<?php echo $cuidador_id; ?>" class="btn-agendar">Agendar</a>
                </div>
                <div class="bio">
                    <h3>Bio</h3>
                    <p><span class="bioText"><?php echo (htmlspecialchars($cuidador['bio'])); ?></span></p>
                </div>
                <div class="experiencia">
                    <h3>Experiência</h3>
                    <p><span class="experienciaText"><?php echo (htmlspecialchars($cuidador['experiencia'])); ?></span></p>
                </div>

                <div class="preco">
                    <h3>Preço por Hora</h3>
                    <div class="preco-valor">
                        <?php
                        // Exibir o preço, se existir, formatado
                        if (isset($cuidador['preco_hora']) && $cuidador['preco_hora'] > 0) {
                            echo 'R$ ' . number_format($cuidador['preco_hora'], 2, ',', '.') . '/hora';
                        } else {
                            echo 'Preço não informado';
                        }
                        ?>
                    </div>
                </div>
            
                
                <div class="disponibilidade">
                    <h3>Disponibilidade</h3>
                    <div id="disponibilidade-texto" <?= empty($disponibilidade) ? 'style="display:none;"' : '' ?>>
                        <ul>
                            <?php foreach ($disponibilidade as $dia => $horario): ?>
                                <li>
                                    <strong><?= ucfirst($dia) ?>:</strong>
                                    <?= $horario['hora_inicio'] . ' - ' . $horario['hora_fim'] ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="comentarios-card">
                    <h3>Comentários</h3>
                    <div id="comentarios" class="comentarios">
                        <!-- Os comentários serão carregados aqui via AJAX -->
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="/assets/js/perfil/avaliacoes.js"></script>
</body>
</html>
