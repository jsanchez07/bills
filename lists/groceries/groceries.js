function addToList(newItem){
    var ul = document.querySelector(".list");
    var li = document.createElement("li");
    li.appendChild(document.createTextNode(newItem));
    ul.appendChild(li);
}