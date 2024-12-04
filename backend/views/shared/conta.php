<div id="conteudo-3" class="content-section">
    <?php
    include __DIR__ . '/../../auth/protect.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $tipo_usuario = $_SESSION['tipo_usuario'] ?? 'tutor';
    $usuario_id = $_SESSION['id'];
    $informacoesUsuario = getInformacoesUsuario($mysqli, $usuario_id, $tipo_usuario);
    ?>

    <div id="info-pessoais" class="sub-conteudo">
        <div class="section" usuario-id="<?php echo $usuario_id; ?>">
            <div class="principais-informacoes">
                <h3>Informações Pessoais</h3>
                <div class="textfield">
                    <span id="nomeErro" class="erro"></span>
                    <label for="nome">Nome Completo</label>
                    <input type="text" class="nome-tutorInput" name="nome" value="<?php echo htmlspecialchars($informacoesUsuario['nome']); ?>" required>
                </div>
                <div class="textfield-group">
                    <div class="textfield telefone-container">
                        <span id="telefoneErro" class="erro"></span>
                        <label for="telefone">Telefone</label>
                        <input type="tel" class="telefoneInput" name="telefone" value="<?php echo htmlspecialchars($informacoesUsuario['telefone']); ?>" pattern="[0-9]{10,11}" required>
                    </div>
                    <div class="textfield codigo-container">
                        <span id="codigoTelErro" class="erro"></span>
                        <label for="codigo_telefone">Código Telefone</label>
                        <input type="text" class="codigoTelInput" name="codigo_telefone" placeholder="Código" required>
                        <button class="btn-codigo" id="btn-telefone">Enviar código</button>
                        <button class="btn-codigo" id="btn-reenviar-tel" style="display:none;">Reenviar</button>
                        <span id="timer-tel" style="display:none;"></span>
                    </div>
                </div>
                <div class="textfield-group">
                    <div class="textfield email-container">
                        <span id="emailErro" class="erro"></span>
                        <label for="email">E-mail</label>
                        <input type="email" class="emailInput" name="email" value="<?php echo htmlspecialchars($informacoesUsuario['email']); ?>" required>
                    </div>
                    <div class="textfield codigo-container">
                        <span id="codigoEmailErro" class="erro"></span>
                        <label for="codigo_email">Código E-mail</label>
                        <input type="text" class="codigoEmailInput" name="codigo_email" placeholder="Código" required>
                        <button class="btn-codigo" id="btn-email">Enviar código</button>
                        <button class="btn-codigo" id="btn-reenviar-email" style="display:none;">Reenviar</button>
                        <span id="timer-email" style="display:none;"></span>
                    </div>
                </div>
                <div class="textfield">
                    <span id="dtNascimentoErro" class="erro"></span>
                    <label for="dt_nascimento">Data de Nascimento</label>
                    <input type="date" class="dt_nascimentoInput" name="dt_nascimento" value="<?php echo htmlspecialchars($informacoesUsuario['dt_nascimento']); ?>" required>
                </div>
                <div class="textfield">
                    <span id="cpfErro" class="erro"></span>
                    <label for="cpf">CPF</label>
                    <span class="cpfText"><?php echo htmlspecialchars($informacoesUsuario['cpf']); ?> <span class="material-symbols-outlined lock">lock</span></span>
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
                    <label for="cep">CEP</label>
                    <input type="text" class="cepInput" name="cep" value="<?php echo htmlspecialchars($informacoesUsuario['cep'] ?? ''); ?>" required>
                </div>
                <div class="textfield">
                    <span id="enderecoErro" class="erro"></span>
                    <label for="endereco">Endereço</label>
                    <input type="text" class="enderecoInput" name="endereco" value="<?php echo htmlspecialchars($informacoesUsuario['endereco'] ?? ''); ?>" required>
                </div>
                <div class="textfield">
                    <span id="complementoErro" class="erro"></span>
                    <label for="complemento">Complemento (opcional)</label>
                    <input type="text" class="complementoInput" name="complemento" value="<?php echo htmlspecialchars($informacoesUsuario['complemento'] ?? ''); ?>">
                </div>
                <div class="textfield">
                    <span id="bairroErro" class="erro"></span>
                    <label for="bairro">Bairro</label>
                    <input type="text" class="bairroInput" name="bairro" value="<?php echo htmlspecialchars($informacoesUsuario['bairro'] ?? ''); ?>" required>
                </div>
                <div class="textfield">
                    <span id="cidadeErro" class="erro"></span>
                    <label for="cidade">Cidade</label>
                    <input type="text" class="cidadeInput" name="cidade" value="<?php echo htmlspecialchars($informacoesUsuario['cidade'] ?? ''); ?>" required>
                </div>
                <div class="textfield">
                    <span id="ufErro" class="erro"></span>
                    <label for="uf">UF</label>
                    <input type="text" class="ufInput" name="uf" maxlength="2" value="<?php echo htmlspecialchars($informacoesUsuario['uf'] ?? ''); ?>" required>
                </div>
            </div>
            <button id="btn-salvar" class="btn-salvar">Salvar Alterações</button>
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
                <form id="formExcluirConta" action="/backend/includes/perfil/excluir_conta.php" method="POST">
                    <input type="hidden" name="confirmar_exclusao" value="1">
                    <button type="submit" class="btn-excluir" onclick="confirmExclusao(event);">Excluir Conta</button>
                </form>
            </div>
        </div>
    </div>
    <script src="/backend/views/tutor/main.js" type="module"></script>
</div>