<?php
include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/db.php';
require_once '../../includes/functions.php';
include __DIR__ . '/../../includes/navbar.php';

$tutor_id = $_SESSION['id'];
$cuidador_id = intval($_GET['id']);
$cuidador = getCuidadorProfile($mysqli, $cuidador_id);
$tutor = getTutorProfile($mysqli, $tutor_id);

// Buscar a disponibilidade do cuidador
$query = "SELECT dia_da_semana, hora_inicio, hora_fim FROM disponibilidade_cuidador WHERE cuidador_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $cuidador_id);
$stmt->execute();
$result = $stmt->get_result();

//Inicializa o array de disponibilidade
$disponibilidade = [];

// Preenche o array de disponibilidade com os dados da consulta
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
    <title>Perfil do cuidador</title>
    <link rel="stylesheet" href="/backend/views/tutor/main.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
                        <p class="preco-hora">R$ <?php echo $cuidador['preco_hora'];?>/hora</p>
                        <div class="avaliacoes" id="avaliacoes">
                            <p id="media">Média: --</p>
                            <div id="estrelas" class="estrelas"></div>
                            <p id="total">Total de avaliações: --</p>
                        </div>
                    </div>
                    <?php if ((int)$tutor['ativo'] === 1 && !empty($tutor['cep']) && (int)$tutor['telefone_validado'] === 1) { ?>
                        <a href="agendar.php?id=<?php echo $cuidador_id; ?>" class="btn-agendar">Agendar</a>
                    <?php } elseif ((int)$tutor['telefone_validado'] === 0) { ?>
                        <a class="btn-agendar" onclick="redirecionarContaTelefone()">Agendar</a>
                    <?php } else { ?>
                        <a class="btn-agendar" onclick="redirecionarConta()">Agendar</a>
                    <?php } ?>
                </div>
                <div class="bio">
                    <h3>Sobre mim</h3>
                    <p><span class="bioText"><?php echo (htmlspecialchars($cuidador['bio'])); ?></span></p>
                </div>
            </div>
            <div class="infos-cuidador">
                <div class="section">
                    <div class="experiencia">
                        <h3>Experiência</h3>
                        <p><span class="experienciaText"><?php echo (htmlspecialchars($cuidador['experiencia'])); ?></span></p>
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
                </div>
            </div>

        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Disponibilidade em JSON do PHP
            const disponibilidade = <?= $json_disponibilidade; ?>;

            console.log(disponibilidade);

            const diasSemana = {
                "domingo": 0,
                "segunda-feira": 1,
                "terça-feira": 2,
                "quarta-feira": 3,
                "quinta-feira": 4,
                "sexta-feira": 5,
                "sábado": 6
            };

            // Mapeia os dados de disponibilidade para as configurações do Flatpickr
            const diasDisponiveis = [...new Set(disponibilidade.map(item => diasSemana[item.dia]))];

            console.log(diasDisponiveis);

            // Inicializa o Flatpickr
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
                    // Exibe os horários disponíveis para o dia selecionado
                    const diaSemana = new Date(dateStr).toLocaleDateString('pt-BR', {
                        weekday: 'long'
                    }).toLowerCase();

                    const horarios = disponibilidade.filter(item => item.dia_da_semana === diaSemana);

                    if (horarios.length) {
                        let horarioTexto = "Horários disponíveis:\n";
                        horarios.forEach(horario => {
                            horarioTexto += `• ${horario.hora_inicio} - ${horario.hora_fim}\n`;
                        });

                        alert(horarioTexto); // Mostra uma mensagem com os horários (pode ser substituído por algo mais elegante)
                    }
                }
            });
        });
    </script>
    <script src="/backend/assets/js/perfil/avaliacoes.js"></script>

</body>

</html>