var search = document.getElementById('busca');
search.addEventListener("keydown", function(event){
    if (event.key === "Enter"){
        searchData();
    }
});

function searchData(){
    window.location = 'buscar.php?search='+search.value;
}