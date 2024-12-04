const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const app = express();
const server = http.createServer(app);
const mysql = require('mysql2');
const io = new Server(server, {
    cors: {
        origin: "http://localhost:8000", // Permitir apenas localhost:8000
        methods: ["GET", "POST"], // Permitir os métodos que você vai usar
        allowedHeaders: ["my-custom-header"], // Se necessário, adicione cabeçalhos específicos
        withCredentials: true,
        credentials: true

    }
});
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'safepet'
});

function verificarAgendamentos() {
    const dataAtual = new Date().toISOString().slice(0, 10); // Obtém a data atual no formato YYYY-MM-DD
    const SISTEMA_ID = 0;

    const query = `
        SELECT a.id AS agendamento_id, a.data_servico, a.hora_inicio, a.hora_fim, t.id AS tutor_id, c.id AS cuidador_id, p.nome AS pet_nome 
        FROM agendamentos a
        JOIN tutores t ON a.tutor_id = t.id
        JOIN cuidadores c ON a.cuidador_id = c.id
        JOIN pets p ON a.pet_id = p.id
        WHERE a.status = 'aceito' AND a.data_servico = ?`;

    db.query(query, [dataAtual], (err, results) => {
        if (err) {
            console.error('Erro ao buscar agendamentos:', err);
            return;
        }

        results.forEach(agendamento => {
            const mensagem = `Você tem um agendamento hoje com o pet ${agendamento.pet_nome}!`;
            const tutorSocket = userSockets[agendamento.tutor_id];
            const cuidadorSocket = userSockets[agendamento.cuidador_id];

            if (tutorSocket) {
                io.to(tutorSocket).emit('receiveNotification', mensagem);
            }
            if (cuidadorSocket) {
                io.to(cuidadorSocket).emit('receiveNotification', mensagem);
            }

            console.log('JSON gerado verificar agendamento:', JSON.stringify({
                tipo_notificacao: 'verificar_agendamento'
            }));

            // Enviar notificação para o tutor
            fetch('http://localhost:8000/backend/includes/notificacoes/criar-notificacoes.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_remetente: SISTEMA_ID,
                    id_destinatario: agendamento.tutor_id,
                    tipo_remetente: 'sistema',
                    mensagem: mensagem,
                    agendamento_id: agendamento.agendamento_id,
                    tipo_notificacao: 'verificar_agendamento'
                })
            }).catch(error => console.error('Erro ao criar notificação para tutor:', error));

            // Enviar notificação para o cuidador
            fetch('http://localhost:8000/backend/includes/notificacoes/criar-notificacoes.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id_remetente: SISTEMA_ID,
                    id_destinatario: agendamento.cuidador_id,
                    tipo_remetente: 'sistema',
                    mensagem: mensagem,
                    agendamento_id: agendamento.agendamento_id,
                    tipo_notificacao: 'verificar_agendamento'
                })
            }).catch(error => console.error('Erro ao criar notificação para cuidador:', error));
        });
    });
}

verificarAgendamentos();

// Verificar agendamentos a cada 1 hora
setInterval(verificarAgendamentos, 3600000);


let userSockets = {};  // Para mapear os sockets aos IDs de usuário
io.on('connection', (socket) => {
    console.log('Usuário conectado:', socket.id);
    // Armazenar o socket do usuário
    socket.on('register', (userId) => {
        userSockets[userId] = socket.id;  // Mapeando o usuário ao socket
        console.log(`Usuário ${userId} registrado com o socket ID ${socket.id}`);
    });

    // Notificar os usuários em tempo real
    socket.on('sendNotification', (notification) => {
        const { userId, mensagem } = notification;
        const recipientSocket = userSockets[userId];
        if (recipientSocket) {
            io.to(recipientSocket).emit('receiveNotification', mensagem);
        }
    });

    // Enviar mensagem para o destinatário
    socket.on('chatMessage', (msg) => {
        console.log('Mensagem recebida no servidor:', msg);  // Log para garantir que está recebendo corretamente
        const { id_remetente, id_destinatario, mensagem, agendamento_id, nome_remetente, tipo_remetente } = msg;  // Extrair os dados da mensagem
        console.log('Dados da mensagem:', { id_remetente, id_destinatario, mensagem, agendamento_id, nome_remetente, tipo_remetente });
        const recipientSocket = userSockets[id_destinatario]; // Obter o socket do destinatário
        if (recipientSocket) {
            io.to(recipientSocket).emit('chatMessage', { mensagem: mensagem, id_remetente: id_remetente, nome_remetente });  // Enviar a mensagem para o destinatário

            io.to(recipientSocket).emit('newNotification', {
                tipo_notificacao: 'chat',
                mensagem: `Nova mensagem de ${nome_remetente}`,
                remetente: id_remetente
            });
        }
        console.log('JSON gerado chat:', JSON.stringify({
            id_remetente: msg.id_remetente,
            id_destinatario: id_destinatario,
            tipo_remetente: tipo_remetente,
            mensagem: `Nova mensagem de ${msg.nome_remetente}`,
            agendamento_id: agendamento_id,
            tipo_notificacao: 'chat'
        }));
        // Enviar requisição ao PHP para criar notificação no banco
        fetch('http://localhost:8000/backend/includes/notificacoes/criar-notificacoes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id_remetente: msg.id_remetente,
                id_destinatario: id_destinatario,
                tipo_remetente: tipo_remetente,
                mensagem: `Nova mensagem de ${msg.nome_remetente}`,
                agendamento_id: agendamento_id,
                tipo_notificacao: 'chat'
            })

        });

        console.log('Enviando mensagem para salvar:', {
            id_remetente: msg.id_remetente,
            id_destinatario: id_destinatario,
            mensagem: msg.mensagem,
            agendamento_id: agendamento_id
        });
        fetch('http://localhost:8000/backend/includes/agendamentos/salvar-mensagens.php', {
            method: 'POST',
            body: JSON.stringify({
                id_remetente: msg.id_remetente,
                id_destinatario: id_destinatario,
                mensagem: msg.mensagem,
                agendamento_id: agendamento_id
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                console.log('Resposta recebida:', data);
            })
            .catch(error => console.error('Erro ao salvar mensagem:', error));
    });
    socket.on('disconnect', () => {
        console.log('Usuário desconectado:', socket.id);
        // Remover o socket do usuário
        for (let userId in userSockets) {
            if (userSockets[userId] === socket.id) {
                delete userSockets[userId];
                break;
            }
        }
    });
});
server.listen(3000, () => {
    console.log('Servidor WebSocket rodando na porta 3000');
});
