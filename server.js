const express = require('express');
const http = require('http');
const { Server } = require('socket.io');

const app = express();
const server = http.createServer(app);


const io = new Server(server, {
    cors: {
        origin: "http://localhost:8000", // Permitir apenas localhost:8000
        methods: ["GET", "POST"], // Permitir os métodos que você vai usar
        allowedHeaders: ["my-custom-header"], // Se necessário, adicione cabeçalhos específicos
        credentials: true
    }
});

let userSockets = {};  // Para mapear os sockets aos IDs de usuário

io.on('connection', (socket) => {
    console.log('Usuário conectado:', socket.id);

    // Armazenar o socket do usuário
    socket.on('register', (userId) => {
        userSockets[userId] = socket.id;  // Mapeando o usuário ao socket
        console.log(`Usuário ${userId} registrado com o socket ID ${socket.id}`);
    });

    // Enviar mensagem para o destinatário
    socket.on('chatMessage', (msg) => {
        console.log('Mensagem recebida no servidor:', msg);  // Log para garantir que está recebendo corretamente

        const { id_remetente, id_destinatario, mensagem, agendamento_id } = msg;  // Extrair os dados da mensagem

        console.log('Dados da mensagem:', { id_remetente, id_destinatario, mensagem, agendamento_id });

        const recipientSocket = userSockets[id_destinatario]; // Obter o socket do destinatário
        if (recipientSocket) {
            io.to(recipientSocket).emit('chatMessage', mensagem);  // Enviar a mensagem para o destinatário
        }

        console.log('Enviando mensagem para salvar:', {
            id_remetente: msg.id_remetente,
            id_destinatario: id_destinatario,
            mensagem: msg.mensagem,
            agendamento_id : agendamento_id
        });

        fetch('http://localhost:8000/includes/agendamentos/salvar-mensagens.php', {
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
                try {
                    const parsedData = JSON.parse(data);  // Parse o JSON se a resposta for válida
                    console.log(parsedData);
                } catch (error) {
                    console.error('Erro ao parsear JSON:', error);
                }
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
