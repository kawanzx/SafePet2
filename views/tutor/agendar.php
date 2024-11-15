<?php
include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/db.php'; 
require_once '../../includes/functions.php';
//include __DIR__ . '/../../includes/navbar.php';

$cuidador_id = $_GET['id'];
$cuidador = getCuidadorProfile($mysqli, $cuidador_id);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Serviço</title>
    <link rel="stylesheet" href="/assets/css/agendar-servico.css">
</head>
<body>
    <div class="container">
        <h1>Agendar Serviço</h1>

        <div class="cuidador-info">
            <img src="<?php echo $cuidador['foto_perfil'] . '?' . time(); ?>" alt="Foto do Cuidador" class="cuidador-avatar">
            <div>
                <h2 id="nome-cuidador"><?php echo $cuidador['nome']; ?></h2>
                <p id="valor-servico">Valor por Serviço: <?php echo $cuidador['preco_hora']; ?></p>
            </div>  
        </div>

        <form action="agendar-servico.php" method="POST">
            <!-- Seleção de Data -->
            <div class="form-group">
                <label for="data-servico">Data do Serviço:</label>
                <input type="date" id="data-servico" name="data_servico" required>
            </div>

            <!-- Seleção de Pets -->
            <div class="form-group">
                <label for="pets">Selecione os Pets:</label>
                <div id="pets">
                    <!-- Exemplo de opções de pets (geradas dinamicamente pelo PHP) -->
                    <input type="checkbox" id="pet1" name="pets[]" value="1">
                    <label for="pet1">Pet 1</label><br>
                    <input type="checkbox" id="pet2" name="pets[]" value="2">
                    <label for="pet2">Pet 2</label><br>
                    <!-- Mais pets podem ser adicionados aqui com PHP -->
                </div>
            </div>

            <!-- Mensagem para o Cuidador -->
            <div class="form-group">
                <label for="mensagem">Mensagem para o Cuidador:</label>
                <textarea id="mensagem" name="mensagem" rows="4" placeholder="Digite uma mensagem..."></textarea>
            </div>

            <!-- Botão de Enviar -->
            <button type="submit">Agendar Serviço</button>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>