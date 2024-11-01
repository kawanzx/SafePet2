/* NavBar confirmação de logout*/
document.addEventListener('DOMContentLoaded', () => {
document.getElementById('logoutLink').addEventListener('click', function (event) {
    event.preventDefault();

    Swal.fire({
        title: "Tem certeza que deseja sair?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sair",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/auth/logout.php";
        }
      });
});

});