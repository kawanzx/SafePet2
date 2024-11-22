var search = document.getElementById('busca');
search.addEventListener("keydown", function(event){
    if (event.key === "Enter"){
        searchData();
    }
});

function searchData(){
    window.location = 'buscar.php?search='+search.value;
}

// const search = document.getElementById('search'),
//       searchBtn = document.getElementById('search-btn'),
//       searchClose = document.getElementById('search-close')

// /* Search show */
// searchBtn.addEventListener('click', () =>{
//    search.classList.add('show-search')
// })

// /* Search hidden */
// searchClose.addEventListener('click', () =>{
//    search.classList.remove('show-search')
// })