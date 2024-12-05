// Editar Pet
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.editar-btn').forEach(button => {
        button.addEventListener('click', function () {
            const petDiv = this.closest('.pet');

            // Armazenar os valores atuais dos textos em atributos data nos inputs
            petDiv.querySelector('.nomePetInput').setAttribute('data-original', petDiv.querySelector('.nomePetInput').value);
            petDiv.querySelector('.especieInput').setAttribute('data-original', petDiv.querySelector('.especieInput').value);
            petDiv.querySelector('.racaInput').setAttribute('data-original', petDiv.querySelector('.racaInput').value);
            petDiv.querySelector('.idadeInput').setAttribute('data-original', petDiv.querySelector('.idadeInput').value);
            petDiv.querySelector('.sexoInput').setAttribute('data-original', petDiv.querySelector('.sexoInput').value);
            petDiv.querySelector('.pesoInput').setAttribute('data-original', petDiv.querySelector('.pesoInput').value);
            petDiv.querySelector('.castradoInput').setAttribute('data-original', petDiv.querySelector('.castradoInput').value);
            petDiv.querySelector('.descricaoInput').setAttribute('data-original', petDiv.querySelector('.descricaoInput').value);

            // Ocultar os textos e exibir os inputs
            petDiv.querySelectorAll('.nomePetText, .especieText, .racaText, .idadeText, .sexoText, .pesoText, .castradoText, .descricaoText').forEach(text => {
                text.style.display = 'none';
            });
            petDiv.querySelectorAll('.nomePetInput, .especieInput, .racaInput, .idadeInput, .sexoInput, .pesoInput, .castradoInput, .descricaoInput').forEach(input => {
                input.style.display = 'block';
            });

            // Alternar os botões
            this.style.display = 'none';
            petDiv.querySelector('.salvar-btn').style.display = 'inline-block';
            petDiv.querySelector('.cancelar-btn').style.display = 'inline-block';
        });
    });

    document.querySelectorAll('.salvar-btn').forEach(button => {
        button.addEventListener('click', function () {
            const petDiv = this.closest('.pet');
            const petId = petDiv.getAttribute('data-pet-id'); // Supondo que você tenha um atributo data-id com o ID do pet

            // Obter os valores dos inputs
            const nome = petDiv.querySelector('.nomePetInput').value;
            const especie = petDiv.querySelector('.especieInput').value;
            const raca = petDiv.querySelector('.racaInput').value;
            const idade = petDiv.querySelector('.idadeInput').value;
            const sexo = petDiv.querySelector('.sexoInput').value;
            const peso = petDiv.querySelector('.pesoInput').value;
            const castrado = petDiv.querySelector('.castradoInput').value;
            const descricao = petDiv.querySelector('.descricaoInput').value;

            // Enviar os dados via AJAX
            const formData = new FormData();
            formData.append('petId', petId);
            formData.append('nome', nome);
            formData.append('especie', especie);
            formData.append('raca', raca);
            formData.append('idade', idade);
            formData.append('sexo', sexo);
            formData.append('peso', peso);
            formData.append('castrado', castrado);
            formData.append('descricao', descricao);

            fetch('/backend/includes/perfil/editar-pet.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    if (data.includes("sucesso")) {
                        Swal.fire({
                            icon: "success",
                            text: "Dados atualizados com sucesso!",
                          });
                        // Atualizar os textos com os novos valores
                        petDiv.querySelector('.nomePetText').textContent = nome;
                        petDiv.querySelector('.especieText').textContent = especie === 'cachorro' ? 'Cachorro' : 'Gato';
                        petDiv.querySelector('.racaText').textContent = raca;
                        petDiv.querySelector('.idadeText').textContent = idade;
                        petDiv.querySelector('.sexoText').textContent = sexo;
                        petDiv.querySelector('.pesoText').textContent = peso;
                        petDiv.querySelector('.castradoText').textContent = castrado;
                        petDiv.querySelector('.descricaoText').textContent = descricao;

                        // Ocultar os inputs e exibir os textos
                        petDiv.querySelectorAll('.nomePetText, .especieText, .racaText, .idadeText, .sexoText, .pesoText, .castradoText, .descricaoText').forEach(text => {
                            text.style.display = 'inline';
                        });
                        petDiv.querySelectorAll('.nomePetInput, .especieInput, .racaInput, .idadeInput, .sexoInput, .pesoInput, .castradoInput, .descricaoInput, .fotoPetInput').forEach(input => {
                            input.style.display = 'none';
                        });

                        // Alternar os botões
                        petDiv.querySelector('.salvar-btn').style.display = 'none';
                        petDiv.querySelector('.editar-btn').style.display = 'inline-block';
                        petDiv.querySelector('.cancelar-btn').style.display = 'none';
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Erro ao atualizar os dados!",
                            text: data,
                          });
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    Swal.fire({
                        icon: "error",
                        title: "Erro na comunicação com o servidor.",
                      });
                });
        });
    });


    document.querySelectorAll('.cancelar-btn').forEach(button => {
        button.addEventListener('click', function () {
            const petDiv = this.closest('.pet');

            // Restaurar os valores originais dos inputs
            petDiv.querySelectorAll('.nomePetInput, .especieInput, .racaInput, .idadeInput, .sexoInput, .pesoInput, .castradoInput, .descricaoInput').forEach(input => {
                input.value = input.getAttribute('data-original');
            });

            // Ocultar os inputs e exibir os textos
            petDiv.querySelectorAll('.nomePetText, .especieText, .racaText, .idadeText, .sexoText, .pesoText, .castradoText, .descricaoText').forEach(text => {
                text.style.display = 'inline';
            });
            petDiv.querySelectorAll('.nomePetInput, .especieInput, .racaInput, .idadeInput, .sexoInput, .pesoInput, .castradoInput, .descricaoInput, .fotoPetInput').forEach(input => {

                input.style.display = 'none';
            });

            // Alternar os botões
            petDiv.querySelector('.salvar-btn').style.display = 'none';
            petDiv.querySelector('.cancelar-btn').style.display = 'none';
            petDiv.querySelector('.editar-btn').style.display = 'inline-block';
        });
    });


    document.querySelectorAll('.fotoPetImg').forEach(image => {
        image.addEventListener('click', function () {
            const petDiv = this.closest('.pet');
            const fileInput = petDiv.querySelector('.fotoPetInput');
            fileInput.click();
        });
    });

    //Excluir pet
    document.querySelectorAll('.excluir-btn').forEach(button => {
        button.addEventListener('click', function () {
            Swal.fire({
                title: "Tem certeza que deseja excluir esse pet?",
                text: "Não será possível reverter essa ação!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim, deletar pet",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {


                    const petElement = this.closest('.pet');
                    const petId = petElement.getAttribute('data-pet-id');

                    // Enviar requisição AJAX para excluir o pet
                    fetch('../../../includes/perfil/excluir-pet.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'petId=' + encodeURIComponent(petId)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: "Excluído!",
                                    text: "O pet foi apagado do sistema.",
                                    icon: "success"
                                });
                                petElement.remove(); // Remove o elemento da página
                            } else {
                                alert('Erro: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            Swal.fire({
                                title: "Ocorreu um erro ao tentar excluir o pet.",
                                icon: "error"
                            });
                        });
                }
            });
        });
    });

    document.querySelector('.cad-pet').addEventListener('click', function (event) {
        event.preventDefault(); // Impede o envio padrão do formulário
    
        const form = document.getElementById('form-pet');
        const formData = new FormData(form); // Cria o FormData com os dados do formulário

        if (!form.checkValidity()) {
            form.reportValidity(); // Exibe mensagens de erro do HTML5
            return; // Cancela o envio se o formulário for inválido
        }
    
        fetch('/backend/includes/perfil/cadastro-pet.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json()) // Recebe a resposta em JSON
            .then(data => {
                console.log(data);
                if (data.status === "sucesso") {
                    Swal.fire({
                        icon: "success",
                        text: data.message,
                    });
                    form.reset(); // Limpa o formulário após o sucesso
                    document.getElementById("preview").style.display = "none"; // Esconde a pré-visualização da imagem
                } else {
                    Swal.fire({
                        icon: "error",
                        title: data.message,
                    });
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erro na comunicação com o servidor.",
                });
            });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const fotoInput = document.getElementById('foto');
        const preview = document.getElementById('preview');

        if (fotoInput && preview) {
            fotoInput.addEventListener('change', function (event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.src = '';
                    preview.style.display = 'none';
                }
            });
        }
    });

    document.querySelectorAll('.fotoPetInput').forEach(function (input) {
        input.addEventListener('change', function (event) {
            const petId = event.target.id.split('-')[1]; // Extrai o ID do pet do input file
            const preview = document.getElementById('preview-' + petId); // Obtém o elemento de pré-visualização da imagem
            const file = event.target.files[0]; // O arquivo selecionado
    
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result; // Atualiza o src da imagem
                };
                reader.readAsDataURL(file); // Lê o arquivo como uma URL base64
    
                // Criar um objeto FormData para enviar o arquivo via AJAX
                let formData = new FormData();
                formData.append('foto', file); // Anexa o arquivo de imagem
                formData.append('pet_id', petId); // Anexa o ID do pet
    
                // Enviar a imagem para o servidor via AJAX
                fetch('/backend/includes/perfil/atualizar_foto.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json()) // Espera resposta JSON
                    .then(data => {
                        if (data.sucesso) {
                            console.log(data.mensagem);
                            Swal.fire({
                                icon: "success",
                                title: "Sucesso!",
                                text: data.mensagem
                            });
                            // Atualiza o preview com o novo caminho da imagem
                            preview.src = '/backend/assets/uploads/fotos-pets/' + file.name + '?' + new Date().getTime();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Erro!",
                                text: data.mensagem
                            }).then(() => {
                                location.reload();
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao atualizar a foto:', error);
                        alert('Houve um erro ao atualizar a foto.');
                    });
            } else {
                preview.src = '/backend/assets/uploads/fotos-pets/default-image.png'; // Imagem padrão se nenhum arquivo for selecionado
            }
        });
    });
});