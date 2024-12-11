const menuToggle = document.querySelector('.menu-toggle');
const navList = document.querySelector('.nav-list');
const socket = io('http://localhost:3000'); 

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
        const isDropdownOpen = dropdown.style.display === 'block';
        dropdown.style.display = isDropdownOpen ? 'none' : 'block';
        if (!isDropdownOpen) {
            carregarNotificacoes(); // Recarregar notificações apenas ao abrir
        }
    });

    document.addEventListener('click', (e) => {
        if (!notificacaoIcon.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    const userId = document.body.dataset.userId;
    socket.emit('register', userId);

    socket.on('receiveNotification', (mensagem) => {
        Swal.fire("Nova notificação: " + mensagem);
        carregarNotificacoes(); 
    });

    socket.on('newNotification', (data) => {
        const { tipo_notificacao, mensagem } = data;
        if (tipo_notificacao === 'chat') {
            mostrarNotificacao(mensagem);
            criarNotificacoes();
        }
        if (tipo_notificacao === 'agendamento_status') {
            mostrarNotificacao(mensagem);
        }
        carregarNotificacoes();
    });

    function criarNotificacoes(){
        fetch('/backend/includes/notificacoes/criar-notificacoes.php')
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
        fetch('/backend/includes/notificacoes/buscar-notificacoes.php')
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
                        li.addEventListener('click', () => marcarComoLida(notificacao.id, notificacao.agendamento_id, notificacao.remetente_id, notificacao.tipo_remetente, notificacao.tipo_notificacao));
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

    function marcarComoLida(id, agendamentoId, remetenteId, tipo_remetente, tipo_notificacao) {
        const bodyData = new URLSearchParams({
            id: id,
            agendamento_id: agendamentoId,
            remetente_id: remetenteId,
            tipo_remetente: tipo_remetente,
            tipo_notificacao: tipo_notificacao
        });
    
        fetch('/backend/includes/notificacoes/marcar-notificacoes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: bodyData.toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sucesso') {
                carregarNotificacoes();
                switch (data.tipo_notificacao) {
                    case 'chat':
                        window.location.href = `../../views/shared/chat.php?agendamento_id=${agendamentoId}&user_id=${remetenteId}&tipo=${tipo_remetente}`;
                        break;
                    case 'verificar_agendamento':
                        window.location.href = `../../views/shared/agendamentos.php`;
                        break;
                    case 'agendamento_status':
                        window.location.href = `../../views/shared/agendamentos.php`;
                        break;
                    default:
                        console.warn("Tipo de notificação desconhecido:", data.tipo_notificacao);
                        break;
                }
            } else {
                console.error("Erro ao marcar notificação como lida:", data.message);
            }
        })
        .catch(error => console.error("Erro na requisição:", error));
    }
    
    carregarNotificacoes();

    setInterval(() => {
        if (document.getElementById('notificacoes-dropdown').style.display === 'block') {
            carregarNotificacoes();
        }
    }, 3000);
});

