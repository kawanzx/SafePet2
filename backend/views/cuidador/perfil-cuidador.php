<?php
include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/db.php';
include __DIR__ . '/../../includes/perfil/disponibilidade.php';

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
$mysqli->close();
?>
<div id="conteudo-1" class="content-section active">
    <h1>Perfil do Cuidador</h1>
    <div class="meu-perfil">
        <div class="section">
            <div class="perfil-header">
                <form action="upload_foto.php" method="POST" enctype="multipart/form-data">
                    <div class="upload-avatar">
                        <img src="<?php echo $cuidador['foto_perfil'] . '?' . time(); ?>" alt="Foto do cuidador" class="cuidador-avatar" id="preview-avatar" onclick="document.getElementById('input-foto').click()">
                        <input type="file" name="nova_foto" accept="image/*" id="input-foto" style="display: none;" onchange="previewAndUploadFoto()">
                    </div>
                </form>
                <div>
                    <h2><?php echo $cuidador['nome']; ?></h2>
                    <?php
                        // Exibir o preço, se existir, formatado
                        if (isset($cuidador['preco_hora']) && floatval($cuidador['preco_hora']) > 0) {
                            echo '<p class="preco-hora">R$ ' . number_format(floatval($cuidador['preco_hora']), 2, ',', '.') . '/hora</p>';
                        } else {
                            echo '<p class="preco-hora">Preço não informado</p>';
                        }
                        ?>
                    <div class="avaliacoes" id="avaliacoes">
                        <p id="media">Média: --</p>
                        <div id="estrelas" class="estrelas"></div>
                        <p id="total">Total de avaliações: --</p>
                    </div>
                </div>
            </div>
            <div class="bio">
                <form action="/backend/includes/perfil/editar-bio.php" method="POST">
                    <h3>Sobre mim
                        <button type="button" class="editar-bio">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                    </h3>
                    <p><span class="bioText"><?php echo $cuidador['bio']; ?></span>
                    </p>
                    <textarea class="bioInput" name="bio" rows="5" cols="37" style="display: none"><?php echo $cuidador['bio']; ?></textarea>
                    <button type="submit" class="salvar-bio" style="display: none;">Salvar</button>
                </form>
            </div>
        </div>
        <div class="infos-cuidador">
            <div class="section">
                <div class="experiencia">
                    <form action="/backend/includes/perfil/experiencia.php" method="POST">
                        <h3>Experiência
                            <button type="button" class="editar-experiencia">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                        </h3>
                        <p><span class="experienciaText"><?php echo $cuidador['experiencia']; ?></span>
                        </p>
                        <textarea class="experienciaInput" name="experiencia" rows="5" cols="37" style="display: none"><?php echo $cuidador['experiencia']; ?></textarea>
                        <button type="submit" class="salvar-experiencia" style="display: none;">Salvar</button>
                    </form>
                </div>
                <div class="preco">
                    <!-- Exibição do Preço -->
                    <h3>Preço por Hora</h3>
                    <!-- Formulário para Atualizar Preço -->
                    <form method="POST" action="\backend\includes\perfil\salvar-preco.php">
                        <div class="input-preco-unique">
                            <label for="preco_hora">Defina seu Preço por Hora:</label>
                            <input type="number" name="preco_hora" id="preco_hora" value="<?= htmlspecialchars($cuidador['preco_hora']) ?>" min="0" step="0.01" required>
                        </div>
                        <button type="submit" class="btn-salvar-preco">Salvar Preço</button>
                    </form>
                </div>
                <div class="disponibilidade-card">
                    <h3>
                        Disponibilidade
                        <button id="btn-editar" class="btn-editar" onclick="exibirFormulario()">Editar</button>
                    </h3>
                    <!-- Texto exibindo a disponibilidade atual -->
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
                        <!-- Formulário de edição, inicialmente oculto -->
                        <form id="form-disponibilidade" action="" method="POST" <?= empty($disponibilidade) ? '' : 'style="display:none;"' ?> onsubmit="return validarFormulario()">
                            <h4>Definir Disponibilidade</h4>
                            <?php
                            // Definindo os dias da semana
                            $diasSemana = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];
                            foreach ($diasSemana as $dia):
                            ?>
                                <div class="input-dia" style="margin-bottom: 10px;">
                                    <!-- Checkbox para marcar o dia -->
                                    <input type="checkbox" name="horarios[<?= $dia ?>][marcado]" value="1" <?= isset($disponibilidade[$dia]) ? 'checked' : '' ?> onclick="toggleHorarios('<?= $dia ?>')" class="checkbox-dia">
                                    <label><strong><?= ucfirst($dia) ?>:</strong></label>
                                    <!-- Campos de horário -->
                                    <input type="time" name="horarios[<?= $dia ?>][inicio]" value="<?= isset($disponibilidade[$dia]) ? $disponibilidade[$dia]['hora_inicio'] : '' ?>" class="hora-inicio" <?= !isset($disponibilidade[$dia]) ? 'disabled' : '' ?> step="3600" onchange="ajustarHorario(this)">
                                    <input type="time" name="horarios[<?= $dia ?>][fim]" value="<?= isset($disponibilidade[$dia]) ? $disponibilidade[$dia]['hora_fim'] : '' ?>" class="hora-fim" <?= !isset($disponibilidade[$dia]) ? 'disabled' : '' ?> step="3600" onchange="ajustarHorario(this)">
                                </div>
                            <?php endforeach; ?>
                            <button type="submit" class="btn-salvar">Salvar</button>
                        </form>
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


    <script src="/backend/views/cuidador/main.js" type="module"></script>
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

        // Função para ajustar os minutos para 00 ao selecionar o horário
        function ajustarHorario(input) {
            const hora = input.value.split(':')[0]; // Pega a hora
            input.value = `${hora}:00`; // Define os minutos como 00
        }

        // Exibe ou oculta o formulário de edição ao clicar no botão de editar
        function exibirFormulario() {
            var form = document.getElementById("form-disponibilidade");
            var textoDisponibilidade = document.getElementById("disponibilidade-texto"); // Corrigido aqui
            var calendario = document.querySelector(".flatpickr-calendar.inline");

            // Alternar entre exibir o texto ou o formulário
            if (form.style.display === "none") {
                form.style.display = "block";
                textoDisponibilidade.style.display = "none";
                calendario.style.display = "none";
            } else {
                form.style.display = "none";
                textoDisponibilidade.style.display = "block";
                calendario.style.display = "block";
            }
        }

        // Habilita ou desabilita os campos de horário com base no checkbox
        function toggleHorarios(dia) {
            var checkbox = document.querySelector(`input[name="horarios[${dia}][marcado]"]`);
            var horaInicio = document.querySelector(`input[name="horarios[${dia}][inicio]"]`); // Corrigido
            var horaFim = document.querySelector(`input[name="horarios[${dia}][fim]"]`); // Corrigido

            if (checkbox.checked) {
                // Se o checkbox estiver marcado, habilitar os campos de horário
                horaInicio.disabled = false;
                horaFim.disabled = false;
            } else {
                // Se o checkbox não estiver marcado, desmarcar os campos de horário e desabilitá-los
                horaInicio.disabled = true;
                horaFim.disabled = true;
                horaInicio.value = ''; // Limpar o valor do horário
                horaFim.value = ''; // Limpar o valor do horário
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
                        Swal.fire({
                            icon: "error",
                            title: "Erro!",
                            text: "Por favor, preencha os horários para " + dia + ".",
                        });
                        formValido = false;
                    }

                    // Verificar se a hora de início é maior que a de fim
                    if (horaInicio.value && horaFim.value && horaInicio.value >= horaFim.value) {
                        Swal.fire({
                            icon: "error",
                            title: "Erro!",
                            text: "O horário de início não pode ser maior ou igual ao horário de fim.",
                        });
                        formValido = false;
                    }
                }
            });

            return formValido;
        }
    </script>

</div>