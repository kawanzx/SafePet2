<?php
session_start();

include '../../includes/db.php';
include '../../includes/functions.php';

//$nome_remetente = $_SESSION['nome'];
$id_remetente = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$agendamento_id = $_GET['agendamento_id'];

$tabela = $tipo_usuario === 'tutor' ? 'tutores' : 'cuidadores';

$query = "SELECT m.mensagem, m.id_remetente, u.nome as remetente_nome
          FROM mensagens m
          JOIN $tabela u ON m.id_remetente = u.id
          WHERE m.agendamento_id = ? ORDER BY m.data_envio";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $agendamento_id);
$stmt->execute();
$result = $stmt->get_result();

$mensagens = [];
while ($row = $result->fetch_assoc()) {
    $mensagens[] = [
        'mensagem' => $row['mensagem'],
        'remetente_nome' => $row['remetente_nome'],
        'id_remetente' => $row['id_remetente']
    ];
}

//echo json_encode($mensagens);

$agendamentos = getAgendamentosByUser($mysqli, $id_remetente, $tipo_usuario, 'aceito');

if (!empty($agendamentos)) {
    $primeiroAgendamento = $agendamentos[0];

    if ($tipo_usuario === 'tutor') {
        $id_destinatario = $primeiroAgendamento['cuidador_id'];
        $destinatario_info = getInformacoesUsuario($mysqli, $id_destinatario, 'cuidador');
        $nome_destinatario = $destinatario_info['nome'];
    } else {
        $id_destinatario = $primeiroAgendamento['tutor_id'];
        $destinatario_info = getInformacoesUsuario($mysqli, $id_destinatario, 'tutor');
        $nome_destinatario = $destinatario_info['nome'];
    }
} else {
    $id_destinatario = null;
}
?>
<link rel="stylesheet" href="/assets/css/chat.css">
<h1>Chat</h1>
<div id="chat-container">
    <div id="agendamento_id" style="display: none;"><?php echo $agendamento_id; ?></div>
    <div id="id_remetente" style="display: none;"><?php echo $id_remetente ?></div>
    <div id="id_destinatario" style="display: none;"><?php echo $id_destinatario ?></div>
    <div id="nome_remetente" style="display: none;"><?php echo $nome_remetente ?></div>
    <div id="nome_destinatario" style="display: none;"><?php echo $nome_destinatario ?></div>
    <div id="mensagens"></div>
    <div class="enviar-mensagem">
        <input type="text" id="mensagem" placeholder="Digite sua mensagem">
        <button id="enviar">Enviar</button>
    </div>
</div>

<script src="https://cdn.socket.io/4.0.1/socket.io.min.js"></script>
<script>
    const socket = io("http://localhost:3000");

    // Verifique se a conexão foi bem-sucedida
    socket.on('connect', () => {
        console.log('Conectado ao servidor WebSocket:', socket.id);
    });

    // Registrar o usuário com seu ID
    const idRemetente = document.getElementById('id_remetente').textContent;
    socket.emit('register', idRemetente);

    // Receber mensagens em tempo real
    socket.on('chatMessage', (msg) => {
        const mensagensDiv = document.getElementById('mensagens');
        mensagensDiv.innerHTML += `<div>${msg}</div>`;
    });

    window.onload = () => {
    const idAgendamento = document.getElementById('agendamento_id').textContent;

        fetch(`/includes/agendamentos/buscar-mensagens.php?agendamento_id=${idAgendamento}`)
        .then(response => response.json())
        .then(data => {
            const mensagensDiv = document.getElementById('mensagens');
            data.forEach(msg => {
                const classe = msg.id_remetente == idRemetente ? 'mensagem-remetente' : 'mensagem-recebida';

                // Exibir a mensagem com o nome do remetente
                mensagensDiv.innerHTML += `
                    <div class="${classe}">
                        <strong>${msg.remetente_nome}</strong>: ${msg.mensagem}
                    </div>
                `;
            });
        });
    };

    // Enviar mensagem
    document.getElementById('enviar').addEventListener('click', () => {
        const idRemetente = document.getElementById('id_remetente').textContent;
        const idDestinatario = document.getElementById('id_destinatario').textContent;
        const idAgendamento = document.getElementById('agendamento_id').textContent;
        const mensagem = document.getElementById('mensagem').value;

        // Exibir a mensagem localmente
        const mensagensDiv = document.getElementById('mensagens');
        mensagensDiv.innerHTML += `<div class="mensagem-remetente">${mensagem}</div>`;

        socket.emit('chatMessage', {
            id_remetente: idRemetente,
            id_destinatario: idDestinatario,
            mensagem: mensagem,
            agendamento_id: idAgendamento

        });

        document.getElementById('mensagem').value = '';
    });
</script>