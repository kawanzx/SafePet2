<?php

include '../../login/protect.php';
include 'navbar.php';
include '../../login/conexaobd.php';

$sql = "
    SELECT c.id, c.nome, c.preco_hora, c.cidade, c.uf, c.foto_perfil, 
           AVG(a.nota) as avaliacao_media 
    FROM cuidadores c
    LEFT JOIN avaliacoes a ON c.id = a.id_cuidador
    GROUP BY c.id, c.nome, c.preco_hora, c.cidade, c.uf, foto_perfil
";
$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuidadores Disponíveis - SafePet</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="pesquisar.css">
</head>

<body>
    <!-- Seção de Cuidadores Disponíveis -->

    <section class="cuidadores">

        <div id="divBusca">
            <img src="search3.png" alt="Buscar..." />
            <input type="text" id="txtBusca" placeholder="Buscar..." />
            <button id="btnBusca">Buscar</button>
        </div>

        <h2>Cuidadores Disponíveis</h2>

        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="cuidador">
                    <div class="avatar">
                        <img src="<?php echo '../cuidador/uploads/fotos_cuidadores/' . htmlspecialchars($row['foto_perfil'] ? $row['foto_perfil'] : '../../assets/profile-circle-icon.png'); ?>" class="cuidador-avatar" alt="Foto de <?php echo htmlspecialchars($row['nome']); ?>">
                    </div>
                    <div class="details">
                        <h3><?php echo htmlspecialchars($row['nome']); ?></h3>
                        <p>Avaliação:
                            <?php
                            $avaliacao = isset($row['avaliacao_media']) ? round($row['avaliacao_media']) : 0;
                            echo str_repeat('⭐', $avaliacao) . str_repeat('☆', 5 - $avaliacao);
                            ?>
                        </p>
                        <p>Preço:
                            <?php
                            if (isset($row['preco_hora'])) {
                                echo 'R$ ' . number_format($row['preco_hora'], 2, ',', '.') . '/hora';
                            } else {
                                echo 'Preço não informado';
                            }
                            ?></p>
                        <p>Localização: <?php echo htmlspecialchars($row['cidade'] . ', ' . $row['uf']); ?></p>
                    </div>
                    <a href="perfil_cuidador.php?id=<?php echo $row['id']; ?>" class="schedule-button">Ver Perfil</a>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>Nenhum cuidador disponível no momento.</p>
        <?php endif; ?>

    </section>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>