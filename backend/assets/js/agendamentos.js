document.querySelectorAll('.btn-aceitar').forEach(button => {
    button.addEventListener('click', (e) => {
        e.stopPropagation();
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
                fetch('/backend/includes/agendamentos/aceitar-agendamento.php', {
                    method: 'POST',
                    body: JSON.stringify({ id: agendamentoId }),
                    headers: { 'Content-Type': 'application/json' }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Sucesso!', 'O agendamento foi aceito.', 'success')
                                .then(() => location.reload()); 
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
        e.stopPropagation(); 
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

window.concluirAgendamento = concluirAgendamento;
async function concluirAgendamento(agendamentoId) {

    Swal.fire({
        title: 'Você tem certeza que deseja concluir este agendamento?',
        text: "Esta ação não pode ser desfeita.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, aceitar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/backend/includes/agendamentos/concluir-agendamento.php', {
                method: 'POST',
                body: JSON.stringify({ agendamento_id: agendamentoId }),
                headers: { 'Content-Type': 'application/json' }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Sucesso!', 'Agendamento concluído com sucesso!', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Erro!', data.message || 'Erro ao concluir o agendamento: ' + result.message, 'error');
                    }
                })
                .catch(error => Swal.fire('Erro!', 'Erro inesperado ao concluir o agendamento.', error));

        }
    });
}


let selectedRating = 0; 

document.querySelectorAll('.btn-avaliar').forEach(button => {
    button.addEventListener('click', function () {
        const agendamentoId = this.getAttribute('data-id');
        const popup = document.getElementById('popup-' + agendamentoId);
        popup.classList.remove('hidden');
    });
});

document.querySelectorAll('.close').forEach(closeButton => {
    closeButton.addEventListener('click', function () {
        const popup = this.closest('.popup');
        popup.classList.add('hidden');
    });
});

document.querySelectorAll('.rating').forEach((ratingElement) => {
    const agendamentoId = ratingElement.closest('form').getAttribute('data-agendamento-id');
    const stars = ratingElement.querySelectorAll('i');
    const ratingInput = document.getElementById('ratingValue-' + agendamentoId);

    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            const selectedRating = parseInt(star.getAttribute('data-value'), 10);

            stars.forEach(s => s.classList.remove('active'));

            for (let i = 0; i < selectedRating; i++) {
                stars[i].classList.add('active');
            }

            if (ratingInput) {
                ratingInput.value = selectedRating;
            }
        });
    });
});

document.querySelectorAll('.popup form').forEach(form => {
    form.addEventListener('submit', function (event) {
        event.preventDefault();

        const agendamentoId = this.closest('.popup').getAttribute('data-id');
        const tutorId = this.closest('.popup').getAttribute('data-id-tutor');
        const cuidadorId = this.closest('.popup').getAttribute('data-id-cuidador');
        const commentField = document.getElementById('comments-' + agendamentoId);
        const comments = commentField ? commentField.value.trim() : '';
        const rating = document.getElementById('ratingValue-' + agendamentoId).value;

        if (rating > 0) {
            fetch('/backend/includes/agendamentos/avaliar-agendamento.php', {
                method: 'POST',
                body: JSON.stringify({
                    rating: rating,
                    comments: comments,
                    agendamento_id: agendamentoId,
                    tutor_id: tutorId,
                    cuidador_id: cuidadorId
                }),
                headers: { 'Content-Type': 'application/json' }
            }).then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        Swal.fire('Sucesso', data.mensagem, 'success');
                    } else {
                        Swal.fire('Erro', data.mensagem, 'error');
                    }
                }).finally(() => {
                    document.getElementById('popup-' + agendamentoId).classList.add('hidden');
                });
        } else {
            Swal.fire('Erro', 'Por favor, selecione uma nota.', 'error');
        }
    });
});

