<?php
include('db.php');

$cuidador_id = $_GET['id'];

$diasDaSemanaMap = [
    'domingo' => 0,
    'segunda-feira' => 1,
    'terça-feira' => 2,
    'quarta-feira' => 3,
    'quinta-feira' => 4,
    'sexta-feira' => 5,
    'sábado' => 6
];

$sql = "SELECT dia_da_semana, hora_inicio, hora_fim FROM disponibilidade_cuidador WHERE cuidador_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $cuidador_id);
$stmt->execute();
$result = $stmt->get_result();

$diasHorariosPermitidos = [];
while ($row = $result->fetch_assoc()) {
    $dia = $row['dia_da_semana'];
    if (isset($diasDaSemanaMap[$dia])) {
        $diasHorariosPermitidos[$dia] = [
            'hora_inicio' => $row['hora_inicio'],
            'hora_fim' => $row['hora_fim']
        ];
    }
}

echo json_encode($diasHorariosPermitidos);

