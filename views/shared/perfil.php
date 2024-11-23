<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/db.php';
require_once '../../includes/functions.php';
include __DIR__ . '/../../includes/navbar.php';

$usuario_id = $_GET['id'];
$tipo_usuario = $_GET['tipo'];


if (!$usuario_id || !in_array($tipo_usuario, ['tutor', 'cuidador'])) {
    die('Perfil inválido.');
}

if ($tipo_usuario === 'cuidador') {
    $usuario = getCuidadorProfile($mysqli, $usuario_id);
} elseif ($tipo_usuario === 'tutor') {
    $usuario = getTutorProfile($mysqli, $usuario_id);
    $pets = getPetsByTutor($mysqli, $usuario_id);
}

if (!$usuario) {
    die('Usuário não encontrado.');
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do <?php echo ucfirst($tipo_usuario); ?></title>
    <link rel="stylesheet" href="/views/tutor/main.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div id="conteudo-1" class="content-section active">
        <h1>Perfil do <?php echo ucfirst($tipo_usuario); ?></h1>
        <div class="meu-perfil">
            <div class="section">
                <span style="position: absolute; margin: -20px; cursor:pointer; font-size:27px" class="material-symbols-outlined" onclick="history.back()">arrow_back</span>
                <div class="perfil-header">
                    <img src="<?php echo $usuario['foto_perfil'] . '?' . time(); ?>" alt="Foto do usuário" class="usuario-avatar" id="preview-avatar">
                    <div>
                        <h2><?php echo $usuario['nome']; ?></h2>
                        <?php if ($tipo_usuario === 'cuidador'): ?>
                            <div class="header">
                                <div class="avaliacoes" id="avaliacoes">
                                    <p id="media">Média: --</p>
                                    <div id="estrelas" class="estrelas"></div>
                                    <p id="total">Total de avaliações: --</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bio">
                    <h3>Bio</h3>
                    <p><span class="bioText"><?php echo $usuario['bio'] ?? 'Sem informações.'; ?></span></p>
                </div>

                <?php if ($tipo_usuario === 'cuidador'): ?>
                    <div class="experiencia">
                        <h3>Experiência</h3>
                        <p><span class="experienciaText"><?php echo $usuario['experiencia'] ?? 'Sem experiência registrada.'; ?></span></p>
                    </div>
                    <div class="disponibilidade">
                        <h3>Disponibilidade</h3>
                        <?php
                        $sql = "SELECT dia_da_semana, hora_inicio, hora_fim FROM disponibilidade_cuidador WHERE cuidador_id = ?";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bind_param("i", $usuario_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<p>{$row['dia_da_semana']}: {$row['hora_inicio']} - {$row['hora_fim']}</p>";
                            }
                        } else {
                            echo "<p>Nenhuma disponibilidade cadastrada.</p>";
                        }

                        $stmt->close();
                        ?>
                    </div>

                    <div class="comentarios-card">
                        <h3>Comentários</h3>
                        <div id="comentarios">
                            <!-- Os comentários serão carregados aqui via AJAX -->
                        </div>
                    </div>
                <?php else: ?>
                    <div class="pets-card">
                        <div class="section">
                            <h3>Pets</h3>
                            <div class='pets-container'>
                                <?php if (!empty($pets)): ?>
                                    <?php foreach ($pets as $row): ?>
                                        <div class='pet'>
                                            <div class='pet-header'>
                                                <?php if (!empty($row['foto'])): ?>
                                                    <img src="<?php echo htmlspecialchars('/assets/uploads/fotos-pets/' . $row['foto']); ?>" alt="Foto do pet">
                                                <?php else: ?>
                                                    <img src="/assets/uploads/fotos-pets/default-image.png" alt="Foto padrão para pets">
                                                <?php endif; ?>
                                                <h2 class='nomePet-perfil'><?php echo htmlspecialchars($row['nome']); ?></h2>
                                            </div>
                                            <p>Espécie: <?php echo htmlspecialchars($row['especie']); ?></p>
                                            <p>Raça: <?php echo htmlspecialchars($row['raca']); ?></p>
                                            <p>Idade: <?php echo htmlspecialchars($row['idade']); ?> anos</p>
                                            <p>Sexo: <?php echo htmlspecialchars($row['sexo']); ?></p>
                                            <p>Peso: <?php echo htmlspecialchars($row['peso']); ?> kg</p>
                                            <p>Castrado: <?php echo ($row['castrado'] == 1 ? "Sim" : "Não"); ?></p>
                                            <p>Descrição: <?php echo htmlspecialchars($row['descricao']); ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Nenhum pet cadastrado.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="/assets/js/perfil/avaliacoes.js"></script>
</body>

</html>