<?php
include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/db.php'; //
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
                    <p><span class="info-label">Avaliação:</span> ⭐⭐⭐⭐ (4.8)</p>
                </div>
            </div>
            <div class="bio">
                <form action="/includes/perfil/editar-bio.php" method="POST">
                    <h3>Bio
                        <button type="button" class="editar-bio">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                    </h3>
                    <p><span class="bioText"><?php echo $cuidador['bio'];?></span>
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
                    <p><span class="experienciaText"><?php echo $cuidador['experiencia'];?></span>
                    </p>
                    <textarea class="experienciaInput" name="experiencia" rows="5" cols="37" style="display: none"><?php echo $cuidador['experiencia']; ?></textarea>
                    <button type="submit" class="salvar-experiencia" style="display: none;">Salvar</button>
                </form>
            </div>
            <div class="disponibilidade">
                <h3>Disponibilidade</h3>
                <?php
                // Exibir a disponibilidade cadastrada
                $sql = "SELECT dia_da_semana, hora_inicio, hora_fim FROM disponibilidade_cuidador WHERE cuidador_id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("i", $cuidador['id']);
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
                <form action="/includes/perfil/salvar-disponibilidade.php" method="POST">
                    <label for="dia_da_semana">Dia da Semana:</label>
                    <select name="dia_da_semana" required>
                        <option value="segunda">Segunda</option>
                        <option value="terca">Terça</option>
                        <option value="quarta">Quarta</option>
                        <option value="quinta">Quinta</option>
                        <option value="sexta">Sexta</option>
                        <option value="sabado">Sábado</option>
                        <option value="domingo">Domingo</option>
                    </select>

                    <label for="hora_inicio">Horário de Início:</label>
                    <input type="time" name="hora_inicio" required>

                    <label for="hora_fim">Horário de Fim:</label>
                    <input type="time" name="hora_fim" required>

                    <button type="submit">Adicionar Disponibilidade</button>
                </form>
            </div>

        </div>
    </div>
    <script src="/views/cuidador/main.js" type="module"></script>
</div>