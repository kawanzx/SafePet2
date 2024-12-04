<?php
require_once '../db.php';

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
    $tipo_usuario = $_SESSION['tipo_usuario'];
    $tabela = ($tipo_usuario === 'tutor') ? 'tutores' : 'cuidadores';

    $sql = "SELECT nome, foto_perfil, bio FROM $tabela WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "<p>Usuário não encontrado.</p>";
        exit;
    }
} else {
    echo "<p>ID do usuário não especificado.</p>";
    exit;
}
?>
