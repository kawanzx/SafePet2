document.querySelectorAll('.btn-aceitar').forEach(button => {
    button.addEventListener('click', (e) => {
        e.stopPropagation(); // Evita o redirecionamento do card
        const agendamentoId = e.target.dataset.id;

        Swal.fire({
            title: 'Aceitar agendamento?',
            text: "Esta ação não pode ser desfeita.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, aceitar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Exemplo de requisição para aceitar o agendamento
                fetch('/backend/includes/agendamentos/aceitar-agendamento.php', {
                    method: 'POST',
                    body: JSON.stringify({ id: agendamentoId }),
                    headers: { 'Content-Type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Sucesso!', 'O agendamento foi aceito.', 'success')
                            .then(() => location.reload()); // Recarregar a página para refletir as mudanças
                    } else {
                        Swal.fire('Erro!', data.message || 'Ocorreu um erro ao aceitar o agendamento.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Erro!', 'Não foi possível processar a solicitação.', 'error');
                });
            }
        });
    });
});

document.querySelectorAll('.btn-recusar').forEach(button => {
    button.addEventListener('click', (e) => {
        e.stopPropagation(); // Evita o redirecionamento do card
        const agendamentoId = e.target.dataset.id;

        Swal.fire({
            title: 'Recusar agendamento?',
            text: "Você não pode aceitar novamente caso necessário.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, recusar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/backend/includes/agendamentos/recusar-agendamento.php', {
                    method: 'POST',
                    body: JSON.stringify({ id: agendamentoId }),
                    headers: { 'Content-Type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Sucesso!', 'O agendamento foi recusado.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Erro!', data.message || 'Ocorreu um erro ao recusar o agendamento.', 'error');
                    }
                })
                .catch(error => Swal.fire('Erro!', 'Não foi possível processar a solicitação.', 'error'));
            }
        });
    });
});

document.querySelectorAll('.btn-cancelar').forEach(button => {
    button.addEventListener('click', (e) => {
        e.stopPropagation(); 
        const agendamentoId = e.target.dataset.id;

        Swal.fire({
            title: 'Cancelar agendamento?',
            text: "Esta ação não pode ser desfeita.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, cancelar!',
            cancelButtonText: 'Voltar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/backend/includes/agendamentos/cancelar-agendamento.php', {
                    method: 'POST',
                    body: JSON.stringify({ id: agendamentoId }),
                    headers: { 'Content-Type': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Sucesso!', 'O agendamento foi cancelado.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Erro!', data.message || 'Ocorreu um erro ao cancelar o agendamento.', 'error');
                    }
                })
                .catch(error => Swal.fire('Erro!', 'Não foi possível processar a solicitação.', 'error'));
            }
        });
    });
});