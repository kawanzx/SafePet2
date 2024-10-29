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
            const fotoInput = petDiv.querySelector('input[type="file"]');
            const fotoAtual = petDiv.querySelector('.fotoPetAtual').value;

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

            if (fotoInput && fotoInput.files.length > 0) {
                formData.append('foto', fotoInput.files[0]);
            } else {
                // Se não houver uma nova foto, adicione o valor da foto atual (se existir)
                formData.append('foto_atual', fotoAtual);
            }

            fetch('/includes/perfil/editar-pet.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    if (data.includes("sucesso")) {
                        // Atualizar os textos com os novos valores
                        petDiv.querySelector('.nomePetText').textContent = nome;
                        petDiv.querySelector('.especieText').textContent = especie === 'cachorro' ? 'Cachorro' : 'Gato';
                        petDiv.querySelector('.racaText').textContent = raca;
                        petDiv.querySelector('.idadeText').textContent = idade;
                        petDiv.querySelector('.sexoText').textContent = sexo;
                        petDiv.querySelector('.pesoText').textContent = peso;
                        petDiv.querySelector('.castradoText').textContent = castrado;
                        petDiv.querySelector('.descricaoText').textContent = descricao;

                        // Atualizar a imagem caso uma nova foto tenha sido enviada
                        if (fotoInput && fotoInput.files.length > 0) {
                            const newImageUrl = URL.createObjectURL(fotoInput.files[0]);
                            petDiv.querySelector('.fotoPet img').src = newImageUrl;
                        }

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
                        alert('Erro ao atualizar os dados: ' + data);
                    }
                })
                .catch(error => {
                    console.error('Erro na requisição:', error);
                    alert('Erro na comunicação com o servidor.');
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


    //Excluir Pet
    // Evento para excluir um pet
    document.querySelectorAll('.excluir-btn').forEach(button => {
        button.addEventListener('click', function () {
            if (confirm('Tem certeza que deseja excluir este pet?')) {
                const petElement = this.closest('.pet');
                const petId = petElement.getAttribute('data-pet-id');

                // Enviar requisição AJAX para excluir o pet
                fetch('excluir-pet.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'petId=' + encodeURIComponent(petId)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            petElement.remove(); // Remove o elemento da página
                        } else {
                            alert('Erro: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Ocorreu um erro ao tentar excluir o pet.');
                    });
            }
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


    // Foto preview editar pet
    document.querySelectorAll('.fotoPetInput').forEach(function (input) {
        input.addEventListener('change', function (event) {
            const petId = event.target.id.split('-')[1]; // Extrai o ID do pet do input file
            const preview = document.getElementById('preview-' + petId); // Obtém o elemento de pré-visualização da imagem
            const file = event.target.files[0]; // O arquivo selecionado

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result; // Define o src da imagem para o conteúdo do arquivo
                };
                reader.readAsDataURL(file); // Lê o arquivo como uma URL base64

                // Criar um objeto FormData para enviar o arquivo via AJAX
                let formData = new FormData();
                formData.append('foto', file); // Anexa o arquivo de imagem
                formData.append('pet_id', petId); // Anexa o ID do pet

                // Enviar a imagem para o servidor via AJAX
                fetch('atualizar_foto.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text()) // Pega a resposta do servidor
                    .then(data => {
                        console.log(data); // Exibe a resposta (se desejar)
                    })
                    .catch(error => {
                        console.error('Erro ao atualizar a foto:', error);
                        alert('Houve um erro ao atualizar a foto.');
                    });
            } else {
                preview.src = 'default-image.png'; // Imagem padrão se nenhum arquivo for selecionado
            }
        });
    });

});