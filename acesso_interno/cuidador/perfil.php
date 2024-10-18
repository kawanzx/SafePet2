<?php

include('../../login/protect.php');
include 'navbar.php';
include_once '../../login/conexaobd.php';

$cuidador_id = $_SESSION['id'];

$sql = "SELECT servico, valor, data FROM ganhos_cuidador WHERE cuidador_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $cuidador_id);
$stmt->execute();
$result = $stmt->get_result();

$valores = []; // Inicializa o array de valores
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $valores[] = $row['valor']; // Adiciona o valor ao array
    }
} else {
    $valores = []; // Array vazio se não houver ganhos
}
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
                <!-- Seção Completa do Perfil -->
                <div class="perfil-completo">
                    <div class="perfil-header">
                        <img src="https://cdn-icons-png.flaticon.com/512/9706/9706583.png" alt="Foto do Cuidador" class="cuidador-avatar">
                        <div>
                            <h2>Nome do Cuidador</h2>
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
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='ganho'>";
                            echo "<p>Serviço: " . htmlspecialchars($row['servico']) . "</p>";
                            echo "<p>Valor: R$ " . htmlspecialchars($row['valor']) . "</p>";
                            echo "<p>Data: " . htmlspecialchars($row['data']) . "</p>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Você ainda não possui ganhos registrados.</p>";
                    }
                    ?>
                    <canvas id="ganhosChart"></canvas>
                </div>
                
                <script>
                    const ganhos = <?php echo json_encode($valores); ?>;

                    // Crie um array de meses para os labels
                    const datas = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const ctx = document.getElementById('ganhosChart').getContext('2d');
                    const myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: datas, // Usando meses como labels
                            datasets: [{
                                label: 'Meus Ganhos',
                                data: ganhos,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>
            </div>
            
            <div id="conteudo-3" class="content-section">
                <p>conteudo 3</p>
            </div>

            <div id="conteudo-4" class="content-section">
                <div class="suporte">
                    <h2>Suporte para Cuidadores</h2>

                    <h4 class="faq-question">Como cuidar de pets?</h4>
                    <p class="faq-answer">Para cuidar de pets, é importante entender suas necessidades específicas, como alimentação adequada, atividades físicas, e cuidados médicos regulares. Também é necessário observar o comportamento para identificar qualquer sinal de desconforto ou doença.</p>

                    <h4 class="faq-question">Quais são as políticas do SafePet?</h4>
                    <p class="faq-answer">O SafePet exige que os cuidadores sigam regras de ética e responsabilidade, garantindo a segurança e bem-estar dos pets. Além disso, é importante respeitar os horários e acordos de agendamento.</p>

                    <h4 class="faq-question">Como gerenciar meus agendamentos?</h4>
                    <p class="faq-answer">Acesse a aba de agendamentos para verificar compromissos futuros, ou confirmar/cancelar serviços diretamente pela plataforma.</p>

                    <h4 class="faq-question">Como funciona o pagamento?</h4>
                    <p class="faq-answer">O pagamento é realizado através da plataforma SafePet após a conclusão do serviço. Você pode acompanhar os valores recebidos na aba de "Ganhos" do seu perfil.</p>

                    <h4 class="faq-question">Como entrar em contato com o suporte?</h4>
                    <p class="faq-answer">Para suporte, utilize a seção de contato no seu perfil ou envie uma mensagem diretamente via e-mail para a equipe do SafePet, que responderá em até 48 horas.</p>
                </div>
            </div>
    
            <div id="conteudo-5" class="content-section">
                <div class="politica-privacidade">
                    <h2>Política de Privacidade</h2>
                    <p>Na SafePet, respeitamos sua privacidade e estamos comprometidos em proteger suas informações pessoais. Esta política explica como coletamos, usamos e protegemos seus dados.</p>
                    
                    <h4>1. Coleta de Informações</h4>
                    <p>Coletamos informações pessoais que você nos fornece ao se cadastrar, como nome, e-mail e telefone.</p>
                    
                    <h4>2. Uso das Informações</h4>
                    <p>Utilizamos suas informações para melhorar nossos serviços, enviar notificações e atender às suas solicitações.</p>
                    
                    <h4>3. Proteção de Dados</h4>
                    <p>Adotamos medidas de segurança para proteger suas informações contra acesso não autorizado.</p>
                    
                    <h4>4. Compartilhamento de Informações</h4>
                    <p>Não compartilhamos suas informações pessoais com terceiros sem seu consentimento.</p>
                    
                    <h4>5. Alterações na Política</h4>
                    <p>Podemos atualizar esta política periodicamente. Avisaremos sobre mudanças significativas.</p>
                    
                    <p>Se você tiver dúvidas sobre nossa política de privacidade, entre em contato conosco.</p>
                </div>
            </div>
        </main>
    </div>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>