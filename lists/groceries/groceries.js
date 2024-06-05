function addToList(newItem){
    var ul = document.querySelector("#theList");
    var li = document.createElement("li");
    li.appendChild(document.createTextNode(newItem));
    ul.appendChild(li);
    document.querySelector('#item').value = "";
}

function removeFromList(){
    var ul = document.querySelector("#theList");
    ul.removeChild(ul.lastChild);
}