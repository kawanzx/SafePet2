document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form-agendar-servico");
    const dataServico = document.getElementById("data-servico");
    const petCheckboxes = document.querySelectorAll(".pet-checkbox");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); 

        if (!dataServico.value) {
            Swal.fire({
                icon: "warning",
                title: "Campo Obrigatório",
                text: "Por favor, selecione uma data para o serviço.",
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
                    window.location.href = '/views/shared/agendamentos.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: data.message,
                });
            }
        })
    }
    
    // Obtém a data atual no formato YYYY-MM-DD
    const hoje = new Date();
    const ano = hoje.getFullYear();
    const mes = String(hoje.getMonth() + 1).padStart(2, '0'); 
    const dia = String(hoje.getDate()).padStart(2, '0');
    const dataAtual = `${ano}-${mes}-${dia}`;

    const inputData = document.getElementById('data-servico');
    inputData.min = dataAtual;
});