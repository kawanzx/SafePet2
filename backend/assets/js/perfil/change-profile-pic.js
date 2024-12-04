// Trocar foto de perfil
document.addEventListener('DOMContentLoaded', () => {
    function previewAndUploadFoto() {
        var input = document.getElementById('input-foto');
        var preview = document.getElementById('preview-avatar');

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result; // Atualiza o preview da imagem
            };
            reader.readAsDataURL(input.files[0]);

            const uploadPath = tipoUsuario === 'tutor' ? '/backend/assets/uploads/fotos-tutores/' : '/backend/assets/uploads/fotos-cuidadores/';

            // Envia a imagem para o servidor automaticamente
            var formData = new FormData();
            formData.append('nova_foto', input.files[0]);
            formData.append('tipo_usuario', tipoUsuario);
            
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/backend/includes/perfil/upload_foto.php', true);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Sucesso!",
                            text: "Foto alterada.",
                        });
                        preview.src = uploadPath + response.nova_foto + '?' + new Date().getTime();
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Erro!",
                            text: response.error,
                        }).then(() => {
                            location.reload();
                        });
                    }
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Erro!",
                        text: 'Erro ao enviar a foto.',
                    })
                }
            };

            xhr.send(formData);
        }
    }

    document.getElementById('input-foto').onchange = previewAndUploadFoto;
});