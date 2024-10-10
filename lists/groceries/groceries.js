
function Groceries(id, item, isChecked, category, categoryID) {
    this.id = id;
    this.item = item;
    this.isChecked = isChecked;
    this.category = category;
    this.categoryID = categoryID;
}

function Categories(id, category_name, order_index) {
    this.id = id;
    this.category_name = category_name;
    this.order_index = order_index;
}

function addToList(newItem, categoryID, categoryName){
    alertDiv = document.querySelector("#error-" + categoryID);
    
    if (!newItem.trim()) {
        //alert Div for blank item
        alertDiv.innerHTML = "Please do not enter a blank item.";
        animateAlertDiv(alertDiv);
        return;
    }
    if(newItem.length > 50){
        //alert Div for too long item
        alertDiv.innerHTML = "Please enter an item that is less than 50 characters.";
        animateAlertDiv(alertDiv);
        return;
    }
    
    for(var i = 0; i < groceries.length; i++){
        if(groceries[i].item.toLowerCase() == newItem.toLowerCase() && groceries[i].category == categoryName){
            //alert Div for duplicate item
            alertDiv.innerHTML = "This item is already in the list.";
            animateAlertDiv(alertDiv);
            return;
        }    
    }

    itemID = generateID();
    //clear the alert div if there is an error
    alertDiv = document.querySelector("#error-" + categoryID);
    alertDiv.innerHTML = "";
    
    //trim input to make it less messy
    newItem = newItem.trim();

    document.querySelector("#"+categoryID).innerHTML += "<li id='"+itemID+"'><input class='list-checkbox' onchange='rearrangeList(\""+categoryID+ "\")' type='checkbox'>"+newItem+"<button class='remove-item-button' onclick='removeThisItem(\""+itemID+"\")'>x</button></li>";
                           
    groceries.push(new Groceries(itemID, newItem, false, categoryName, categoryID));
    // Make an HTTP request to a server-side script
    fetch('addItem.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ item: newItem, categoryName: categoryName, id: itemID, categoryID: categoryID})
    })
    .then(response => response.json())
    //.then(data => console.log(data))
    .catch((error) => {
        console.error('Error:', error);
    });

    document.querySelector('#add-to-list-'+categoryID).value = "";
   
    //show the uncheck all button    
    document.querySelector("#"+categoryID+" button").classList.remove("invisible");
        
    

    console.log(groceries);
    //re-arrange the list
    rearrangeList(categoryID);
}

function removeThisItem(itemID){
    //remove it from the html
    var forTheID = "#"+itemID;
    var li = document.querySelector(forTheID);
    var parentUl = li.parentNode; // Get the parent <ul> or <ol>
    li.remove(); 

    // Check if there are any <li> elements left in the list and make uncheck button invisible if not
    if(parentUl.querySelectorAll('li').length === 0) {
        // No <li> elements left, do something here
        document.querySelector("#"+parentUl.id+" button").classList.add("invisible");
    }

    //remove it from the array
    for(var i = 0; i < groceries.length; i++){
        if(groceries[i].id == itemID){
            groceries.splice(i, 1);
        }
    }
    
    // Make an HTTP request to a server-side script
     fetch('removeItem.php', {
        method: 'POST',
        body: JSON.stringify({ itemID: itemID })
    })
    .then(response => response.json());
}

function addCategory(categoryToAdd){
    categoryAlertDiv = document.querySelector("#error-newCategory");

    if (!categoryToAdd.trim()) {
        categoryAlertDiv.innerHTML = "Please do not enter a blank item.";
        animateAlertDiv(categoryAlertDiv);
        return;
    }
    if(categoryToAdd.length > 50){
        //alert Div for too long item
        categoryAlertDiv.innerHTML = "Please enter an item that is less than 50 characters.";
        animateAlertDiv(categoryAlertDiv);
        return;
    }
    for(var i = 0; i < categories.length; i++){
        if(categories[i].category_name.toLowerCase() == categoryToAdd.toLowerCase()){
            //alert Div for duplicate item
            categoryAlertDiv.innerHTML = "This category already exists.";
            animateAlertDiv(categoryAlertDiv);
            return;
        }
    }

    //trim the input to make it less messy
    categoryToAdd = categoryToAdd.trim();

    categoryAlertDiv = document.querySelector("#error-newCategory");
    categoryAlertDiv.innerHTML = "";
    
    var newCategory = categoryToAdd;
    var newCategoryID = generateID();

    // Extract order_index values from categories
    let orderIndexes = categories.map(category => category.order_index);

    // Find the highest order_index value
    let maxOrderIndex = Math.max(...orderIndexes);

    // Calculate the new order_index
    let newOrderIndex = maxOrderIndex + 1;
    var stringNewOrderIndex = newOrderIndex.toString();
  

    var groupOfLists = document.querySelector("#group-of-lists");
    var addToListTextboxID = "add-to-list-"+newCategoryID;
    groupOfLists.innerHTML += "<div class = 'list'><h2>"+categoryToAdd+"</h2><ul id='"+ newCategoryID+"'></ul>";

    groupOfLists.innerHTML += `
        <div class='actions'>
            <input type='text' class='add-item-textbox' id='` + addToListTextboxID + `' placeholder='Add an item'>
            <button class='add-item-button' onclick='addToList(document.querySelector("#` + addToListTextboxID + `").value, "` + newCategoryID + `", "`+escapeHTML(categoryToAdd)+`"); document.querySelector("#` + addToListTextboxID + `").focus()'>Add</button>
            <div class='error-message' id='error-` + newCategoryID + `'></div>
        </div>
        </div>
        `;
    document.querySelector("#newCategory").value = "";

    //put it into the array of categories
    categories.push(new Categories(newCategoryID, newCategory, stringNewOrderIndex));
    decorateDeleteCategoryDropdown();
    
    console.log(categories);
    // Make an HTTP request to a server-side script
    fetch('addCategory.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ category: newCategory, id: newCategoryID, order_index: newOrderIndex})
    })
    .then(response => response.json())
    //.then(data => data)
    .catch((error) => {
        console.error('Error:', error);
    });
    
}

function deleteCategory(categoryID){
     // Confirmation dialog
     if (!confirm("Are you sure you want to delete this category?")) {
        return; // Stop the function if the user does not confirm
    }
    //find the ul list and get it's parent
    var categoryDiv = document.querySelector("#"+categoryID);
    var listDiv = categoryDiv.parentNode;
    
    //find the actions textbox and get it's parent
    var categoryActionsTextbox = document.querySelector("#add-to-list-"+categoryID);
    var actionsDiv = categoryActionsTextbox.parentNode;
  
    //remove the main list div and main actions div
    actionsDiv.remove();
    listDiv.remove();

     // Find the category in the array and get its order_index
    let orderIndexToRemove;
    for (var i = 0; i < categories.length; i++) {
        if (categories[i].id == categoryID) {
            orderIndexToRemove = categories[i].order_index;
            categories.splice(i, 1); // Remove the category from the array
            break;
        }
    }

    // Reorder the remaining order_indexes
    categories.forEach((category, index) => {
    if (parseInt(category.order_index, 10) > orderIndexToRemove) {
        category.order_index -= 1;
    }
    });

    // Create an array of objects containing category_id and order_index
    let categoryOrderIndexes = categories.map(category => ({
        category_id: category.id,
        order_index: category.order_index
    }));
    decorateDeleteCategoryDropdown();
      
    // Make an HTTP request to a server-side script to delete the given category
    fetch('deleteCategory.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ categoryID: categoryID})
    })
    .then(response => response.json())
    //.then(data => console.log(data))
    .catch((error) => {
        console.error(' delete Error:', error);
    });

    // Make an HTTP request to a server-side script to update the order_index values of the remaining categories
    fetch('reorderCategories.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ categoryOrderIndexes: categoryOrderIndexes })
    })
    .then(response => response.json())
    //.then(data => (data))
    .catch((error) => {
      console.error('reorder Error:', error);
    });
}

function moveCategoryUp(categoryID){
    console.log("moveCategoryUp", categoryID);
}

function moveCategoryDown(categoryID){
    console.log("moveCategoryDown", categoryID);
}

function decorateDeleteCategoryDropdown(){
    var theSelect = document.getElementById('category-dropdown'); 
    theSelect.innerHTML = "";
    for(var i = 0; i<categories.length; i++){
        theSelect.innerHTML += "<option id = '"+categories[i].id+"' value='"+encodeURIComponent(categories[i].category_name)+"'>"+categories[i].category_name+"</option>";
    }
}

function rearrangeList(listID){
    var ul = document.querySelector("#" + listID);
    var items = Array.from(ul.children); // Convert to array for stable iteration
    var checkedItems = [];
    var uncheckedItems = [];
    var checkedItemsText = [];
    var uncheckedItemsText = [];

    // First, separate items into checked and unchecked without modifying the DOM
    items.forEach(function(item) {
        if(item.firstChild.checked){
            itemText = item.id.replace("#li", "");
            checkedItemsText.push(itemText);
            checkedItems.push(item);
        } else {
            itemText = item.id.replace("#li", "");
            uncheckedItemsText.push(itemText);
            uncheckedItems.push(item);
        }
    });

    // Then, rearrange the DOM based on checked status
    uncheckedItems.forEach(function(item) {
        ul.appendChild(item); // This moves unchecked items to the end
    });
    checkedItems.forEach(function(item) {
        ul.appendChild(item); // This moves checked items to the end, maintaining their order
    });

    // Make an HTTP request to a server-side script
    fetch('rearrangeList.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ checkedItemsText: checkedItemsText, uncheckedItemsText: uncheckedItemsText})
    })
        .then(response => response.json())
        //.then(data => console.log(data))
        .catch((error) => {
            console.error('Error:', error);
        });

}

function orderCategories(){

}

//helper functions that are used in the main functions

function uncheckAll(categoryID){
    var ul = document.querySelector("#"+categoryID);
    var items = Array.from(ul.children); // Convert to array for stable iteration
    items.forEach(function(item){
        item.firstChild.checked = false;
    });
    rearrangeList(categoryID);

    // Make an HTTP request to a server-side script
    fetch('uncheckAll.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ categoryID: categoryID})
    })
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

function animateAlertDiv(alertDiv){
    alertDiv.style.animation = "none";
    // Trigger reflow
    alertDiv.offsetHeight;
    // Apply the new animation
    alertDiv.style.animation = "growAndBack 2s forwards";
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

function replaceSpecialCharacters(str){
    // Define a regular expression that matches special characters
    // You might need to add more characters to the regex depending on your needs
    const regex = /[\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|\'\"\@\#]/g;
    // Replace each special character with an escape character followed by the character itself
    return str.replace(regex, "\\$&");
}