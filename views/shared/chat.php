<?php
session_start();

include '../../includes/db.php';
include '../../includes/functions.php';

$id_remetente = $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$agendamento_id = $_GET['agendamento_id'];

$tabela = ($tipo_usuario === 'tutor' ? 'cuidadores' : 'tutores');

$agendamentos = getAgendamentosByUser($mysqli, $id_remetente, $tipo_usuario, 'aceito');

if (!empty($agendamentos)) {
    $primeiroAgendamento = $agendamentos[0];

    if ($tipo_usuario === 'tutor') {
        $id_destinatario = $primeiroAgendamento['cuidador_id'];
    } else {
        $id_destinatario = $primeiroAgendamento['tutor_id'];
    }
} else {
    $id_destinatario = null;
}

$stmt = $mysqli->prepare("SELECT nome, foto_perfil FROM $tabela WHERE id = ?");
$stmt->bind_param("i", $id_destinatario);
$stmt->execute();
$result = $stmt->get_result();
$destinatario = $result->fetch_assoc();

$outroUsuarioId = $_GET['user_id'];
$outroUsuarioTipo = $_GET['tipo'];

?>

<link rel="stylesheet" href="/assets/css/chat.css">
<div id="chat-container">
    <div id="destinatario-info">
        <img src="/img/seta-voltar.svg" style="cursor:pointer; width:25px;" onclick="history.back()" alt="Seta para voltar a página">
        <?php if ($tipo_usuario === 'tutor') { ?>
            <img src="/assets/uploads/fotos-cuidadores/<?php echo htmlspecialchars($destinatario['foto_perfil']); ?>" alt="Foto do destinatário" class="foto-perfil" onclick="location.href='/views/shared/perfil.php?id=<?php echo $outroUsuarioId ?>&tipo=<?php echo $outroUsuarioTipo; ?>'">
        <?php } else { ?>
            <img src="/assets/uploads/fotos-tutores/<?php echo htmlspecialchars($destinatario['foto_perfil']); ?>" alt="Foto do destinatário" class="foto-perfil" onclick="location.href='/views/shared/perfil.php?id=<?php echo $outroUsuarioId ?>&tipo=<?php echo $outroUsuarioTipo; ?>'">
        <?php } ?>
        <span class="nome-destinatario"><?php echo htmlspecialchars($destinatario['nome']); ?></span>
    </div>
    <div id="agendamento_id" style="display: none;"><?php echo $agendamento_id; ?></div>
    <div id="id_remetente" style="display: none;"><?php echo $id_remetente ?></div>
    <div id="id_destinatario" style="display: none;"><?php echo $id_destinatario ?></div>
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
        mensagensDiv.innerHTML += `<div class="${msg.id_remetente === idRemetente ? 'mensagem-remetente' : 'mensagem-recebida'}">${msg.mensagem}</div>`;
    });

    window.onload = () => {
        const idAgendamento = document.getElementById('agendamento_id').textContent;

        fetch(`/includes/agendamentos/buscar-mensagens.php?agendamento_id=${idAgendamento}`)
            .then(response => response.json())
            .then(data => {
                const mensagensDiv = document.getElementById('mensagens');
                data.forEach(msg => {
                    const classe = msg.id_remetente == idRemetente ? 'mensagem-remetente' : 'mensagem-recebida';
                    mensagensDiv.innerHTML += `<div class="${classe}">${msg.mensagem}</div>`;
                });
            });
    };

    document.getElementById('mensagem').addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault(); // Previne a quebra de linha ao pressionar Enter
            enviarMensagem();
        }
    });

    // Enviar mensagem
    document.getElementById('enviar').addEventListener('click', () => {
        enviarMensagem();
    });

    function enviarMensagem() {
        const idRemetente = document.getElementById('id_remetente').textContent;
        const idDestinatario = document.getElementById('id_destinatario').textContent;
        const idAgendamento = document.getElementById('agendamento_id').textContent;
        const mensagem = document.getElementById('mensagem').value.trim();

        if (mensagem === "") {
            return;
        }

        // Exibir a mensagem localmente
        const mensagensDiv = document.getElementById('mensagens');
        mensagensDiv.innerHTML += `<div class="mensagem-remetente">${mensagem}</div>`;

        // Enviar a mensagem via socket
        socket.emit('chatMessage', {
            id_remetente: idRemetente,
            id_destinatario: idDestinatario,
            mensagem: mensagem,
            agendamento_id: idAgendamento
        });
        
        document.getElementById('mensagem').value = '';
    }
    
</script>