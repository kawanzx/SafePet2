<?php
session_start();

include('../../login/protect.php');
include 'navbar.php';
include_once '../../login/conexaobd.php';

$tutor_id = $_SESSION['id'];
$sql = "SELECT nome, especie, raca, idade, sexo, peso, castrado, descricao, foto FROM pets WHERE tutor_id = '$tutor_id'";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="shortcut icon" href="../../assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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
                    <li><a href="#" onclick="showContent('conteudo-2', this)"><span class="material-symbols-outlined">pets</span>Meus Pets</a></li>
                    <li><a href="#" onclick="showContent('conteudo-3', this)"><span class="material-symbols-outlined">person</span>Informações Pessoais</a></li>
                    <li><a href="#" onclick="showContent('conteudo-4', this)"><span class="material-symbols-outlined">info</span>Suporte</a></li>
                    <li><a href="#" onclick="showContent('conteudo-5', this)"><span class="material-symbols-outlined">lock</span>Política de Privacidade</a></li>
                </ul>
            </nav>
        </aside>
        <main class="conteudo">
            <div id="conteudo-1" class="content-section active">
                <h1>Perfil do Tutor</h1>
                <div class="perfil-completo">
                    <div class="perfil-header">
                        <img src="https://cdn-icons-png.flaticon.com/512/9706/9706583.png" alt="Foto do tutor" class="tutor-avatar">
                        <div>
                            <h2><?php echo $_SESSION['nome']; ?></h2>
                            <p><span class="info-label">Avaliação:</span> ⭐⭐⭐⭐ (4.8)</p>
                        </div>
                    </div>
                    <div class="section">
                        <h3>Bio</h3>
                        <p>Mãe de pet. Apaixonada pelos meus bichinhos.</p>
                    </div>
                    <div class="section">
                        <h3>Pets</h3>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='pet'>";
                                echo "<h2>" . htmlspecialchars($row['nome']) . "</h2>";
                                echo "<p>Espécie: " . htmlspecialchars($row['especie']) . "</p>";
                                echo "<p>Raça: " . htmlspecialchars($row['raca']) . "</p>";
                                echo "<p>Idade: " . htmlspecialchars($row['idade']) . " anos</p>";
                                echo "<p>Sexo: " . htmlspecialchars($row['sexo']) . "</p>";
                                echo "<p>Peso: " . htmlspecialchars($row['peso']) . " kg</p>";
                                echo "<p>Castrado: " . ($row['castrado'] == 1 ? "Sim" : "Não") . "</p>";
                                echo "<p>Descrição: " . htmlspecialchars($row['descricao']) . "</p>";
                                if (!empty($row['foto'])) {
                                    echo "<img src='" . htmlspecialchars($row['foto']) . "' alt='Foto do pet' width='150'>";
                                }
                                echo "</div>";
                            }
                        } else {
                            echo "<p>Você ainda não cadastrou nenhum pet.</p>";
                        }
                        ?>
                    </div>
                    <div class="section">
                        <h3>Disponibilidade</h3>
                        <p>Segunda a Sexta: 08:00 - 18:00</p>
                        <p>Sábados: 10:00 - 14:00</p>
                    </div>
                </div>
            </div>

            <div id="conteudo-2" class="content-section">
                <div class="container">
                    <div class="section">
                        <h2>Meus Pets</h2>
                        <?php
                        $sql = "SELECT nome, especie, raca, idade, sexo, peso, castrado, descricao, foto FROM pets WHERE tutor_id = ?";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bind_param("i", $tutor_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='pet'>";
                                echo "<h2>" . htmlspecialchars($row['nome']) . "</h2>";
                                echo "<p>Espécie: " . htmlspecialchars($row['especie']) . "</p>";
                                echo "<p>Raça: " . htmlspecialchars($row['raca']) . "</p>";
                                echo "<p>Idade: " . htmlspecialchars($row['idade']) . " anos</p>";
                                echo "<p>Sexo: " . htmlspecialchars($row['sexo']) . "</p>";
                                echo "<p>Peso: " . htmlspecialchars($row['peso']) . " kg</p>";
                                echo "<p>Castrado: " . ($row['castrado'] == 1 ? "Sim" : "Não") . "</p>";
                                echo "<p>Descrição: " . htmlspecialchars($row['descricao']) . "</p>";
                                if (!empty($row['foto'])) {
                                    echo "<img src='" . htmlspecialchars($row['foto']) . "' alt='Foto do pet' width='150'>";
                                }
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                    <div class="section">
                        <h2>Cadastrar Pet</h2>
                        <form action="cadastro-pet.php" method="POST" enctype="multipart/form-data">
                            <label for="nome">Nome do Pet:</label>
                            <input type="text" id="nome" name="nome" required>
                            <label for="especie">Espécie:</label>
                            <select id="especie" name="especie" required>
                                <option value="cachorro">Cachorro</option>
                                <option value="gato">Gato</option>
                            </select>
                            <label for="raca">Raça:</label>
                            <input type="text" id="raca" name="raca">
                            <label for="idade">Idade (em anos):</label>
                            <input type="number" id="idade" name="idade" min="0">
                            <label for="sexo">Sexo:</label>
                            <select id="sexo" name="sexo" required>
                                <option value="M">Macho</option>
                                <option value="F">Fêmea</option>
                            </select>
                            <label for="peso">Peso (kg):</label>
                            <input type="number" id="peso" name="peso" step="0.01" min="0">
                            <label for="castrado">O pet é castrado?</label>
                            <select id="castrado" name="castrado" required>
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                            </select>
                            <label for="descricao">Descrição (comportamento, necessidades especiais):</label>
                            <textarea id="descricao" name="descricao" rows="4"></textarea>
                            <label for="foto">Foto do Pet:</label>
                            <input type="file" id="foto" name="foto" accept="image/*">
                            <input type="submit" value="Cadastrar Pet">
                        </form>
                    </div>
                </div>
            </div>
            <div id="conteudo-3" class="content-section">
                <p>conteudo 3</p>
            </div>
            <div id="conteudo-4" class="content-section">
                <p>conteudo 4</p>
            </div>
            <div id="conteudo-5" class="content-section">
                <p>conteudo 5</p>
            </div>
        </main>
    </div>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>