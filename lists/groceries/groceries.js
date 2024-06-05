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
function listenToCheckbox() {
    var checkboxes = document.querySelectorAll("input[type='checkbox']");
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                console.log('Checkbox is checked');
                reArrangeList();
            } else {
                console.log('Checkbox is unchecked');
            }
        });
    });
}


function reArrangeList(){
    var ul = document.querySelector("#theList");
    var items = ul.children;
    for(var i = 0; i < items.length; i++){
        if(items[i].firstChild.checked){
            ul.appendChild(items[i]);
        }
        else if (!items[i].firstChild.checked){
            ul.insertBefore(items[i], items[0]);
        }
    }
}