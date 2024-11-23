<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../includes/functions.php';
include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/db.php';

$cuidador_id = $_SESSION['id'];
$cuidador = getCuidadorProfile($mysqli, $cuidador_id);

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
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="/img/favicon.ico" alt="SafePet">
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
            <?php include 'perfil-cuidador.php' ?>
            <?php include 'ganhos-cuidador-view.php' ?>
            <?php include '../shared/conta.php' ?>
            <?php include 'suporte-cuidador.php' ?>
            <?php include '../shared/politica-privacidade.php' ?>
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
    <script>
        const tipoUsuario = "<?php echo $_SESSION['tipo_usuario']; ?>";
    </script>
    <script src="/views/cuidador/main.js" type="module"></script>
</body>

</html>