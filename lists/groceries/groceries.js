
function Groceries(id, item, isChecked, category) {
    this.id = id;
    this.item = item;
    this.isChecked = isChecked;
    this.category = category;
}

function Categories(id, category_name) {
    this.id = id;
    this.category_name = category_name;
}

function addToList(newItem, categoryID, categoryName){
    alertDiv = document.querySelector("#error-" + categoryID);
    
    if (!newItem.trim()) {
        //alert Div for blank item
        alertDiv.innerHTML = "Please do not enter a blank item.";
        return;
    }
    if(newItem.length > 50){
        //alert Div for too long item
        alertDiv.innerHTML = "Please enter an item that is less than 50 characters.";
        return;
    }
    
    for(var i = 0; i < groceries.length; i++){
       // console.log("groceries[i]: "+ groceries[i].item);
        //console.log("newItem: "+ newItem);
        if(groceries[i].item == newItem){
            //alert Div for duplicate item
            alertDiv.innerHTML = "This item is already in the list.";
            return;
        }    
    }

    itemID = generateID();
    //clear the alert div if there is an error
    alertDiv = document.querySelector("#error-" + categoryID);
    alertDiv.innerHTML = "";
    
    //trim input to make it less messy
    newItem = newItem.trim();

    
    document.querySelector("#"+categoryID).innerHTML += "<li id='"+itemID+"'><input class='list-checkbox' type='checkbox'>"+newItem+"<button class='remove-item-button' onclick='removeThisItem(\""+itemID+"\")'>x</button></li>";
                           

    // Make an HTTP request to a server-side script
    fetch('addItem.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ item: newItem, categoryName: categoryName, id: itemID, categoryID: categoryID})
    })
    .then(response => response.json())
    .then(data => console.log(data))
    .catch((error) => {
        console.error('Error:', error);
    });

    document.querySelector('#add-to-list-'+categoryID).value = "";
    //location.reload();
    //re-arrange the list
    //reArrangeList(itemID);
}

function generateID(){
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    let result = '';
    const charactersLength = characters.length;
    for (let i = 0; i < 12; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}
//decorateListAdding(item, itemID, isChecked);{

//}

function removeThisItem(itemID){
    console.log("item to remove: "+itemID);
    var forTheID = "#"+itemID;
    console.log("forTheID: "+forTheID);
    var li = document.querySelector(forTheID);
    li.remove(); 
    
    // Make an HTTP request to a server-side script
     fetch('removeItem.php', {
        method: 'POST',
        body: JSON.stringify({ itemID: itemID })
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

function addCategory(categoryToAdd){
    categoryAlertDiv = document.querySelector("#error-newCategory");

    if (!categoryToAdd.trim()) {
        categoryAlertDiv.innerHTML = "Please do not enter a blank item.";
        return;
    }
    if(categoryToAdd.length > 50){
        //alert Div for too long item
        categoryAlertDiv.innerHTML = "Please enter an item that is less than 50 characters.";
        return;
    }
    for(var i = 0; i < categories.length; i++){
        if(categories[i] == categoryToAdd){
            //alert Div for duplicate item
            categoryAlertDiv.innerHTML = "This category already exists.";
            return;
        }
    }

    //trim the input to make it less messy
    categoryToAdd = categoryToAdd.trim();

    categoryAlertDiv = document.querySelector("#error-newCategory");
    categoryAlertDiv.innerHTML = "";
    var newCategory = categoryToAdd;
    console.log("newCategory in the addCategory function: "+newCategory);
    
    var newCategoryID = generateID();
    console.log("newCategoryID in the addCategory function: "+newCategoryID);
    
    var newCategoryDiv = document.createElement("div");
    var newCategoryList = document.createElement("ul");
    newCategoryList.id = newCategoryID;

    var categoriesList = document.querySelector("#group-of-lists");
    
    var newCategoryHeader = document.createElement("h2");
    newCategoryDiv.className = "list";
    newCategoryHeader.innerHTML = newCategory;

    newCategoryDiv.appendChild(newCategoryHeader);
    newCategoryDiv.appendChild(newCategoryList);
    document.querySelector("#newCategory").value = "";

    var actionsDiv = document.createElement("div");
    actionsDiv.className = "actions";
    var actionsInput = document.createElement("input");
    actionsInput.type = "text";
    actionsInput.id = "add-to-list-"+newCategoryID;
    actionsInput.className = "add-item-textbox";
    
    actionsInput.placeholder = "Add an item hoe";
    var actionsButton = document.createElement("button");
    actionsButton.classList.add("add-item-button");
    actionsButton.innerHTML = "Add Item";
     // Use addEventListener instead of directly assigning to onclick
    actionsButton.addEventListener('click', function() {
        addToList(actionsInput.value, newCategoryID, newCategory);
        actionsInput.focus();
    });

    var errorDiv = document.createElement("div");
    errorDiv.id = "error-" + newCategoryID;
    errorDiv.className = "error-message";
    
    actionsDiv.appendChild(actionsInput);
    actionsDiv.appendChild(actionsButton);
    actionsDiv.appendChild(errorDiv);

    categoriesList.appendChild(newCategoryDiv);
    categoriesList.appendChild(actionsDiv);



    //put it into the array of categories
    categories.push(new Categories(newCategoryID, newCategory));
    decorateDeleteCategoryDropdown();
    

    // Make an HTTP request to a server-side script
    fetch('addCategory.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ category: newCategory, id: newCategoryID})
    })
    .then(response => response.json())
    //.then(data => console.log(data))
    .catch((error) => {
        console.error('Error:', error);
    });
    
}

function decorateDeleteCategoryDropdown(){
    var theSelect = document.getElementById('category-dropdown'); 
    theSelect.innerHTML = "";
    for(var i = 0; i<categories.length; i++){
        theSelect.innerHTML += "<option id = '"+categories[i].id+"' value='"+encodeURIComponent(categories[i].category_name)+"'>"+categories[i].category_name+"</option>";
    }
}

function deleteCategory(categoryID){

    //find the ul list and get it's parent
    var categoryDiv = document.querySelector("#"+categoryID);
    var listDiv = categoryDiv.parentNode;
    
    //find the actions textbox and get it's parent
    var categoryActionsTextbox = document.querySelector("#add-to-list-"+categoryID);
    var actionsDiv = categoryActionsTextbox.parentNode;
  
    //remove the main list div and main actions div
    actionsDiv.remove();
    listDiv.remove();
      
    // Make an HTTP request to a server-side script
    fetch('deleteCategory.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ categoryID: categoryID})
    })
    .then(response => response.json())
    .then(data => console.log(data))
    .catch((error) => {
        console.error('Error:', error);
    });

    //remove the category from the array
    for(var i = 0; i < categories.length; i++){
        if(categories[i].id == categoryID){
            categories.splice(i, 1);
        }
    }

    decorateDeleteCategoryDropdown();
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

function replaceSpecialCharacters(str){
    // Define a regular expression that matches special characters
    // You might need to add more characters to the regex depending on your needs
    const regex = /[\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|\'\"\@\#]/g;
    // Replace each special character with an escape character followed by the character itself
    return str.replace(regex, "\\$&");
}

function escapeHTML(str) {
    // Define a map of characters to their HTML entity equivalents
    const charMap = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
        "/": '&#x2F;',
        "`": '&#x60;',
        "=": '&#x3D;'
    };
    
    // Use the replace function with a function as its second argument
    // The function will return the corresponding HTML entity or the character itself if not found in the map
    return str.replace(/[&<>"'`=\/]/g, function(char) {
        return charMap[char] || char;
    });
}