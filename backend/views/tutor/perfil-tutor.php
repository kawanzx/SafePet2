<?php
include __DIR__ . '/../../auth/protect.php';
?>
<div id="conteudo-1" class="content-section active">
    <h1>Perfil do Tutor</h1>
    <div class="meu-perfil">
        <div class="section">
            <div class="perfil-header">
                <form action="upload_foto.php" method="POST" enctype="multipart/form-data">
                    <div class="upload-avatar">
                        <img src="<?php echo $tutor['foto_perfil'] . '?' . time(); ?>" alt="Foto do tutor" class="tutor-avatar" id="preview-avatar" onclick="document.getElementById('input-foto').click()">
                        <input type="file" name="nova_foto" accept="image/*" id="input-foto" style="display: none;" onchange="previewAndUploadFoto()">
                    </div>
                </form>
                <div>
                    <h2><?php echo $tutor['nome']; ?></h2>
                </div>
            </div>
            <div class="bio">
                <form action="/backend/includes/perfil/editar-bio.php" method="POST">
                    <h3>Sobre mim
                        <button type="button" class="editar-bio">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                    </h3>
                    <p><span class="bioText"><?php echo $tutor['bio']; ?></span></p>
                    <textarea class="bioInput" name="bio" rows="5" cols="37" style="display: none"><?php echo $tutor['bio']; ?></textarea>
                    <button type="submit" class="salvar-bio" style="display: none;">Salvar</button>
                </form>
            </div>
        </div>

        <div class="pets-card">
            <div class="section">
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
        </div>
    </div>
    <script src="/backend/views/tutor/main.js" type="module"></script>
</div>