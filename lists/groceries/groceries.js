function addToList(newItem, itemID){
    if (!newItem.trim()) {
        alertDiv = document.querySelector("#error-" + itemID);
        alertDiv.innerHTML = "Please do not enter a blank item.";
        return;
    }
    alertDiv = document.querySelector("#error-" + itemID);
    alertDiv.innerHTML = "";
    console.log(newItem);
    console.log("The itemID: " + itemID);
    var nameForList = itemID.split("-").pop();
    console.log("The name for the list: " + nameForList);
    
    
    ul = document.querySelector("#"+nameForList+"-list");
    console.log("The ul: " + ul);
    var li = document.createElement("li");
    
    //the three things 
    var checkbox = document.createElement("input");
    var theItem = document.createTextNode(newItem);
    var removeButton = document.createElement("button");
 
    //adding attributes
    checkbox.type = "checkbox";
    checkbox.oninput = listenToCheckbox();
    removeButton.innerHTML = "x";
    //theItem.id = newItem;
    var forItemID = newItem.replaceAll(" ", "-");
    li.id = forItemID;
    //appending the three things to the li
    li.appendChild(checkbox);
    li.appendChild(theItem);
    li.appendChild(removeButton);
    removeButton.onclick = function() {
        removeThisItem(forItemID);
    }
    
    ul.appendChild(li);
    document.querySelector('#'+itemID).value = "";

    // Make an HTTP request to a server-side script
    fetch('addItem.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ item: newItem, category: nameForList })
    })
    .then(response => response.json())
    .then(data => console.log(data))
    .catch((error) => {
        console.error('Error:', error);
    });

    //re-arrange the list
    reArrangeList(itemID);
}

function removeFromList(){
    var ul = document.querySelector("#theList");
    ul.removeChild(ul.lastChild);
}

function removeThisItem(item){
    var forTheID = "#"+item;
    var li = document.querySelector(forTheID);
    console.log("The item to be removed: " + li);
    li.remove(); 
    
    // Make an HTTP request to a server-side script
     fetch('removeItem.php', {
        method: 'POST',
        body: JSON.stringify({ item: item })
    })
    .then(response => response.json());
}

function listenToCheckbox() {
    var checkboxes = document.querySelectorAll("input[type='checkbox']");
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                console.log('Checkbox is checked');
                reArrangeList(document.querySelector("#" +(this.previousSibling.id)).id.toString());
            } else {
                console.log('Checkbox is unchecked');
            }
        });
    });
}

function addCategory(){
    var newCategory = document.querySelector("#newCategory").value;
    console.log("Adding a category: " + newCategory);
    var newCategoryID = newCategory.replaceAll(" ", "-");
    var newCategoryDiv = document.createElement("div");
    var newCategoryList = document.createElement("ul");
    newCategoryList.id = newCategoryID + "-list";

    var categoriesList = document.querySelector("#group-of-lists");
    
    var newCategoryHeader = document.createElement("h2");
    newCategoryDiv.className = "list";
    newCategoryHeader.innerHTML = newCategory;

    newCategoryDiv.appendChild(newCategoryHeader);
    newCategoryDiv.appendChild(newCategoryList);
    //newCategoryList.appendChild();
    document.querySelector("#newCategory").value = "";

    var actionsDiv = document.createElement("div");
    actionsDiv.className = "actions";
    var actionsInput = document.createElement("input");
    actionsInput.type = "text";
    actionsInput.id = "item-" + newCategoryID;
    actionsInput.innerHTML = "Add an item hoe";
    actionsInput.placeholder = "Add an item hoe";
    var actionsButton = document.createElement("button");
    actionsButton.classList.add("add-item-button");
    actionsButton.innerHTML = "Add Item";
    actionsButton.onclick = function() {
        addToList(actionsInput.value, actionsInput.id);
        actionsInput.focus();
    };
    
    var errorDiv = document.createElement("div");
    errorDiv.id = "error-" + actionsInput.id;
    errorDiv.className = "error-message";
    
    actionsDiv.appendChild(actionsInput);
    actionsDiv.appendChild(actionsButton);
    actionsDiv.appendChild(errorDiv);

    categoriesList.appendChild(newCategoryDiv);
    categoriesList.appendChild(actionsDiv);
}

function reArrangeList(itemText){
    irtext = itemText.toString();
    var ul = document.querySelector("#"+itemText);
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