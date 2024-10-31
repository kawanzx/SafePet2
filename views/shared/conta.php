<div id="conteudo-3" class="content-section">
    <?php
    include __DIR__ . '/../../auth/protect.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //$informacoesTutor = getinf$informacoesTutor($mysqli, $tutor_id);
    $tipo_usuario = $_SESSION['tipo_usuario'] ?? 'tutor';
    $usuario_id = $_SESSION['id'];
    $informacoesUsuario = getInformacoesUsuario($mysqli, $usuario_id, $tipo_usuario);
    ?>

    <div id="info-pessoais" class="sub-conteudo">
        <div class="section" usuario-id="<?php echo $usuario_id; ?>">
            <div class="principais-informacoes">
                <h3>Informações Pessoais <button class="btn-editar"><span class="material-symbols-outlined">edit</span></button></h3>
                <div class="textfield">
                    <span id="nomeErro" class="erro"></span>
                    <label for="nome-tutor">Nome Completo:</label>
                    <span class="nome-tutorText"><?php echo htmlspecialchars($informacoesUsuario['nome']); ?></span>
                    <input type="text" class="nome-tutorInput" name="nome-tutor" value="<?php echo htmlspecialchars($informacoesUsuario['nome']); ?>" style="display: none" required>
                </div>
                <div class="textfield">
                    <span id="telefoneErro" class="erro"></span>
                    <label for="telefone">Telefone:</label>
                    <span class="telefoneText"><?php echo htmlspecialchars($informacoesUsuario['telefone']); ?></span>
                    <input type="tel" class="telefoneInput" name="telefone" value="<?php echo htmlspecialchars($informacoesUsuario['telefone']); ?>" style="display: none" pattern="[0-9]{10,11}" required>
                </div>
                <div class="textfield">
                    <span id="emailErro" class="erro"></span>
                    <label for="email">E-mail:</label>
                    <span class="emailText"><?php echo htmlspecialchars($informacoesUsuario['email']); ?></span>
                    <input type="email" class="emailInput" name="email" value="<?php echo htmlspecialchars($informacoesUsuario['email']); ?>" style="display: none" required>
                </div>
                <div class="textfield">
                    <span id="dtNascimentoErro" class="erro"></span>
                    <label for="dt_nascimento">Data de Nascimento:</label>
                    <span class="dt_nascimentoText"><?php echo htmlspecialchars($informacoesUsuario['dt_nascimento']); ?></span>
                    <input type="date" class="dt_nascimentoInput" name="dt_nascimento" value="<?php echo htmlspecialchars($informacoesUsuario['dt_nascimento']); ?>" style="display: none" required>
                </div>
                <div class="textfield">
                    <span id="cpfErro" class="erro"></span>
                    <label for="cpf">CPF:</label>
                    <span class="cpfText"><?php echo htmlspecialchars($informacoesUsuario['cpf']); ?></span>
                </div>
            </div>
            <div id="mensagemEndereco" style="display: none;">
                <h3>Endereço</h3>
                <p>Você ainda não cadastrou um endereço. <a href="#" id="cadastrarEnderecoBtn">Clique aqui para cadastrar.</a></p>
            </div>
            <div class="endereco">
                <h3>Endereço</h3>
                <div class="textfield">
                    <span id="cepErro" class="erro"></span>
                    <label for="cep">CEP:</label>
                    <span class="cepText"><?php echo htmlspecialchars($informacoesUsuario['cep'] ?? ''); ?></span>
                    <input type="text" class="cepInput" name="cep" value="<?php echo htmlspecialchars($informacoesUsuario['cep'] ?? ''); ?>" style="display: none" required>
                </div>
                <div class="textfield">
                    <span id="enderecoErro" class="erro"></span>
                    <label for="endereco">Endereço:</label>
                    <span class="enderecoText"><?php echo htmlspecialchars($informacoesUsuario['endereco'] ?? ''); ?></span>
                    <input type="text" class="enderecoInput" name="endereco" value="<?php echo htmlspecialchars($informacoesUsuario['endereco'] ?? ''); ?>" style="display: none" required>
                </div>
                <div class="textfield">
                    <span id="complementoErro" class="erro"></span>
                    <label for="complemento">Complemento (opcional):</label>
                    <span class="complementoText"><?php echo htmlspecialchars($informacoesUsuario['complemento'] ?? ''); ?></span>
                    <input type="text" class="complementoInput" name="complemento" value="<?php echo htmlspecialchars($informacoesUsuario['complemento'] ?? ''); ?>" style="display: none">
                </div>
                <div class="textfield">
                    <span id="bairroErro" class="erro"></span>
                    <label for="bairro">Bairro:</label>
                    <span class="bairroText"><?php echo htmlspecialchars($informacoesUsuario['bairro'] ?? ''); ?></span>
                    <input type="text" class="bairroInput" name="bairro" value="<?php echo htmlspecialchars($informacoesUsuario['bairro'] ?? ''); ?>" style="display: none" required>
                </div>
                <div class="textfield">
                    <span id="cidadeErro" class="erro"></span>
                    <label for="cidade">Cidade:</label>
                    <span class="cidadeText"><?php echo htmlspecialchars($informacoesUsuario['cidade'] ?? ''); ?></span>
                    <input type="text" class="cidadeInput" name="cidade" value="<?php echo htmlspecialchars($informacoesUsuario['cidade'] ?? ''); ?>" style="display: none" required>
                </div>
                <div class="textfield">
                    <span id="ufErro" class="erro"></span>
                    <label for="uf">UF:</label>
                    <span class="ufText"><?php echo htmlspecialchars($informacoesUsuario['uf'] ?? ''); ?></span>
                    <input type="text" class="ufInput" name="uf" value="<?php echo htmlspecialchars($informacoesUsuario['uf'] ?? ''); ?>" style="display: none" required>
                </div>
            </div>
            <button class="btn-salvar">Salvar Alterações</button>
        </div>
    </div>
    <div id="trocar-senha" class="sub-conteudo" style="display:none;">
        <div class="section">
            <div class="trocar-senha">
                <h3>Trocar senha</h3>
                <form method="POST" action="/includes/perfil/trocar-senha.php">
                    <div class="textfield">
                        <label for="senha_antiga">Senha Antiga:</label>
                        <input type="password" name="senha_antiga" id="senha_antiga" required>
                    </div>
                    <div class="textfield">
                        <label for="nova_senha">Nova Senha:</label>
                        <input type="password" name="nova_senha" id="nova_senha" minlength="6" required>
                    </div>
                    <div class="textfield">
                        <label for="confirmar_senha">Confirmar Nova Senha:</label>
                        <input type="password" name="confirmar_senha" id="confirmar_senha" required>
                    </div>
                    <button class="btn-trocar-senha" type="button" onclick="validarSenha()">Trocar Senha</button>
                </form>
            </div>
        </div>
    </div>
    <div id="excluir-conta" class="sub-conteudo" style="display:none;">
        <div class="section">
            <div class="excluir">
                <h3>Excluir Conta</h3>
                <p>Tem certeza de que deseja excluir sua conta? Esta ação é irreversível.</p>
                <form id="formExcluirConta" action="/includes/perfil/excluir_conta.php" method="POST">
                    <input type="hidden" name="confirmar_exclusao" value="1">
                    <button type="submit" class="btn-excluir" onclick="confirmExclusao(event);">Excluir Conta</button>
                </form>
            </div>
        </div>
    </div>
    <script src="/views/tutor/main.js" type="module"></script>
</div>