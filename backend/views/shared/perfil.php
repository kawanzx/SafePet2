<?php

include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/db.php';
require_once '../../includes/functions.php';
include __DIR__ . '/../../includes/navbar.php';

$usuario_id = $_GET['id'];
$tipo_usuario = $_GET['tipo'];


if (!$usuario_id || !in_array($tipo_usuario, ['tutor', 'cuidador'])) {
    die('Perfil inválido.');
}

if ($tipo_usuario === 'cuidador') {
    $usuario = getCuidadorProfile($mysqli, $usuario_id);
} elseif ($tipo_usuario === 'tutor') {
    $usuario = getTutorProfile($mysqli, $usuario_id);
    $pets = getPetsByTutor($mysqli, $usuario_id);
}

if (!$usuario) {
    die('Usuário não encontrado.');
}

$query = "SELECT dia_da_semana, hora_inicio, hora_fim FROM disponibilidade_cuidador WHERE cuidador_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$disponibilidade = [];

while ($row = $result->fetch_assoc()) {
    $disponibilidade[$row['dia_da_semana']] = [
        'hora_inicio' => $row['hora_inicio'],
        'hora_fim' => $row['hora_fim']
    ];
}

$disponibilidade_formatada = [];
foreach ($disponibilidade as $dia => $horario) {
    $disponibilidade_formatada[] = [
        'dia' => strtolower($dia),
        'hora_inicio' => $horario['hora_inicio'],
        'hora_fim' => $horario['hora_fim']
    ];
}

$json_disponibilidade = json_encode($disponibilidade_formatada);

$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do <?php echo ucfirst($tipo_usuario); ?> - SafePet</title>
    <link rel="stylesheet" href="/backend/views/tutor/main.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div id="conteudo-1" class="content-section active">
        <h1>Perfil do <?php echo ucfirst($tipo_usuario); ?></h1>
        <div class="meu-perfil">
            <div class="section">
                <span style="position: absolute; margin: -20px; cursor:pointer; font-size:27px" class="material-symbols-outlined" onclick="history.back()">arrow_back</span>
                <div class="perfil-header">
                    <img src="<?php echo $usuario['foto_perfil'] . '?' . time(); ?>" alt="Foto do usuário" class="usuario-avatar" id="preview-avatar">
                    <div>
                        <h2><?php echo $usuario['nome']; ?></h2>
                        <?php if ($tipo_usuario === 'cuidador'): ?>
                            <p class="preco-hora">R$ <?php echo $usuario['preco_hora']; ?>/hora</p>
                            <div class="header">
                                <div class="avaliacoes" id="avaliacoes">
                                    <p id="media">Média: --</p>
                                    <div id="estrelas" class="estrelas"></div>
                                    <p id="total">Total de avaliações: --</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bio">
                    <h3>Sobre mim</h3>
                    <p><span class="bioText"><?php echo $usuario['bio'] ?? 'Sem informações.'; ?></span></p>
                </div>
            </div>
            <div class="infos-cuidador">
                <div class="section">
                    <?php if ($tipo_usuario === 'cuidador'): ?>
                        <div class="experiencia">
                            <h3>Experiência</h3>
                            <p><span class="experienciaText"><?php echo $usuario['experiencia'] ?? 'Sem experiência registrada.'; ?></span></p>
                        </div>
                        <div class="disponibilidade-card">
                            <h3>Disponibilidade</h3>
                            <div class="disponibilidade">
                                <input type="text" id="calendario-disponibilidade" style="display: none;">
                                <div id="disponibilidade-texto" <?= empty($disponibilidade) ? 'style="display:none;"' : '' ?>>
                                    <ul>
                                        <?php foreach ($disponibilidade as $dia => $horario): ?>
                                            <li>
                                                <p>
                                                    <strong><?= ucfirst($dia) ?>:</strong>
                                                    <?= $horario['hora_inicio'] . ' - ' . $horario['hora_fim'] ?>
                                                </p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="comentarios-card">
                            <h3>Comentários</h3>
                            <div id="comentarios" class="comentarios">
                                <!-- Os comentários serão carregados aqui via AJAX -->
                            </div>
                        </div>
                        <script src="/backend/assets/js/perfil/avaliacoes.js"></script>
                    <?php else: ?>
                        <div class="pets-card">
                            <h3>Pets</h3>
                            <div class='pets-container'>
                                <?php if (!empty($pets)): ?>
                                    <?php foreach ($pets as $row): ?>
                                        <div class='pet'>
                                            <div class='pet-header'>
                                                <?php if (!empty($row['foto'])): ?>
                                                    <img src="<?php echo htmlspecialchars('/backend/assets/uploads/fotos-pets/' . $row['foto']); ?>" alt="Foto do pet">
                                                <?php else: ?>
                                                    <img src="/backend/assets/uploads/fotos-pets/default-image.png" alt="Foto padrão para pets">
                                                <?php endif; ?>
                                                <h2 class='nomePet-perfil'><?php echo htmlspecialchars($row['nome']); ?></h2>
                                            </div>
                                            <p>Espécie: <?php echo htmlspecialchars($row['especie']); ?></p>
                                            <p>Raça: <?php echo htmlspecialchars($row['raca']); ?></p>
                                            <p>Idade: <?php echo htmlspecialchars($row['idade']); ?> anos</p>
                                            <p>Sexo: <?php echo htmlspecialchars($row['sexo']); ?></p>
                                            <p>Peso: <?php echo htmlspecialchars($row['peso']); ?> kg</p>
                                            <p>Castrado: <?php echo ($row['castrado'] == 1 ? "Sim" : "Não"); ?></p>
                                            <p>Descrição: <?php echo htmlspecialchars($row['descricao']); ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Nenhum pet cadastrado.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const disponibilidade = <?= $json_disponibilidade; ?>;
            const diasSemana = {
                "domingo": 0,
                "segunda-feira": 1,
                "terça-feira": 2,
                "quarta-feira": 3,
                "quinta-feira": 4,
                "sexta-feira": 5,
                "sábado": 6
            };

            const diasDisponiveis = [...new Set(disponibilidade.map(item => diasSemana[item.dia]))];

            flatpickr("#calendario-disponibilidade", {
                minDate: "today",
                dateFormat: "d-m-Y",
                inline: true,
                locale: "pt",
                enable: [
                    function(date) {
                        return diasDisponiveis.includes(date.getDay());
                    }
                ],
                onChange: function(selectedDates, dateStr, instance) {
                    const diaSemana = new Date(dateStr).toLocaleDateString('pt-BR', {
                        weekday: 'long'
                    }).toLowerCase();

                    const horarios = disponibilidade.filter(item => item.dia_da_semana === diaSemana);

                    if (horarios.length) {
                        let horarioTexto = "Horários disponíveis:\n";
                        horarios.forEach(horario => {
                            horarioTexto += `• ${horario.hora_inicio} - ${horario.hora_fim}\n`;
                        });
                        Swal.fire(horarioTexto);
                    }
                }
            });
        });
    </script>
</body>

</html>