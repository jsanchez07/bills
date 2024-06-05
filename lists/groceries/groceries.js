function addToList(newItem){
    var ul = document.querySelector("#theList");
    var li = document.createElement("li");
    var checkbox = document.createElement("input");
    checkbox.type = "checkbox";
    li.appendChild(checkbox);
    li.appendChild(document.createTextNode(newItem));
    ul.appendChild(li);
    document.querySelector('#item').value = "";
}

function removeFromList(){
    var ul = document.querySelector("#theList");
    ul.removeChild(ul.lastChild);
}