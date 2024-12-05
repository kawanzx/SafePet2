<?php
include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/db.php';
require_once '../../includes/functions.php';
include __DIR__ . '/../../includes/navbar.php';

$tutor_id = $_SESSION['id'];
$cuidador_id = $_GET['id'];
$cuidador = getCuidadorProfile($mysqli, $cuidador_id);
$pets = getPetsByTutor($mysqli, $tutor_id);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Serviço - SafePet</title>
    <link rel="stylesheet" href="/backend/assets/css/agendar-servico.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="shortcut icon" href="/backend/img/favicon.ico" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
</head>

<body>
    <div class="container">
        <h1>Agendar Serviço</h1>

        <div class="cuidador-info">
            <img src="<?php echo $cuidador['foto_perfil'] . '?' . time(); ?>" alt="Foto do Cuidador" class="cuidador-avatar">
            <div>
                <h2 id="nome-cuidador"><?php echo $cuidador['nome']; ?></h2>
                <p id="valor-servico">R$ <?php echo $cuidador['preco_hora']; ?> / hora</p>
            </div>
        </div>

        <form id="form-agendar-servico" action="/backend/includes/agendar-servico.php?id=<?php echo $cuidador_id; ?>" method="POST">
            <!-- Seleção de Data -->
            <div class="section">
                <label for="data-servico">Data do Serviço:</label>
                <div class="data-agendamento">
                    <input type="date" id="data-servico" name="data_servico" required>
                </div>
            </div>

            <div class="section">
                <label for="hora_inicio">Hora de Início:</label>
                <select id="hora-inicio" name="hora_inicio"></select>

                <label for="hora_fim">Hora de Término:</label>
                <select id="hora-fim" name="hora_fim"></select>
            </div>

            <!-- Seleção de Pets -->
            <div class="section">
                <label for="pets">Selecione os Pets:</label>
                <div id="pets">
                    <?php if (!empty($pets)): ?>
                        <?php foreach ($pets as $row): ?>
                            <label class="pet">
                                <input type="checkbox" name="petsSelecionados[]" value="<?php echo $row['id']; ?>" class="pet-checkbox">
                                <?php if (!empty($row['foto'])): ?>
                                    <img src="<?php echo htmlspecialchars('/backend/assets/uploads/fotos-pets/' . $row['foto']); ?>" alt="Foto do pet">
                                <?php else: ?>
                                    <img src="/backend/assets/uploads/fotos-pets/default-image.png" alt="Foto padrão para pets">
                                <?php endif; ?>
                                <h2 class="nomePet-perfil"><?php echo htmlspecialchars($row['nome']); ?></h2>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Nenhum pet cadastrado.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mensagem para o Cuidador -->
            <div class="section">
                <label for="mensagem">Mensagem para o Cuidador:</label>
                <textarea id="mensagem" name="mensagem" rows="4" placeholder="Digite uma mensagem..."></textarea>
            </div>

            <!-- Botão de Enviar -->
            <button type="submit">Agendar Serviço</button>
        </form>
    </div>
    <script type="module" src="/backend/assets/js/agendar-servico.js"></script>
</body>

</html>