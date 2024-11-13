<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/navbar.php';
include __DIR__ . '/../../includes/db.php';

$sql = "
    SELECT c.id, c.nome, c.preco_hora, c.cidade, c.uf, c.foto_perfil, 
           AVG(a.nota) as avaliacao_media 
    FROM cuidadores c
    LEFT JOIN avaliacoes a ON c.id = a.id_cuidador
    GROUP BY c.id, c.nome, c.preco_hora, c.cidade, c.uf, foto_perfil
";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$stmt->bind_result($id, $nome, $preco_hora, $cidade, $uf, $foto_perfil, $nota_media);
$stmt->fetch();
$stmt->close();

$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuidadores Disponíveis - SafePet</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <!-- Seção de Cuidadores Disponíveis -->

    <section class="cuidadores">

        <div id="divBusca">
            <span class="material-symbols-outlined">search</span>
            <input type="text" id="txtBusca" placeholder="Buscar..." />
            <button id="btnBusca">Buscar</button>
        </div>

        <h2>Cuidadores Disponíveis</h2>

        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="cuidador">
                    <div class="avatar">
                        <img src="<?php echo '/assets/uploads/fotos-cuidadores/' . htmlspecialchars($row['foto_perfil'] ? $row['foto_perfil'] : '../../assets/profile-circle-icon.png'); ?>" class="cuidador-avatar" alt="Foto de <?php echo htmlspecialchars($row['nome']); ?>">
                    </div>
                    <div class="details">
                        <h3><?php echo htmlspecialchars($row['nome']); ?></h3>
                        <p>Avaliação:
                            <?php
                            $avaliacao = isset($nota_media) ? round($nota_media) : 0;
                            echo str_repeat('⭐', $avaliacao) . str_repeat('☆', 5 - $avaliacao);
                            ?>
                        </p>
                        <p>Preço:
                            <?php
                            if (isset($preco_hora)) {
                                echo 'R$ ' . number_format($preco_hora, 2, ',', '.') . '/hora';
                            } else {
                                echo 'Preço não informado';
                            }
                            ?></p>
                        <p>Localização: <?php echo htmlspecialchars($cidade . ', ' . $uf); ?></p>
                    </div>
                    <a href="perfil-cuidador.php?id=<?php echo $id; ?>" class="schedule-button">Ver Perfil</a>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>Nenhum cuidador disponível no momento.</p>
        <?php endif; ?>

    </section>