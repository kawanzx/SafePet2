document.addEventListener('DOMContentLoaded', function () {
    const handleAgendamentoAction = (buttonClass, url, successMessage, errorMessage) => {
        document.querySelectorAll(buttonClass).forEach(button => {
            button.addEventListener('click', function () {
                const agendamentoId = this.getAttribute('data-id');
                
                if (confirm(`Você tem certeza que deseja ${successMessage.toLowerCase()} este agendamento?`)) {
                    fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: agendamentoId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(successMessage);
                            location.reload();
                        } else {
                            alert(errorMessage);
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Ocorreu um erro ao processar o pedido.');
                    });
                }
            });
        });
    };

    handleAgendamentoAction('.btn-cancelar', '../../includes/agendamentos/cancelar-agendamento.php', 'Agendamento cancelado com sucesso!', 'Erro ao cancelar o agendamento. Tente novamente mais tarde.');
    handleAgendamentoAction('.btn-aceitar', '../../includes/agendamentos/aceitar-agendamento.php', 'Agendamento foi aceito com sucesso!', 'Ocorreu um erro ao tentar aceitar o atendimento. Tente novamente mais tarde.');
    handleAgendamentoAction('.btn-recusar', '../../includes/agendamentos/recusar-agendamento.php', 'Agendamento recusado com sucesso!', 'Ocorreu um erro ao tentar recusar o atendimento. Tente novamente mais tarde.');
});

