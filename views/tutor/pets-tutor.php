<?php
include __DIR__ . '/../../auth/protect.php';
?>
<div id="conteudo-2" class="content-section">
    <div class="meus-pets">
        <div class="section">
            <h3>Meus Pets</h3>
            <?php
            $result = getPets($mysqli, $tutor_id);
            ?>

            <div class="pets-container">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <article class='pet' data-pet-id="<?php echo htmlspecialchars($row['id']); ?>">
                            <header class='pet-header'>
                                <span class="fotoPet">
                                    <?php if (!empty($row['foto'])) { ?>
                                        <img id="preview-<?php echo htmlspecialchars($row['id']); ?>" src="<?php echo htmlspecialchars('/assets/uploads/fotos-pets/' . $row['foto']); ?>" alt='Foto do pet' class="fotoPetImg">
                                    <?php } else { ?>
                                        <img id="preview-<?php echo htmlspecialchars($row['id']); ?>" src="/assets/uploads/fotos-pets/default-image.png" alt='Foto padrão para pets' class="fotoPetImg">
                                    <?php } ?>
                                </span>
                                <input type="file" id="foto-<?php echo htmlspecialchars($row['id']); ?>" name="foto" class="fotoPetInput" style="display:none;" accept="image/*">
                                <input type="hidden" class="fotoPetAtual" value="<?php echo htmlspecialchars($row['foto']); ?>">
                                <h2 class="nomePet-perfil">
                                    <span class="nomePetText"><?php echo htmlspecialchars($row['nome']); ?></span>
                                    <input type="text" class="nomePetInput" value="<?php echo htmlspecialchars($row['nome']); ?>" style="display: none;">
                                </h2>
                                    <button class="editar-btn">Editar</button>
                                    <button class="excluir-btn">Excluir</button>
                                    <button class="salvar-btn" style="display: none;">Salvar</button>
                                    <button class="cancelar-btn" style="display: none;">Cancelar</button>
                            </header>
                            <hr>
                            <div class="pet-info">
                                <p>Espécie:
                                    <span class="especieText"><?php echo htmlspecialchars($row['especie']); ?></span>
                                    <select class="especieInput" style="display: none;">
                                        <option value="cachorro" <?php if ($row['especie'] == 'cachorro') echo 'selected'; ?>>Cachorro</option>
                                        <option value="gato" <?php if ($row['especie'] == 'gato') echo 'selected'; ?>>Gato</option>
                                    </select>
                                </p>
                                <p>Raça:
                                    <span class="racaText"><?php echo htmlspecialchars($row['raca']); ?></span>
                                    <input type="text" class="racaInput" value="<?php echo htmlspecialchars($row['raca']); ?>" style="display: none;">
                                </p>
                                <p>Idade:
                                    <span class="idadeText"><?php echo htmlspecialchars($row['idade']); ?></span>
                                    <input type="text" class="idadeInput" value="<?php echo htmlspecialchars($row['idade']); ?>" style="display: none;">
                                </p>
                                <p>Sexo:
                                    <span class="sexoText"><?php echo htmlspecialchars($row['sexo']); ?></span>
                                    <select class="sexoInput" style="display: none;">
                                        <option value="M" <?php if ($row['sexo'] == 'M') echo 'selected'; ?>>Macho</option>
                                        <option value="F" <?php if ($row['sexo'] == 'F') echo 'selected'; ?>>Fêmea</option>
                                    </select>
                                </p>
                                <p>Peso:
                                    <span class="pesoText"><?php echo htmlspecialchars($row['peso']); ?></span>
                                    <input type="text" class="pesoInput" value="<?php echo htmlspecialchars($row['peso']); ?>" style="display: none;">
                                </p>
                                <p>Castrado:
                                    <span class="castradoText"><?php echo htmlspecialchars($row['castrado']); ?></span>
                                    <select class="castradoInput" style="display: none;">
                                        <option value="Sim" <?php if ($row['castrado'] == 'Sim') echo 'selected'; ?>>Sim</option>
                                        <option value="Não" <?php if ($row['castrado'] == 'Não') echo 'selected'; ?>>Não</option>
                                    </select>
                                </p>
                                <p>Descrição:
                                    <span class="descricaoText"><?php echo htmlspecialchars($row['descricao']); ?></span>
                                    <input type="text" class="descricaoInput" value="<?php echo htmlspecialchars($row['descricao']); ?>" style="display: none;">
                                </p>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Você ainda não cadastrou nenhum pet.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="cad-pet-card">
            <div class="section">
                <h3>Cadastrar Pet</h3>
                <div class="cadastrar-pet">
                    <form id="form-pet" action="/includes/perfil/cadastro-pet.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="petId" name="petId">
                        <div class="coluna">
                            <label for="nome-pet">Nome do Pet:</label>
                            <input type="text" id="nome" name="nome-pet" required>
                            <label for="raca">Raça:</label>
                            <input type="text" id="raca" name="raca" required>
                        </div>
                        <div class="coluna">
                            <label for="especie">Espécie:</label>
                            <select id="especie" name="especie" required>
                                <option value="cachorro">Cachorro</option>
                                <option value="gato">Gato</option>
                            </select>
                            <label for="idade">Idade (em anos):</label>
                            <input type="number" id="idade" name="idade" min="0" required>
                        </div>
                        <div class="coluna">
                            <label for="sexo">Sexo:</label>
                            <select id="sexo" name="sexo" required>
                                <option value="M">Macho</option>
                                <option value="F">Fêmea</option>
                            </select>
                            <label for="peso">Peso (kg):</label>
                            <input type="number" id="peso" name="peso" step="0.01" min="0" required>
                            <label for="castrado">O pet é castrado?</label>
                            <select id="castrado" name="castrado" required>
                                <option value="Sim">Sim</option>
                                <option value="Não">Não</option>
                            </select>
                        </div>
                        <label for="descricao">Descrição (comportamento, necessidades especiais):</label>
                        <textarea id="descricao" name="descricao" rows="4"></textarea>
                        <label for="foto">Foto do Pet:</label>
                        <input type="file" id="foto" name="foto" accept="image/*">
                        <div id="preview-container">
                            <img id="preview" src="" alt="Pré-visualização da foto do pet" style="display:none; width: 200px; height: auto;">
                        </div>
                        <input type="submit" value="Cadastrar Pet" id="cad-pet">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/views/tutor/main.js" type="module"></script>