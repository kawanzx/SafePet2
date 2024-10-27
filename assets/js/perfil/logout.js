/* NavBar confirmação de logout*/
document.addEventListener('DOMContentLoaded', () => {
document.getElementById('logoutLink').addEventListener('click', function (event) {
    event.preventDefault();

    var confirmation = window.confirm("Você realmente deseja sair?");
    if (confirmation) {
        window.location.href = "/auth/logout.php";
    }
});

});