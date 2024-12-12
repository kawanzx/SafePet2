document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form-agendar-servico");
    const petCheckboxes = document.querySelectorAll(".pet-checkbox");
    const horaInicioSelect = document.getElementById("hora-inicio");
    const horaFimSelect = document.getElementById("hora-fim");

    let diasHorarios; 

    fetch(`/backend/includes/disponibilidade-cuidador.php?id=${new URLSearchParams(window.location.search).get('id')}`)
        .then(response => response.json())
        .then(data => {
            diasHorarios = data; 
            iniciarFlatpickr(Object.keys(data)); 
        });

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        if (!document.getElementById("data-servico").value) {
            Swal.fire({
                icon: "warning",
                title: "Campo Obrigatório",
                text: "Por favor, selecione uma data para o serviço.",
            });
            return;
        }

        if (!horaInicioSelect.value || !horaFimSelect.value) {
            Swal.fire({
                icon: "warning",
                title: "Campo Obrigatório",
                text: "Por favor, selecione a hora de início e término do serviço.",
            });
            return;
        }

        if (horaInicioSelect.value >= horaFimSelect.value) {
            Swal.fire({
                icon: "warning",
                title: "Horário Inválido",
                text: "A hora de término deve ser maior que a hora de início.",
            });
            return;
        }

        const petsSelecionados = Array.from(petCheckboxes).some((checkbox) => checkbox.checked);
        if (!petsSelecionados) {
            Swal.fire({
                icon: "warning",
                title: "Nenhum Pet Selecionado",
                text: "Por favor, selecione pelo menos um pet para o agendamento.",
            });
            return;
        }

        submitForm();
    });

    function iniciarFlatpickr(diasPermitidos) {
        flatpickr("#data-servico", {
            minDate: "today",
            dateFormat: "d-m-Y",
            inline: true,
            locale: "pt",
            disable: [
                function (date) {
                    return !diasPermitidos.includes(date.toLocaleDateString('pt-BR', { weekday: 'long' }));
                }
            ],
            onChange: function (selectedDates, dateStr) {
                const [dia, mes, ano] = dateStr.split('-');
                const dataSelecionada = new Date(`${ano}-${mes}-${dia}T00:00:00`);
                const diaSemana = dataSelecionada.toLocaleDateString('pt-BR', { weekday: 'long' });

                exibirHorariosDisponiveis(diaSemana, diasHorarios);
            }
        });
    }

    // Função para exibir os horários disponíveis
    function exibirHorariosDisponiveis(diaSemana, diasHorarios) {
        horaInicioSelect.innerHTML = ""; // Limpar selects
        horaFimSelect.innerHTML = "";

        if (diasHorarios[diaSemana]) {
            const { hora_inicio, hora_fim } = diasHorarios[diaSemana];
            const inicio = new Date(`1970-01-01T${hora_inicio.substring(0, 5)}:00`);
            const fim = new Date(`1970-01-01T${hora_fim.substring(0, 5)}:00`);

            while (inicio <= fim) {
                const horaFormatada = inicio.toTimeString().substring(0, 5);
                const optionInicio = new Option(horaFormatada, horaFormatada);
                horaInicioSelect.appendChild(optionInicio);
                const optionFim = new Option(horaFormatada, horaFormatada);
                horaFimSelect.add(optionFim);

                // Incremento de 30 minutos
                inicio.setHours(inicio.getHours() + 1);
            }
        } else {
            const option = new Option("Nenhum horário disponível", "");
            horaInicioSelect.add(option);
            horaFimSelect.add(option);
        }
    }

    // Função para submeter o formulário
    function submitForm() {
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: data.message,
                    }).then(() => {
                        window.location.href = '/backend/views/shared/agendamentos.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: data.message,
                    });
                }
            });
    }
});
