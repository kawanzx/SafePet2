<?php
include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/db.php';
include __DIR__ . '/../../includes/perfil/disponibilidade.php';

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
                    <div class="avaliacoes" id="avaliacoes">
                        <p id="media">Média: --</p>
                        <div id="estrelas" class="estrelas"></div>
                        <p id="total">Total de avaliações: --</p>
                    </div>
                </div>
            </div>
            <div class="bio">
                <form action="/includes/perfil/editar-bio.php" method="POST">
                    <h3>Bio
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
            <div class="experiencia">
                <form action="/includes/perfil/experiencia.php" method="POST">
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
                
                <!-- Formulário para Atualizar Preço -->
                <form method="POST" action="\includes\perfil\salvar-preco.php">
                    <div class="input-preco-unique">
                        <label for="preco_hora">Defina seu Preço por Hora:</label>
                        <input type="number" name="preco_hora" id="preco_hora" value="<?= htmlspecialchars($cuidador['preco_hora']) ?>" min="0" step="0.01" required>
                    </div>
                    <button type="submit" class="btn-salvar-preco">Salvar Preço</button>
                </form>

            </div>

        
            <div class="disponibilidade">
                <h3>Disponibilidade</h3>

                <!-- Texto exibindo a disponibilidade atual -->
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
            <button id="btn-editar" onclick="exibirFormulario()">Editar</button>

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

            <div class="comentarios-card">
                <h3>Comentários</h3>
                <div id="comentarios" class="comentarios">
                    <!-- Os comentários serão carregados aqui via AJAX -->
                </div>
            </div>

        </div>
    </div>

    <script src="/views/cuidador/main.js" type="module"></script>
    <script>
    // Função para ajustar os minutos para 00 ao selecionar o horário
    function ajustarHorario(input) {
        const hora = input.value.split(':')[0]; // Pega a hora
        input.value = `${hora}:00`; // Define os minutos como 00
    }

    // Exibe ou oculta o formulário de edição ao clicar no botão de editar
    function exibirFormulario() {
        var form = document.getElementById("form-disponibilidade");
        var textoDisponibilidade = document.getElementById("disponibilidade-texto"); // Corrigido aqui

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

</div>