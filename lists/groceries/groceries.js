function addToList(newItem){
    var ul = document.querySelector("#theList");
    var li = document.createElement("li");
    //the three things 
    var checkbox = document.createElement("input");
    var theItem = document.createTextNode(newItem);
    var removeButton = document.createElement("button");
    
    console.log(newItem.toString());
    console.log(checkbox);
    console.log(theItem);
    console.log(removeButton);

    //adding attributes
    checkbox.type = "checkbox";
    checkbox.oninput = listenToCheckbox;
    removeButton.innerHTML = "x";
    theItem.id = newItem.toString();
    //appending the three things to the li
    li.appendChild(checkbox);
    li.appendChild(theItem);
    li.appendChild(removeButton);
    removeButton.onclick = function() {
        removeThisItem(li);
    }
    ul.appendChild(li);
    document.querySelector('#item').value = "";
    reArrangeList();
}

function removeFromList(){
    var ul = document.querySelector("#theList");
    ul.removeChild(ul.lastChild);
}

function removeThisItem(item){
     // Make an HTTP request to a server-side script
     var itemID = item.id;
        console.log("Item from the Javascript file at the beginning: " + item);
     fetch('removeItem.php', {
        method: 'POST',
        body: JSON.stringify({ item: itemID })
    })
    .then(response => response.json());
    //.then(data => console.log("Is this the data part? " + data));

    var ul = document.querySelector("#theList");
    console.log("Item from the Javascript file: " + itemID);
    console.log("The actual Item from the Javascript file: " + item);
    ul.removeChild(item);
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