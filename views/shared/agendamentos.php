<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/navbar.php';
include __DIR__ . '/../../includes/db.php';
include __DIR__ . '/../../includes/functions.php';

$user_id = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento Cuidador - SafePet</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css">
</head>

<body>

    <!-- Sidebar -->
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="/img/favicon.ico" alt="SafePet">
                <h2>SafePet</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="#1" onclick="showContent('solicitacoes', this)"><span class="material-symbols-outlined">pending</span><span class="item-description">Solicitações</span></a></li>
                    <li><a href="#2" onclick="showContent('breve', this)"><span class="material-symbols-outlined">hourglass_bottom</span><span class="item-description">Em Breve</span></a></li>
                    <li><a href="#3" onclick="showContent('cancelados', this)"><span class="material-symbols-outlined">block</span><span class="item-description">Cancelados</span></a></li>
                    <li><a href="#4" onclick="showContent('concluidos', this)"><span class="material-symbols-outlined">check</span><span class="item-description">Concluídos</span></a></li>
                </ul>
            </nav>
        </aside>

        <main class="conteudo">
            <div id="solicitacoes" class="content-section active">
                <?php $agendamentos = getAgendamentosByUser($mysqli, $user_id, $tipo_usuario, 'pendente'); ?>
                <h2>Solicitações Pendentes</h2>
                <?php $encontrouUsuario = false; ?>
                <div id="agendamentos">
                    <?php if (!empty($agendamentos)): ?>
                        <?php foreach ($agendamentos as $agendamento): ?>
                            <?php
                            if ($tipo_usuario === 'tutor') {
                                $outroUsuario = getCuidadorProfile($mysqli, $agendamento['cuidador_id']);
                                $outroUsuarioId = $agendamento['cuidador_id'];
                                $outroUsuarioTipo = 'cuidador';
                            } else {
                                $outroUsuario = getTutorProfile($mysqli, $agendamento['tutor_id']);
                                $outroUsuarioId = $agendamento['tutor_id'];
                                $outroUsuarioTipo = 'tutor';
                            }

                            $petIds = explode(',', $agendamento['pet_id']);
                            $pets = getPetNamesByIds($mysqli, $petIds);
                            ?>

                            <?php if ($outroUsuario['ativo'] === "1") : ?>
                                <?php $encontrouUsuario = true; ?>
                                <div class="agendamento-card" data-id="<?= $agendamento['id'] ?>">
                                    <div class="agendamento-details" onclick="location.href='/views/shared/perfil.php?id=<?php echo $outroUsuarioId ?>&tipo=<?php echo $outroUsuarioTipo; ?>'">
                                        <img src="<?= htmlspecialchars($outroUsuario['foto_perfil']) ?>" alt="Foto de perfil">
                                        <div class="agendamento-info">
                                            <p class="id"><strong>ID:</strong> <?= htmlspecialchars($agendamento['id']) ?></p>
                                            <p><strong>Nome:</strong> <?= htmlspecialchars($outroUsuario['nome']) ?></p>
                                            <p><strong>Data do Serviço:</strong> <?= htmlspecialchars($agendamento['data_servico']) ?></p>
                                            <p><strong>Pets:</strong>
                                                <?= htmlspecialchars(implode(', ', array_values($pets))) ?>
                                            </p>
                                            <p><strong>Mensagem:</strong> <?= htmlspecialchars($agendamento['mensagem']) ?></p>
                                        </div>
                                    </div>
                                    <div class="agendamento-acoes">
                                        <?php if ($tipo_usuario === 'cuidador'): ?>
                                            <button class="btn-aceitar" type="button" data-id="<?= $agendamento['id'] ?>">Aceitar</button>
                                            <button class="btn-recusar" type="button" data-id="<?= $agendamento['id'] ?>">Recusar</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$encontrouUsuario) : ?>
                            <p>Não há solicitações pendentes no momento.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p>Não há solicitações pendentes no momento.</p>
                    <?php endif; ?>

                </div>
            </div>

            <div id="breve" class="content-section">
                <?php $agendamentos = getAgendamentosByUser($mysqli, $user_id, $tipo_usuario, 'aceito'); ?>
                <h2>Próximos serviços</h2>
                <?php $encontrouUsuario = false; ?>
                <div id="agendamentos">
                    <?php if (!empty($agendamentos)): ?>
                        <?php foreach ($agendamentos as $agendamento): ?>
                            <?php
                            if ($tipo_usuario === 'tutor') {
                                $outroUsuario = getCuidadorProfile($mysqli, $agendamento['cuidador_id']);
                                $outroUsuarioId = $agendamento['cuidador_id'];
                                $outroUsuarioTipo = 'cuidador';
                            } elseif ($tipo_usuario === 'cuidador') {
                                $outroUsuario = getTutorProfile($mysqli, $agendamento['tutor_id']);
                                $outroUsuarioId = $agendamento['tutor_id'];
                                $outroUsuarioTipo = 'tutor';
                            }

                            $petIds = explode(',', $agendamento['pet_id']);
                            $pets = getPetNamesByIds($mysqli, $petIds);
                            ?>

                            <?php if ($outroUsuario['ativo'] === "1") : ?>
                                <?php $encontrouUsuario = true; ?>
                                <div class="agendamento-card" data-id="<?= $agendamento['id'] ?>">
                                    <div class="agendamento-details" onclick="location.href='/views/shared/perfil.php?id=<?php echo $outroUsuarioId ?>&tipo=<?php echo $outroUsuarioTipo; ?>'">
                                        <img src="<?= htmlspecialchars($outroUsuario['foto_perfil']) ?>" alt="Foto de perfil">
                                        <div class="agendamento-info">
                                            <p class="id"><strong>ID:</strong> <?= htmlspecialchars($agendamento['id']) ?></p>
                                            <p><strong>Nome:</strong> <?= htmlspecialchars($outroUsuario['nome']) ?></p>
                                            <p><strong>Data do Serviço:</strong> <?= htmlspecialchars($agendamento['data_servico']) ?></p>
                                            <p><strong>Pets:</strong>
                                                <?= htmlspecialchars(implode(', ', array_values($pets))) ?>
                                            </p>
                                            <p><strong>Mensagem:</strong> <?= htmlspecialchars($agendamento['mensagem']) ?></p>
                                        </div>
                                    </div>
                                    <div class="agendamento-acoes">
                                        <button class="btn-cancelar" type="button" data-id="<?= $agendamento['id'] ?>">Cancelar</button>
                                        <button class="btn-chat" type="button" data-id="<?= $agendamento['id'] ?>" onclick="location.href = 'chat.php?agendamento_id=<?php echo $agendamento['id'] ?>&user_id=<?php echo $outroUsuarioId ?>&tipo=<?php echo $outroUsuarioTipo; ?>'">Chat</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$encontrouUsuario) : ?>
                            <p>Não há agendamentos aceitos no momento.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p>Não há agendamentos aceitos no momento.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div id="cancelados" class="content-section">
                <?php $agendamentos = getAgendamentosByUser($mysqli, $user_id, $tipo_usuario, 'cancelado'); ?>
                <h2>Cancelados</h2>
                <div id="agendamentos">
                    <?php if (!empty($agendamentos)): ?>
                        <?php foreach ($agendamentos as $agendamento): ?>
                            <?php if ($agendamento['status'] === 'cancelado'): ?>
                                <div class="agendamento-card" data-id="<?= $agendamento['id'] ?>">
                                    <div class="agendamento-details" onclick="location.href='/views/shared/perfil.php?id=<?php echo $outroUsuarioId ?>&tipo=<?php echo $outroUsuarioTipo; ?>'">
                                        <div class="agendamento-info">
                                            <p class="id"><strong>ID:</strong> <?= htmlspecialchars($agendamento['id']) ?></p>
                                            <p><strong>Data:</strong> <?= htmlspecialchars($agendamento['data_servico']) ?></p>
                                            <p><strong>Mensagem:</strong> <?= htmlspecialchars($agendamento['mensagem']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Não há agendamentos cancelados.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div id="concluidos" class="content-section">
                <?php $agendamentos = getAgendamentosByUser($mysqli, $user_id, $tipo_usuario, 'concluido'); ?>
                <h2>Concluídos</h2>
                <div id="agendamentos">
                    <?php if (!empty($agendamentos)): ?>
                        <?php foreach ($agendamentos as $agendamento): ?>
                            <?php
                            if ($agendamento['status'] === 'concluido'):
                                if ($tipo_usuario === 'tutor') {
                                    $outroUsuario = getCuidadorProfile($mysqli, $agendamento['cuidador_id']);
                                } else {
                                    $outroUsuario = getTutorProfile($mysqli, $agendamento['tutor_id']);
                                }

                                $petIds = explode(',', $agendamento['pet_id']);
                                $pets = getPetNamesByIds($mysqli, $petIds);
                            ?>
                                <div class="agendamento-card" data-id="<?= $agendamento['id'] ?>">
                                    <div class="agendamento-details" onclick="location.href='/views/shared/perfil.php?id=<?php echo $outroUsuarioId ?>&tipo=<?php echo $outroUsuarioTipo; ?>'">
                                        <img src="<?= htmlspecialchars($outroUsuario['foto_perfil']) ?>" alt="Foto de perfil">
                                        <div class="agendamento-info">
                                            <p class="id"><strong>ID:</strong> <?= htmlspecialchars($agendamento['id']) ?></p>
                                            <p><strong>Nome:</strong> <?= htmlspecialchars($outroUsuario['nome']) ?></p>
                                            <p><strong>Data do Serviço:</strong> <?= htmlspecialchars($agendamento['data_servico']) ?></p>
                                            <p><strong>Pets:</strong>
                                                <?= htmlspecialchars(implode(', ', array_values($pets))) ?>
                                            </p>
                                            <p><strong>Mensagem:</strong> <?= htmlspecialchars($agendamento['mensagem']) ?></p>
                                        </div>
                                    </div>
                                    <div class="agendamento-acoes">
                                        <button class="btn-cancelar" type="button" data-id="<?= $agendamento['id'] ?>">Cancelar</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Não há solicitações concluídas no momento.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="main.js" type="module"></script>
</body>

</html>