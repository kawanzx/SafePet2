<?php
include __DIR__ . '/../../auth/protect.php';
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
                <h3>Experiência</h3>
                <p>Especialista em raças de pequeno porte e animais idosos. Tenho experiência com passeios, alimentação e cuidados gerais, além de lidar com pets que possuem necessidades especiais.</p>
            </div>
            <div class="disponibilidade">
                <h3>Disponibilidade</h3>
                <p>Segunda a Sexta: 08:00 - 18:00</p>
                <p>Sábados: 10:00 - 14:00</p>
            </div>
        </div>
    </div>
    <script src="/views/cuidador/main.js" type="module"></script>
</div>