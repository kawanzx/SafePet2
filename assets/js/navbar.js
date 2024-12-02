const menuToggle = document.querySelector('.menu-toggle');
const navList = document.querySelector('.nav-list');
const socket = io('http://localhost:3000'); // Mova a inicialização do socket para o topo

menuToggle.addEventListener('click', () => {
    navList.classList.toggle('show');
    menuToggle.classList.toggle('open');
});

document.addEventListener("DOMContentLoaded", function () {
    const notificacaoIcon = document.getElementById('notificacao-icon');
    const listaNotificacoes = document.getElementById('lista-notificacoes');
    const contadorNotificacoes = document.getElementById('contador-notificacoes');
    const dropdown = document.getElementById('notificacoes-dropdown');
    const semNotificacoes = document.getElementById('sem-notificacoes');

    notificacaoIcon.addEventListener('click', () => {
        dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
        

    });

    document.addEventListener('click', (e) => {
        if (!notificacaoIcon.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    const userId = document.body.dataset.userId; // Pegue o ID do usuário do DOM
    socket.emit('register', userId);

    // Escutar notificações recebidas
    socket.on('receiveNotification', (mensagem) => {
        alert("Nova notificação: " + mensagem); // Exemplo simples de alerta
        carregarNotificacoes(); // Atualiza a lista de notificações
    });

    socket.on('newNotification', (data) => {
        const { tipo, mensagem } = data;
        if (tipo === 'chat') {
            mostrarNotificacao(mensagem);
            criarNotificacoes();
        }
        carregarNotificacoes();
    });

    function criarNotificacoes(){
        fetch('../../includes/notificacoes/criar-notificacoes.php')
            .then(response => response.json())
            .then(data => {
                listaNotificacoes.innerHTML = '';
                if (data.length > 0) {
                    contadorNotificacoes.textContent = data.length;
                    semNotificacoes.classList.add('hidden');
                    data.forEach(notificacao => {
                        const li = document.createElement('li');
                        li.textContent = notificacao.mensagem;
                        li.addEventListener('click', () => marcarComoLida(notificacao.id));
                        listaNotificacoes.appendChild(li);
                    });
                } else {
                    contadorNotificacoes.textContent = '';
                    semNotificacoes.classList.remove('hidden');
                }
            });
    }

    function carregarNotificacoes() {
        fetch('../../includes/notificacoes/buscar-notificacoes.php')
            .then(response => response.json())
            .then(data => {
                console.log(data);
                listaNotificacoes.innerHTML = '';
                if (data.length > 0) {
                    contadorNotificacoes.textContent = data.length;
                    semNotificacoes.classList.add('hidden');
                    data.forEach(notificacao => {
                        const li = document.createElement('li');
                        li.textContent = notificacao.mensagem;
                        li.addEventListener('click', () => marcarComoLida(notificacao.id, notificacao.agendamento_id, notificacao.remetente_id, notificacao.tipo_remetente));
                        listaNotificacoes.appendChild(li);
                    });
                } else {
                    contadorNotificacoes.textContent = '';
                    semNotificacoes.classList.remove('hidden');
                }
            })
            .catch(error => console.error("Erro ao carregar notificações:", error));
    }

    function mostrarNotificacao(mensagem) {
        const notificacao = document.createElement('div');
        notificacao.className = 'notificacao-popup';
        notificacao.innerText = mensagem;
        document.body.appendChild(notificacao);
        setTimeout(() => document.body.removeChild(notificacao), 5000);
    }

    function marcarComoLida(id, agendamentoId, remetenteId, tipo_remetente) {
        const bodyData = new URLSearchParams({
            id: id,
            agendamento_id: agendamentoId,
            remetente_id: remetenteId,
            tipo_remetente: tipo_remetente
        });

        fetch('../../includes/notificacoes/marcar-notificacoes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: bodyData.toString()
        }).then(response => response.json())
          .then(data => {
            if (data.status === 'sucesso') {
                carregarNotificacoes();
                window.location.href = `../../views/shared/chat.php?agendamento_id=${agendamentoId}&user_id=${remetenteId}&tipo=${tipo_remetente}`;
            } else {
                console.error("Erro ao marcar notificação como lida:", data.message);
            }
        }).catch(error => console.error("Erro na requisição:", error));
    }
    

    setInterval(() => {
        if (document.getElementById('notificacoes-dropdown').style.display === 'block') {
            carregarNotificacoes();
        }
    }, 5000);
});

