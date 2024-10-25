<?php
include('../../login/protect.php');
include 'navbar.php';
include_once '../../login/conexaobd.php';

$cuidador_id = $_SESSION['id'];

// Consulta para obter os ganhos agrupados por mês
$sql = "SELECT DATE_FORMAT(data, '%Y-%m') AS mes, SUM(valor) AS total 
        FROM ganhos_cuidador 
        WHERE cuidador_id = ? 
        GROUP BY mes 
        ORDER BY mes";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $cuidador_id);
$stmt->execute();
$result = $stmt->get_result();

// Inicializa os meses do ano
$mesesAno = ['01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril', '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'];
$valores = array_fill_keys(array_keys($mesesAno), 0); // Preenche os valores com 0 para todos os meses

$totalGanhos = 0; // Inicializa o total de ganhos

// Processa os resultados e preenche os meses com valores
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mes = substr($row['mes'], 5, 2); // Extrai o mês
        $valores[$mes] = $row['total']; // Atualiza o valor do mês
        $totalGanhos += $row['total']; // Soma ao total de ganhos
    }
}

$totalGanhosFormatado = number_format($totalGanhos, 2, ',', '.'); // Formata o total de ganhos
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Completo do Cuidador</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="script.js"></script>
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../../assets/favicon.ico" alt="SafePet">
                <h2>SafePet</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="#" onclick="showContent('conteudo-1', this)"><span class="material-symbols-outlined">account_circle</span><span class="item-description">Meu Perfil</span></a></li>
                    <li><a href="#" onclick="showContent('conteudo-2', this)"><span class="material-symbols-outlined">finance</span><span class="item-description">Ganhos</span></a></li>
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

        <main class="conteudo">
            <div id="conteudo-1" class="content-section active">
                <h1>Perfil do Cuidador</h1>
                <div class="perfil-completo">
                    <?php
                    $sql = "SELECT nome, bio, foto_perfil FROM cuidadores WHERE id = ?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param('i', $cuidador_id);
                    $stmt->execute();
                    $stmt->bind_result($nome, $bio, $foto_perfil);
                    $stmt->fetch();
                    $stmt->close();

                    $foto_perfil = !empty($foto_perfil) ? 'uploads/fotos_cuidadores/' . $foto_perfil : '../../assets/profile-circle-icon.png';
                    ?>
                    <div class="perfil-header">
                        <form action="upload_foto.php" method="POST" enctype="multipart/form-data">
                            <div class="upload-avatar">
                                <img src="<?php echo htmlspecialchars($foto_perfil) . '?' . time(); ?>" alt="Foto do cuidador" class="cuidador-avatar" id="preview-avatar" onclick="document.getElementById('input-foto').click()">
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
                            <p>
                                <span class="bioText">
                                    <?php
                                    if (isset($bio)) {
                                        echo htmlspecialchars($bio);
                                    } else {
                                        echo '';
                                    }
                                    ?>
                                </span>
                            </p>
                            <textarea class="bioInput" name="bio" rows="5" cols="50" style="display: none"><?php echo htmlspecialchars($bio); ?></textarea>
                            <button type="submit" class="salvar-bio" style="display: none;">Salvar</button>
                            <input type="hidden" name="cuidador_id" value="<?php echo $cuidador_id; ?>">
                        </form>
                    </div>
                    <div class="section">
                        <h3>Experiência</h3>
                        <p>Especialista em raças de pequeno porte e animais idosos. Tenho experiência com passeios, alimentação e cuidados gerais, além de lidar com pets que possuem necessidades especiais.</p>
                    </div>
                    <div class="section">
                        <h3>Disponibilidade</h3>
                        <p>Segunda a Sexta: 08:00 - 18:00</p>
                        <p>Sábados: 10:00 - 14:00</p>
                    </div>
                </div>
            </div>

            <div id="conteudo-2" class="content-section">
                <h2>Meus Ganhos</h2>
                <div class="ganhos-container">
                    <div class="ganhos-resumo">
                        <h3>Total de Ganhos: R$ <?php echo $totalGanhosFormatado; ?></h3>
                    </div>

                    <?php
                    foreach ($mesesAno as $mesNumero => $mesNome) {
                        if ($valores[$mesNumero] > 0) { // Só mostra meses com ganhos
                            echo "<div class='ganho'>";
                            echo "<p><span class='material-symbols-outlined'>calendar_month</span> Mês: <span class='info-label'>" . htmlspecialchars($mesNome) . "</span></p>";
                            echo "<p><span class='material-symbols-outlined'>attach_money</span> Valor: <span class='info-label'>R$ " . number_format($valores[$mesNumero], 2, ',', '.') . "</span></p>";
                            echo "</div>";
                        }
                    }
                    ?>
                    <canvas id="ganhosChart"></canvas>
                </div>
            </div>

            <div id="conteudo-3" class="content-section">
                <?php
                $sql = "SELECT nome, cep, endereco, complemento, bairro, cidade, uf, telefone, email, dt_nascimento, cpf FROM cuidadores WHERE id = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param('i', $cuidador_id);
                $stmt->execute();
                $stmt->bind_result($nome, $cep, $endereco, $complemento, $bairro, $cidade, $uf, $telefone, $email, $dt_nascimento, $cpf);
                $stmt->fetch();
                $stmt->close();
                ?>
                <div id="info-pessoais" class="sub-conteudo">
                    <div class="informacoes-pessoais" cuidador-id="<?php echo $cuidador_id; ?>">
                        <h2>Informações Pessoais <button class="btn-editar"><span class="material-symbols-outlined">edit</span></button></h2>
                        <div class="principais-informacoes">
                            <div class="textfield">
                                <span id="nomeErro" class="erro"></span>
                                <label for="nome-cuidador">Nome Completo:</label>
                                <span class="nome-cuidadorText"><?php echo htmlspecialchars($nome); ?></span>
                                <input type="text" class="nome-cuidadorInput" name="nome-cuidador" value="<?php echo htmlspecialchars($nome); ?>" style="display: none" required>
                            </div>
                            <div class="textfield">
                                <span id="telefoneErro" class="erro"></span>
                                <label for="telefone">Telefone:</label>
                                <span class="telefoneText"><?php echo htmlspecialchars($telefone); ?></span>
                                <input type="tel" class="telefoneInput" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>" style="display: none" pattern="[0-9]{10,11}" required>
                            </div>
                            <div class="textfield">
                                <span id="emailErro" class="erro"></span>
                                <label for="email">E-mail:</label>
                                <span class="emailText"><?php echo htmlspecialchars($email); ?></span>
                                <input type="email" class="emailInput" name="email" value="<?php echo htmlspecialchars($email); ?>" style="display: none" required>
                            </div>
                            <div class="textfield">
                                <span id="dtNascimentoErro" class="erro"></span>
                                <label for="dt_nascimento">Data de Nascimento:</label>
                                <span class="dt_nascimentoText"><?php echo htmlspecialchars($dt_nascimento); ?></span>
                                <input type="date" class="dt_nascimentoInput" name="dt_nascimento" value="<?php echo htmlspecialchars($dt_nascimento); ?>" style="display: none" required>
                            </div>
                            <div class="textfield">
                                <span id="cpfErro" class="erro"></span>
                                <label for="cpf">CPF:</label>
                                <span class="cpfText"><?php echo htmlspecialchars($cpf); ?></span>
                            </div>
                        </div>
                        <h3>Endereço</h3>
                        <div id="mensagemEndereco" style="display: none;">
                            <p>Você ainda não cadastrou um endereço. <a href="#" id="cadastrarEnderecoBtn">Clique aqui para cadastrar.</a></p>
                        </div>
                        <div class="endereco" style="display: none;"> <!-- Começa oculta -->
                            <div class="textfield">
                                <span id="cepErro" class="erro"></span>
                                <label for="cep">CEP:</label>
                                <span class="cepText"><?php echo htmlspecialchars($cep ?? ''); ?></span>
                                <input type="text" class="cepInput" name="cep" value="<?php echo htmlspecialchars($cep ?? ''); ?>" required>
                            </div>
                            <div class="textfield">
                                <span id="enderecoErro" class="erro"></span>
                                <label for="endereco">Endereço:</label>
                                <span class="enderecoText"><?php echo htmlspecialchars($endereco ?? ''); ?></span>
                                <input type="text" class="enderecoInput" name="endereco" value="<?php echo htmlspecialchars($endereco ?? ''); ?>" required>
                            </div>
                            <div class="textfield">
                                <span id="complementoErro" class="erro"></span>
                                <label for="complemento">Complemento (opcional):</label>
                                <span class="complementoText"><?php echo htmlspecialchars($complemento ?? ''); ?></span>
                                <input type="text" class="complementoInput" name="complemento" value="<?php echo htmlspecialchars($complemento ?? ''); ?>">
                            </div>
                            <div class="textfield">
                                <span id="bairroErro" class="erro"></span>
                                <label for="bairro">Bairro:</label>
                                <span class="bairroText"><?php echo htmlspecialchars($bairro ?? ''); ?></span>
                                <input type="text" class="bairroInput" name="bairro" value="<?php echo htmlspecialchars($bairro ?? ''); ?>" required>
                            </div>
                            <div class="textfield">
                                <span id="cidadeErro" class="erro"></span>
                                <label for="cidade">Cidade:</label>
                                <span class="cidadeText"><?php echo htmlspecialchars($cidade ?? ''); ?></span>
                                <input type="text" class="cidadeInput" name="cidade" value="<?php echo htmlspecialchars($cidade ?? ''); ?>" required>
                            </div>
                            <div class="textfield">
                                <span id="ufErro" class="erro"></span>
                                <label for="uf">UF:</label>
                                <span class="ufText"><?php echo htmlspecialchars($uf ?? ''); ?></span>
                                <input type="text" class="ufInput" name="uf" value="<?php echo htmlspecialchars($uf ?? ''); ?>" required>
                            </div>
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
                    <h2>Suporte para Cuidadores</h2>

                    <div class="questions-container">
                        <div class="question">
                            <button>
                                <span>Como cuidar de pets?</span>
                                <i class="fas fa-chevron-down d-arrow"></i>
                            </button>
                            <p>Para cuidar de pets, é importante entender suas necessidades específicas, como alimentação adequada, atividades físicas, e cuidados médicos regulares. Também é necessário observar o comportamento para identificar qualquer sinal de desconforto ou doença.</p>
                        </div>

                        <div class="question">
                            <button>
                                <span>Quais são as políticas do SafePet?</span>
                                <i class="fas fa-chevron-down d-arrow"></i>
                            </button>
                            <p>O SafePet exige que os cuidadores sigam regras de ética e responsabilidade, garantindo a segurança e bem-estar dos pets. Além disso, é importante respeitar os horários e acordos de agendamento.</p>
                        </div>

                        <div class="question">
                            <button>
                                <span>Como gerenciar meus agendamentos?</span>
                                <i class="fas fa-chevron-down d-arrow"></i>
                            </button>
                            <p>Acesse a aba de agendamentos para verificar compromissos futuros, ou confirmar/cancelar serviços diretamente pela plataforma.</p>
                        </div>

                        <div class="question">
                            <button>
                                <span>Como funciona o pagamento?</span>
                                <i class="fas fa-chevron-down d-arrow"></i>
                            </button>
                            <p>O pagamento é realizado através da plataforma SafePet após a conclusão do serviço. Você pode acompanhar os valores recebidos na aba de "Ganhos" do seu perfil.</p>
                        </div>

                        <div class="question">
                            <button>
                                <span>Como entrar em contato com o suporte?</span>
                                <i class="fas fa-chevron-down d-arrow"></i>
                            </button>
                            <p>Para suporte, utilize a seção de contato no seu perfil ou envie uma mensagem diretamente via e-mail para a equipe do SafePet em <a href="mailto:suporte.safepet@gmail.com">suporte.safepet@gmail.com</a>, que responderá em até 48 horas.</p>
                        </div>

                        <section class="contato">
                            <h2>Não achou sua dúvida? Escreva abaixo</h2>
                            <form action="https://api.staticforms.xyz/submit" method="post">
                                <form id="contactForm" action="https://api.staticforms.xyz/submit" method="post">
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
    <script>
        // Gráfico de Ganhos
        const ctx = document.getElementById('ganhosChart').getContext('2d');
        const ganhosChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                datasets: [{
                    label: 'Ganhos por Mês',
                    data: [
                        <?php
                        foreach ($mesesAno as $mesNumero => $mesNome) {
                            echo $valores[$mesNumero] . ',';
                        }
                        ?>
                    ],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return 'R$ ' + tooltipItem.raw.toFixed(2).replace('.', ',');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Valor em R$'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>