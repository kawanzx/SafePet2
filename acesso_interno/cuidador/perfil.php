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
                    <li><a href="#" onclick="showContent('conteudo-1', this)"><span class="material-symbols-outlined">account_circle</span>Meu Perfil</a></li>
                    <li><a href="#" onclick="showContent('conteudo-2', this)"><span class="material-symbols-outlined">finance</span>Ganhos</a></li>
                    <li><a href="#" onclick="showContent('conteudo-3', this)"><span class="material-symbols-outlined">person</span>Informações Pessoais</a></li>
                    <li><a href="#" onclick="showContent('conteudo-4', this)"><span class="material-symbols-outlined">info</span>Suporte</a></li>
                    <li><a href="#" onclick="showContent('conteudo-5', this)"><span class="material-symbols-outlined">lock</span>Política de Privacidade</a></li>
                </ul>
            </nav>
        </aside>

        <main class="conteudo">
            <div id="conteudo-1" class="content-section active">
                <h1>Perfil do Cuidador</h1>
                <div class="perfil-completo">
                    <div class="perfil-header">
                        <img src="https://cdn-icons-png.flaticon.com/512/9706/9706583.png" alt="Foto do Cuidador" class="cuidador-avatar">
                        <div>
                            <h2><?php echo $_SESSION['nome']; ?></h2>
                            <p><span class="info-label">Avaliação:</span> ⭐⭐⭐⭐ (4.8)</p>
                        </div>
                    </div>
                    <div class="section">
                        <h3>Bio</h3>
                        <p>Apaixonado por animais, cuidando de pets há 5 anos. Sempre tive uma ligação forte com animais e dedico meu tempo a proporcionar o melhor cuidado possível aos pets.</p>
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
                <p>conteudo 3</p>
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
                            <p>Para suporte, utilize a seção de contato no seu perfil ou envie uma mensagem diretamente via e-mail para a equipe do SafePet em <a>suporte@safepet.com</a>, que responderá em até 48 horas.</p>
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
