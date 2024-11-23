<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/../../auth/protect.php';
include __DIR__ . '/../../includes/navbar.php';
include __DIR__ . '/../../includes/db.php';
include __DIR__ . '/../../includes/buscar.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuidadores Disponíveis - SafePet</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <section class="cuidadores">

        <div class="wrap">
            <div class="search" id="search">
                <button type="submit" class="searchButton" onclick="searchData()" id="btnBusca">
                    <span class="material-symbols-outlined">search</span>
                </button>
                <input type="search" id="search_input" placeholder="Pesquisar" class="search__input">
            </div>
        </div>

        <h2>Cuidadores Disponíveis</h2>

        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="cuidador">
                    <div class="avatar">
                        <img onclick="location.href='perfil-cuidador.php?id=<?php echo $row['id']; ?>'"
                            src="<?php echo '/assets/uploads/fotos-cuidadores/' . htmlspecialchars($row['foto_perfil'] ? $row['foto_perfil'] : '../../../img/profile-circle-icon.png'); ?>"
                            class="avatar-cuidador"
                            alt="Foto de <?php echo htmlspecialchars($row['nome']); ?>">
                    </div>
                    <div class="details">
                        <h3 onclick="location.href='perfil-cuidador.php?id=<?php echo $row['id']; ?>'">
                            <?php echo htmlspecialchars($row['nome']); ?>
                        </h3>
                        <p>
                            <?php
                            $notaMedia = isset($row['nota_media']) ? $row['nota_media'] : 0;
                            $estrelasCompletas = floor($notaMedia); 
                            $meiaEstrela = $notaMedia - $estrelasCompletas >= 0.5; 
                            $estrelasVazias = 5 - $estrelasCompletas - ($meiaEstrela ? 1 : 0);

                            echo str_repeat('<i class="fa-solid fa-star"></i>', $estrelasCompletas);
                            if ($meiaEstrela) {
                                echo '<i class="fa-solid fa-star-half-stroke"></i>';
                            }
                            echo str_repeat('<i class="fa-regular fa-star"></i>', $estrelasVazias);
                            ?>
                        </p>
                        <p>
                            <?php
                            if (isset($row['preco_hora'])) {
                                echo 'R$ ' . number_format($row['preco_hora'], 2, ',', '.') . '/hora';
                            } else {
                                echo 'Preço não informado';
                            }
                            ?>
                        </p>
                        <p>
                            <?php 
                            if (isset($row['cidade']) && isset($row['uf'])) {
                                echo htmlspecialchars($row['cidade'] . ', ' . $row['uf']);
                            } else {
                                echo 'Endereço não informado';
                            }
                            ?>
                        </p>
                    </div>
                    <a href="perfil-cuidador.php?id=<?php echo $row['id']; ?>" class="schedule-button">Ver Perfil</a>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>Nenhum cuidador disponível no momento.</p>
        <?php endif; ?>

    </section>
    <script src="/assets/js/buscar.js"></script>
</body>