<?php
session_start();

include('../../login/protect.php');
include 'navbar.php';
include_once '../../login/conexaobd.php';

$tutor_id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="shortcut icon" href="../../assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="\acesso_interno\tutor\perfil.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../assets/favicon.ico" alt="">
                <h2>SafePet</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="#" onclick="showContent('conteudo-1', this)"><span class="material-symbols-outlined">account_circle</span><span class="item-description">Meu Perfil</span></a></li>
                    <li><a href="#" onclick="showContent('conteudo-2', this)"><span class="material-symbols-outlined">pets</span><span class="item-description">Meus Pets</span></a></li>
                    <li>
                        <a href="#" onclick="alternarSubmenu(event)"><span class="material-symbols-outlined">person</span><span class="item-description">Conta</span></a>
                        <ul class="submenu">
                            <li><a href="#" onclick="showContent('conteudo-3', this)" data-sub-content="info-pessoais">Informações Pessoais</a></li>
                            <li><a href="#" onclick="showContent('conteudo-3', this)" data-sub-content="trocar-senha">Trocar Senha</a></li>
                            <li><a href="#" onclick="showContent('conteudo-3', this)" data-sub-content="excluir-conta">Excluir Conta</a></li>
                        </ul>
                    </li>
                    <li><a href="#" onclick="showContent('conteudo-4', this)"><span class="material-symbols-outlined">info</span><span class="item-description">Suporte</span></a></li>
                    <li><a href="#" onclick="showContent('conteudo-5', this)"><span class="material-symbols-outlined">lock</span><span class="item-description">Política de Privacidade</span></a></li>
                </ul>
            </nav>
        </aside>

        <!--  Tela de Perfil  -->

        <main class="conteudo">
            <div id="conteudo-1" class="content-section active">
                <h1>Perfil do Tutor</h1>
                <div class="meu-perfil">
                    <?php
                    $sql = "SELECT nome, bio, foto_perfil FROM tutores WHERE id = ?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param('i', $tutor_id);
                    $stmt->execute();
                    $stmt->bind_result($nome, $bio, $foto_perfil);
                    $stmt->fetch();
                    $stmt->close();

                    $foto_perfil = !empty($foto_perfil) ? 'uploads/fotos_tutores/' . $foto_perfil : '../../assets/profile-circle-icon.png';
                    ?>
                    <div class="perfil-header">
                        <form action="upload_foto.php" method="POST" enctype="multipart/form-data">
                            <div class="upload-avatar">
                                <img src="<?php echo htmlspecialchars($foto_perfil) . '?' . time(); ?>" alt="Foto do tutor" class="tutor-avatar" id="preview-avatar" onclick="document.getElementById('input-foto').click()">
                                <input type="file" name="nova_foto" accept="image/*" id="input-foto" style="display: none;" onchange="previewAndUploadFoto()">
                            </div>
                        </form>
                        <div>
                            <h2><?php echo htmlspecialchars($nome); ?></h2>
                            <p><span class="info-label">Avaliação:</span> ⭐⭐⭐⭐ (4.8)</p>
                        </div>
                    </div>
                    <div class="section">
                        <form action="editar-bio.php" method="POST">
                            <h3>Bio
                                <button type="button" class="editar-bio">
                                    <span class="material-symbols-outlined">edit</span>
                                </button>
                            </h3>
                            <p><span class="bioText"><?php echo htmlspecialchars($bio); ?></span></p>
                            <textarea class="bioInput" name="bio" rows="5" cols="50" style="display: none"><?php echo htmlspecialchars($bio); ?></textarea>
                            <button type="submit" class="salvar-bio" style="display: none;">Salvar</button>
                            <input type="hidden" name="tutor_id" value="<?php echo $tutor_id; ?>">
                        </form>
                    </div>
                    <div class="section">
                        <h3>Pets</h3>
                        <div class='pets-container'>
                            <?php
                            $sql = "SELECT nome, especie, raca, idade, sexo, peso, castrado, descricao, foto FROM pets WHERE tutor_id = '$tutor_id'";
                            $stmt = $mysqli->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<div class='pet'>";
                                    echo "<div class='pet-header'>";
                                    if (!empty($row['foto'])) {
                                        echo "<img src='" . htmlspecialchars($row['foto']) . "' alt='Foto do pet'>";
                                    } else {
                                        echo "<img src='" . "' alt=''>";
                                    }
                                    echo "<h2 class='nomePet-perfil'>" . htmlspecialchars($row['nome']) . "</h2>";
                                    echo "</div>";
                                    echo "<p>Espécie: " . htmlspecialchars($row['especie']) . "</p>";
                                    echo "<p>Raça: " . htmlspecialchars($row['raca']) . "</p>";
                                    echo "<p>Idade: " . htmlspecialchars($row['idade']) . " anos</p>";
                                    echo "<p>Sexo: " . htmlspecialchars($row['sexo']) . "</p>";
                                    echo "<p>Peso: " . htmlspecialchars($row['peso']) . " kg</p>";
                                    echo "<p>Castrado: " . ($row['castrado'] == 1 ? "Sim" : "Não") . "</p>";
                                    echo "<p>Descrição: " . htmlspecialchars($row['descricao']) . "</p>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<p>Nenhum pet cadastrado.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!--  Tela de Pets  -->

            <div id="conteudo-2" class="content-section">
                <div class="meus-pets">
                    <div class="section">
                        <h2>Meus Pets</h2>
                        <?php
                        $sql = "SELECT id, nome, especie, raca, idade, sexo, peso, castrado, descricao, foto FROM pets WHERE tutor_id = ?";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bind_param("i", $tutor_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        ?>

                        <div class="pets-container">
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) { ?>
                                    <div class='pet' data-pet-id="<?php echo $row['id']; ?>">
                                        <div class='pet-header'>
                                            <span class="fotoPet">
                                                <?php if (!empty($row['foto'])) { ?>
                                                    <img id="preview-<?php echo $row['id']; ?>" src="<?php echo htmlspecialchars($row['foto']); ?>" alt='Foto do pet' class="fotoPetImg">
                                                <?php } else { ?>
                                                    <img id="preview-<?php echo $row['id']; ?>" src="" alt='' class="fotoPetImg">
                                                <?php } ?>
                                            </span>
                                            <input type="file" id="foto-<?php echo $row['id']; ?>" name="foto" class="fotoPetInput" style="display:none;" accept="image/*">
                                            <input type="hidden" class="fotoPetAtual" value="<?php echo htmlspecialchars($row['foto']); ?>">
                                            <h2 class="nomePet-perfil">
                                                <span class="nomePetText"><?php echo htmlspecialchars($row['nome']); ?></span>
                                                <input type="text" class="nomePetInput" value="<?php echo htmlspecialchars($row['nome']); ?>" style="display: none;">
                                            </h2>
                                            <button class="editar-btn">Editar</button>
                                            <button class="excluir-btn">Excluir</button>
                                            <button class="salvar-btn" style="display: none;">Salvar</button>
                                            <button class="cancelar-btn" style="display: none;">Cancelar</button>
                                        </div>
                                        <hr>
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
                                            <span class="castradoText"><?php echo (htmlspecialchars($row['castrado'])); ?></span>
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
                            <?php }
                            } else {
                                echo "<p>Você ainda não cadastrou nenhum pet.</p>";
                            }
                            ?>
                        </div>

                    </div>
                    <div class="section">
                        <h2>Cadastrar Pet</h2>
                        <div class="cadastrar-pet">
                            <form id="form-pet" action="cadastro-pet.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" id="petId" name="petId">
                                <div class="coluna">
                                    <label for="nome-pet">Nome do Pet:</label>
                                    <input type="text" id="nome" name="nome-pet">
                                    <label for="raca">Raça:</label>
                                    <input type="text" id="raca" name="raca">
                                </div>
                                <div class="coluna">
                                    <label for="especie">Espécie:</label>
                                    <select id="especie" name="especie">
                                        <option value="cachorro">Cachorro</option>
                                        <option value="gato">Gato</option>
                                    </select>
                                    <label for="idade">Idade (em anos):</label>
                                    <input type="number" id="idade" name="idade" min="0">
                                </div>
                                <div class="coluna">
                                    <label for="sexo">Sexo:</label>
                                    <select id="sexo" name="sexo">
                                        <option value="M">Macho</option>
                                        <option value="F">Fêmea</option>
                                    </select>
                                    <label for="peso">Peso (kg):</label>
                                    <input type="number" id="peso" name="peso" step="0.01" min="0">
                                    <label for="castrado">O pet é castrado?</label>
                                    <select id="castrado" name="castrado">
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
            <div id="conteudo-3" class="content-section">
                <?php
                $sql = "SELECT nome, endereco, telefone, email, dt_nascimento, cpf FROM tutores WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('i', $tutor_id);
                $stmt->execute();
                $stmt->bind_result($nome, $endereco, $telefone, $email, $dt_nascimento, $cpf);
                $stmt->fetch();
                $stmt->close();
                ?>
                <div id="info-pessoais" class="sub-conteudo">
                    <div class="informacoes-pessoais" tutor-id="<?php echo $tutor_id; ?>">
                        <h2>Informações Pessoais <button class="btn-editar"><span class="material-symbols-outlined">edit</span></button></h2>
                        <div class="textfield">
                            <label for="nome-tutor">Nome Completo:</label>
                            <span class="nome-tutorText"><?php echo htmlspecialchars($nome); ?></span>
                            <input type="text" class="nome-tutorInput" name="nome-tutor" value="<?php echo htmlspecialchars($nome); ?>" style="display: none" required>
                        </div>
                        <div class="textfield">
                            <span id="nomeErro" class="erro"></span>
                            <label for="endereco">Endereço:</label>
                            <span class="enderecoText"><?php echo htmlspecialchars($endereco); ?></span>
                            <input type="text" class="enderecoInput" name="endereco" value="<?php echo htmlspecialchars($endereco); ?>" style="display: none" required>
                        </div>
                        <div class="textfield">
                            <span id="enderecoErro" class="erro"></span>
                            <label for="telefone">Telefone:</label>
                            <span class="telefoneText"><?php echo htmlspecialchars($telefone); ?></span>
                            <input type="tel" class="telefoneInput" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>" style="display: none" pattern="[0-9]{10,11}" required>
                        </div>
                        <div class="textfield">
                            <span id="telefoneErro" class="erro"></span>
                            <label for="email">E-mail:</label>
                            <span class="emailText"><?php echo htmlspecialchars($email); ?></span>
                            <input type="email" class="emailInput" name="email" value="<?php echo htmlspecialchars($email); ?>" style="display: none" required>
                        </div>
                        <div class="textfield">
                            <span id="emailErro" class="erro"></span>
                            <label for="dt_nascimento">Data de Nascimento:</label>
                            <span class="dt_nascimentoText"><?php echo htmlspecialchars($dt_nascimento); ?></span>
                            <input type="date" class="dt_nascimentoInput" name="dt_nascimento" value="<?php echo htmlspecialchars($dt_nascimento); ?>" style="display: none" required>
                        </div>
                        <div class="textfield">
                            <span id="dtNascimentoErro" class="erro"></span>
                            <label for="cpf">CPF:</label>
                            <span class="cpfText"><?php echo htmlspecialchars($cpf); ?></span>
                        </div>
                        <button class="btn-salvar">Salvar Alterações</button>
                    </div>
                </div>
                <div id="trocar-senha" class="sub-conteudo" style="display:none;">
                    <div class="trocar-senha">
                        <h2>Trocar senha</h2>
                        <form method="POST" action="trocar-senha.php" onsubmit="return validarSenha();">
                            <div class="textfield">
                                <label for="senha_antiga">Senha Antiga:</label>
                                <input type="password" name="senha_antiga" required>
                            </div>

                            <div class="textfield">
                                <label for="nova_senha">Nova Senha:</label>
                                <input type="password" name="nova_senha" id="nova_senha" minlength="6" required>
                            </div>

                            <div class="textfield">
                                <label for="confirmar_senha">Confirmar Nova Senha:</label>
                                <input type="password" name="confirmar_senha" id="confirmar_senha" required>
                            </div>
                            <button class="btn-trocar-senha">Trocar Senha</button>
                        </form>
                    </div>
                </div>

                <div id="excluir-conta" class="sub-conteudo" style="display:none;">
                    <div class="excluir-conta">
                        <h2>Excluir Conta</h2>
                        <p>Tem certeza de que deseja excluir sua conta? Esta ação é irreversível.</p>
                        <form action="excluir_conta.php" method="POST">
                            <button type="submit" name="confirmar_exclusao" class="btn-excluir">Excluir Conta</button>
                        </form>
                        <a href="perfil.php" class="btn-cancelar">Cancelar</a>
                    </div>
                </div>
            </div>
            <div id="conteudo-4" class="content-section">
                <div class="suporte">
                    <h2>Suporte para Tutores</h2>

                    <div class="questions-container">
                        <div class="question">
                            <button>
                                <span>Como encontrar um cuidador?</span>
                                <i class="fas fa-chevron-down d-arrow"></i>
                            </button>
                            <p>Para encontrar um cuidador, acesse a aba de busca no SafePet e filtre os cuidadores disponíveis pela sua localização e pelos serviços que oferecem. Você pode ler avaliações de outros tutores para escolher o melhor cuidador para o seu pet.</p>
                        </div>

                        <div class="question">
                            <button>
                                <span>Quais são as políticas do SafePet para tutores?</span>
                                <i class="fas fa-chevron-down d-arrow"></i>
                            </button>
                            <p>O SafePet exige que os tutores informem todos os detalhes relevantes sobre seus pets, como necessidades especiais, comportamentos específicos e condições de saúde. É importante também respeitar os horários combinados com o cuidador.</p>
                        </div>

                        <div class="question">
                            <button>
                                <span>Como faço um agendamento?</span>
                                <i class="fas fa-chevron-down d-arrow"></i>
                            </button>
                            <p>Para agendar um serviço, selecione o cuidador desejado e escolha um horário disponível na plataforma. Você receberá uma confirmação após o agendamento ser concluído.</p>
                        </div>

                        <div class="question">
                            <button>
                                <span>Como funciona o pagamento?</span>
                                <i class="fas fa-chevron-down d-arrow"></i>
                            </button>
                            <p>O pagamento é realizado através da plataforma SafePet. Após o serviço ser concluído, o valor será cobrado automaticamente e você poderá acompanhar o status na aba de "Pagamentos" do seu perfil.</p>
                        </div>

                        <div class="question">
                            <button>
                                <span>Como entrar em contato com o suporte?</span>
                                <i class="fas fa-chevron-down d-arrow"></i>
                            </button>
                            <p>Para suporte, utilize a seção de contato no seu perfil ou envie uma mensagem diretamente via e-mail para a equipe do SafePet em <a href="mailto:suporte.safepet@gmail.com">suporte@safepet.com</a>, que responderá em até 48 horas.</p>
                        </div>
                        
                        <section class="contato">
                            <h2>Não achou sua dúvida? Escreva abaixo</h2>
                            <form action="https://api.staticforms.xyz/submit" method="post"><form id="contactForm" action="https://api.staticforms.xyz/submit" method="post">
                                <label>Nome</label>
                                <input type="text" name="name" placeholder="Digite seu nome" autocomplete="off" required>
                                <label>Email</label>
                                <input type="email" name="email" placeholder="Digite seu email" autocomplete="off" required>
                                <label>Mensagem</label>
                                <textarea name="message" cols="30" rows="10" placeholder="Digite sua mensagem" required></textarea>
                                <button type="submit">Enviar</button>

                                <input type="hidden" name="accessKey" value="69faecc1-fb39-4467-a250-5ec40ff0baaa">
                                <input type="hidden" name="redirectTo" value="http://localhost:8000/acesso_interno/cuidador/perfil.php#">
                            </form>
                        </section>

                    </div>
                </div>
            </div>
            <div id="conteudo-5" class="content-section">
                <div class="politica-privacidade">
                    <h2>Política de Privacidade</h2>
                    <p>Na SafePet, respeitamos sua privacidade e estamos comprometidos em proteger suas informações pessoais. Esta política explica como coletamos, usamos e protegemos seus dados.</p>

                    <h4>1. Coleta de Informações</h4>
                    <p>Coletamos informações pessoais que você nos fornece ao se cadastrar, como nome, e-mail e telefone.</p>

                    <h4>2. Uso das Informações</h4>
                    <p>Usamos suas informações para oferecer nossos serviços, entrar em contato e melhorar nossa plataforma.</p>

                    <h4>3. Compartilhamento de Informações</h4>
                    <p>Não compartilhamos suas informações pessoais com terceiros, exceto quando necessário para o funcionamento dos nossos serviços.</p>

                    <h4>4. Segurança</h4>
                    <p>Adotamos medidas de segurança para proteger suas informações contra acesso não autorizado.</p>

                    <h4>5. Alterações na Política</h4>
                    <p>Podemos atualizar esta política e informaremos sobre quaisquer alterações significativas.</p>
                </div>
            </div>
        </main>
    </div>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>