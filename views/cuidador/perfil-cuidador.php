<?php

include __DIR__ . '/../../auth/protect.php'; // Protege a página
include __DIR__ . '/../../includes/db.php';

$cuidador_id = $_SESSION['id'];

// Salvar disponibilidade se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $horarios = $_POST['horarios'] ?? [];
    $stmt = $mysqli->prepare("DELETE FROM disponibilidade_cuidador WHERE cuidador_id = ?");
    $stmt->bind_param("i", $cuidador_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("INSERT INTO disponibilidade_cuidador (cuidador_id, dia_da_semana, hora_inicio, hora_fim) VALUES (?, ?, ?, ?)");
    foreach ($horarios as $dia => $horario) {
        if (!empty($horario['inicio']) && !empty($horario['fim'])) {
            $stmt->bind_param("isss", $cuidador_id, $dia, $horario['inicio'], $horario['fim']);
            $stmt->execute();
        }
    }
    $stmt->close();
}

// Buscar disponibilidade do cuidador
$query = "SELECT * FROM disponibilidade_cuidador WHERE cuidador_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $cuidador_id);
$stmt->execute();
$result = $stmt->get_result();

$disponibilidade = [];
while ($row = $result->fetch_assoc()) {
    $disponibilidade[$row['dia_da_semana']] = [
        'hora_inicio' => $row['hora_inicio'],
        'hora_fim' => $row['hora_fim']
    ];
}

$stmt->close();
$mysqli->close();
?>

<div id="conteudo-1" class="content-section active">
    <h1>Perfil do Cuidador</h1>
    <div class="meu-perfil">
        <div class="section">

            <!-- Foto do Cuidador -->
            <div class="perfil-header">
                <form action="upload_foto.php" method="POST" enctype="multipart/form-data">
                    <div class="upload-avatar">
                        <img src="<?php echo $cuidador['foto_perfil'] . '?' . time(); ?>" alt="Foto do cuidador" class="cuidador-avatar" id="preview-avatar" onclick="document.getElementById('input-foto').click()">
                        <input type="file" name="nova_foto" accept="image/*" id="input-foto" style="display: none;" onchange="previewAndUploadFoto()">
                    </div>
                </form>
                <div>
                    <h2><?php echo $cuidador['nome']; ?></h2>
                    <p><span class="info-label">Avaliação:</span> ⭐⭐⭐⭐ (4.8)</p>
                </div>
            </div>

            <!-- Bio -->
            <div class="bio">
                <form action="/includes/perfil/editar-bio.php" method="POST">
                    <h3>Bio
                        <button type="button" class="editar-bio">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                    </h3>
                    <p><span class="bioText"><?php echo $cuidador['bio'];?></span></p>
                    <textarea class="bioInput" name="bio" rows="5" cols="37" style="display: none"><?php echo $cuidador['bio']; ?></textarea>
                    <button type="submit" class="salvar-bio" style="display: none;">Salvar</button>
                </form>
            </div>

            <!-- Experiência -->
            <div class="experiencia">
                <form action="/includes/perfil/experiencia.php" method="POST">
                    <h3>Experiência
                        <button type="button" class="editar-experiencia">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                    </h3>
                    <p><span class="experienciaText"><?php echo $cuidador['experiencia'];?></span></p>
                    <textarea class="experienciaInput" name="experiencia" rows="5" cols="37" style="display: none"><?php echo $cuidador['experiencia']; ?></textarea>
                    <button type="submit" class="salvar-experiencia" style="display: none;">Salvar</button>
                </form>
            </div>

<!-- Disponibilidade -->
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

    <!-- Botão para editar a disponibilidade -->
    <button id="editar-btn" onclick="exibirFormulario()">Editar</button>

    <!-- Formulário de edição, inicialmente oculto -->
    <form id="form-disponibilidade" action="" method="POST" <?= empty($disponibilidade) ? '' : 'style="display:none;"' ?> onsubmit="return validarFormulario()">
        <h4>Definir Disponibilidade</h4>
        <?php 
            // Definindo os dias da semana
            $diasSemana = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];
            foreach ($diasSemana as $dia): 
        ?>
            <div style="margin-bottom: 10px;">
                <!-- Checkbox para marcar o dia -->
                <input type="checkbox" name="horarios[<?= $dia ?>][marcado]" value="1" <?= isset($disponibilidade[$dia]) ? 'checked' : '' ?> onclick="toggleHorarios('<?= $dia ?>')">
                <label><strong><?= ucfirst($dia) ?>:</strong></label>
                <!-- Campos de horário -->
                <input type="time" name="horarios[<?= $dia ?>][inicio]" value="<?= isset($disponibilidade[$dia]) ? $disponibilidade[$dia]['hora_inicio'] : '' ?>" class="horarios-input-<?= $dia ?>" <?= !isset($disponibilidade[$dia]) ? 'disabled' : '' ?> step="3600" onchange="ajustarHorario(this)">
                <input type="time" name="horarios[<?= $dia ?>][fim]" value="<?= isset($disponibilidade[$dia]) ? $disponibilidade[$dia]['hora_fim'] : '' ?>" class="horarios-input-<?= $dia ?>" <?= !isset($disponibilidade[$dia]) ? 'disabled' : '' ?> step="3600" onchange="ajustarHorario(this)">
            </div>
        <?php endforeach; ?>
        <button type="submit">Salvar</button>
    </form>
</div>

<script>
    // Função para ajustar os minutos para 00 ao selecionar o horário
    function ajustarHorario(input) {
        const hora = input.value.split(':')[0]; // Pega a hora
        input.value = `${hora}:00`; // Define os minutos como 00
    }

    // Exibe ou oculta o formulário de edição ao clicar no botão de editar
    function exibirFormulario() {
        var form = document.getElementById("form-disponibilidade");
        var textoDisponibilidade = document.getElementById("disponibilidade-texto");

        // Alternar entre exibir o texto ou o formulário
        if (form.style.display === "none") {
            form.style.display = "block";
            textoDisponibilidade.style.display = "none";
        } else {
            form.style.display = "none";
            textoDisponibilidade.style.display = "block";
        }
    }

    // Habilita ou desabilita os campos de horário com base no checkbox
    function toggleHorarios(dia) {
        var checkbox = document.querySelector(`input[name="horarios[${dia}][marcado]"]`);
        var inputs = document.querySelectorAll(`.horarios-input-${dia}`);

        if (checkbox.checked) {
            // Se o checkbox estiver marcado, habilitar os campos de horário
            inputs.forEach(input => {
                input.disabled = false;
            });
        } else {
            // Se o checkbox não estiver marcado, desmarcar os campos de horário e desabilitá-los
            inputs.forEach(input => {
                input.disabled = true;
                input.value = ''; // Limpar o valor do horário
            });
        }
    }

    // Função para validar o formulário antes de enviar
    function validarFormulario() {
        var dias = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];
        var formValido = true;

        // Verificar se os campos de horário foram preenchidos quando o dia for marcado
        dias.forEach(function(dia) {
            var checkbox = document.querySelector(`input[name="horarios[${dia}][marcado]"]`);
            var horaInicio = document.querySelector(`input[name="horarios[${dia}][inicio]"]`);
            var horaFim = document.querySelector(`input[name="horarios[${dia}][fim]"]`);

            if (checkbox.checked) {
                if (!horaInicio.value || !horaFim.value) {
                    alert(`Por favor, preencha os horários para o dia ${dia}.`);
                    formValido = false;
                }

                // Verificar se a hora de início é maior que a de fim
                if (horaInicio.value && horaFim.value && horaInicio.value >= horaFim.value) {
                    alert(`O horário de início para o dia ${dia} não pode ser maior ou igual ao horário de fim.`);
                    formValido = false;
                }
            }
        });

        return formValido;
    }
</script>
