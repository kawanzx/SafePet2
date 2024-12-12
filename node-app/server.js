const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const app = express();
const server = http.createServer(app);
const mysql = require('mysql2');
require('dotenv').config({ path: '../backend/includes/.env' });
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
    host: process.env.MYSQL_HOST,
    user: process.env.MYSQL_USERNAME,
    password: process.env.MYSQL_PASSWORD,
    database: process.env.MYSQL_DATABASE,
});

db.connect((err) => {
    if (err) {
        console.error('Erro ao conectar ao banco de dados:', err);
    }
});

function notificarAceiteAgendamento(agendamentoId, tutorId, cuidadorId, petNome) {
    const mensagem = `O agendamento para o pet ${petNome} foi aceito!`;
    const SISTEMA_ID = 0;

    const tutorSocket = userSockets[tutorId];
    if (tutorSocket) {
        io.to(tutorSocket).emit('receiveNotification', mensagem);

        io.to(tutorSocket).emit('newNotification', {
            tipo_notificacao: 'agendamento_status',
            mensagem: mensagem,
            remetente: SISTEMA_ID
        });
    }

    // Enviar notificação para o tutor via API
    fetch('http://localhost:8000/backend/includes/notificacoes/criar-notificacoes.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id_remetente: SISTEMA_ID,
            id_destinatario: tutorId,
            tipo_remetente: 'sistema',
            mensagem: mensagem,
            agendamento_id: agendamentoId,
            tipo_notificacao: 'agendamento_status'
        })
    })
        .then(response => response.json())
        .then(data => {
            const updateQuery = `UPDATE agendamentos SET notificacao_enviada = 1 WHERE id = ?`;
            db.query(updateQuery, [agendamentoId], (err, result) => {
                if (err) {
                    console.error('Erro ao atualizar agendamento:', err);
                }
            });
        })
        .catch(error => console.error('Erro na requisição:', error));
}

// Evento para quando o status do agendamento for atualizado para "aceito"
function atualizarStatusAgendamento(agendamentoId, novoStatus) {
    const query = `UPDATE agendamentos SET status = ? WHERE id = ?`;
    db.query(query, [novoStatus, agendamentoId], (err, result) => {
        if (err) {
            console.error('Erro ao atualizar status do agendamento:', err);
            return;
        }

        // Verificar se o status é "aceito" e disparar a notificação
        if (novoStatus === 'aceito') {
            const selectQuery = `SELECT a.id AS agendamento_id, a.tutor_id, a.cuidador_id, p.nome AS pet_nome
                FROM agendamentos a
                JOIN pets p ON a.pet_id = p.id
                WHERE a.id = ?`;
            db.query(selectQuery, [agendamentoId], (err, results) => {
                if (err) {
                    console.error('Erro ao buscar detalhes do agendamento aceito:', err);
                    return;
                }
                if (results.length > 0) {
                    const agendamento = results[0];
                    notificarAceiteAgendamento(
                        agendamento.agendamento_id,
                        agendamento.tutor_id,
                        agendamento.cuidador_id,
                        agendamento.pet_nome
                    );
                }
            });
        }
    });
}

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


let userSockets = {};
io.on('connection', (socket) => {
    socket.on('register', (userId) => {
        userSockets[userId] = socket.id;
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
        const { id_remetente, id_destinatario, mensagem, agendamento_id, nome_remetente, tipo_remetente } = msg;  // Extrair os dados da mensagem
        const recipientSocket = userSockets[id_destinatario]; // Obter o socket do destinatário
        if (recipientSocket) {
            io.to(recipientSocket).emit('chatMessage', { mensagem: mensagem, id_remetente: id_remetente, nome_remetente });  // Enviar a mensagem para o destinatário

            io.to(recipientSocket).emit('newNotification', {
                tipo_notificacao: 'chat',
                mensagem: `Nova mensagem de ${nome_remetente}`,
                remetente: id_remetente
            });
        }

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
            .catch(error => console.error('Erro ao salvar mensagem:', error));
    });
    socket.on('disconnect', () => {
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