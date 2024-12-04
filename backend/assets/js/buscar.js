 var search = document.getElementById('search_input');
 search.addEventListener("keydown", function(event){
     if (event.key === "Enter"){
         searchData();
     }
 });

 function searchData(){
     window.location = 'buscar.php?search='+search.value;
 }
