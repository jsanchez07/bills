
function Groceries(id, item, isChecked, category, categoryID, image_url, description) {
    this.id = id;
    this.item = item;
    this.isChecked = isChecked;
    this.category = category;
    this.categoryID = categoryID;
    this.image_url = image_url || '';
    this.description = description || '';
}

function Categories(id, category_name, order_index) {
    this.id = id;
    this.category_name = category_name;
    this.order_index = order_index;
}

function populateAllLists() {
    var groupOfLists = document.querySelector("#group-of-lists");

    // Iterate through the categories array to create the ul elements for each category
    categories.forEach(category => {
        var categoryID = category.id;
        var categoryName = category.category_name;
        var addToListTextboxID = "add-to-list-" + categoryID;

        // Create the wrapper div
        var wrapperDiv = document.createElement('div');
        wrapperDiv.id = 'category-wrapper-' + categoryID;

        // Create the list div
        var listDiv = document.createElement('div');
        listDiv.className = 'list';
        listDiv.innerHTML = "<div class='heading-and-buttons' onclick='toggleCategory(\"" + categoryID + "\")'><span class='collapse-arrow'>▼</span><h2>" + categoryName + "</h2><div class='move-list'><button id='" + categoryID + "-up' onclick='event.stopPropagation(); moveCategoryUp(\"" + categoryID + "\")'></button><button id='" + categoryID + "-down' onclick='event.stopPropagation(); moveCategoryDown(\"" + categoryID + "\")'></button></div></div><ul id='" + categoryID + "' class='category-content'><div class='list-action-buttons'><button class='uncheck-all-button' onclick='uncheckAll(\"" + categoryID + "\")'>Uncheck All</button><button class='delete-all-button' onclick='deleteCheckedItems(\"" + categoryID + "\", \"" + escapeHTML(categoryName) + "\")'>Delete Checked</button></div></ul>";

        // Append list items to the list div
        var numItemsinCategory = 0;
        groceries.forEach(grocery => {
            if (category.category_name == grocery.category) {
                var itemID = grocery.id;
                var listItem = document.createElement('li');
                listItem.id = itemID;
                var hasDetails = (grocery.image_url && grocery.image_url !== '') || (grocery.description && grocery.description !== '');
                var indicator = hasDetails ? '<span class="details-indicator"></span>' : '';
                listItem.innerHTML = "<input class='list-checkbox' type='checkbox' onclick='event.stopPropagation(); rearrangeList(\"" + categoryID + "\")'" + (grocery.isChecked == 1 ? 'checked' : '') + "><span class='item-text' onclick='openItemModal(\"" + itemID + "\")'>" + indicator + grocery.item + "</span><button class='remove-item-button' onclick='event.stopPropagation(); removeThisItem(\"" + itemID + "\")'>x</button>";
                listDiv.querySelector('ul').appendChild(listItem);
                numItemsinCategory++;
            }
        });

        // Create the actions div
        var actionsDiv = document.createElement('div');
        actionsDiv.className = 'actions category-content';
        actionsDiv.innerHTML = "<input type='text' class='add-item-textbox' id='" + addToListTextboxID + "' placeholder='Add an item'><button class='add-item-button' onclick='addToList(document.querySelector(\"#" + addToListTextboxID + "\").value, \"" + categoryID + "\", \"" + escapeHTML(categoryName) + "\"); document.querySelector(\"#" + addToListTextboxID + "\").focus()'>Add</button><div class='error-message' id='error-" + categoryID + "'></div>";

        // Append the list and actions divs to the wrapper div
        wrapperDiv.appendChild(listDiv);
        wrapperDiv.appendChild(actionsDiv);

        // Append the wrapper div to the group of lists
        groupOfLists.appendChild(wrapperDiv);

        // Clean the list up and hide the uncheck all button if there are no items in the category
        rearrangeList(categoryID);

        if (numItemsinCategory == 0) {
            document.querySelector("#" + categoryID + " .list-action-buttons").classList.add("invisible");
        } else if (numItemsinCategory > 0) {
            document.querySelector("#" + categoryID + " .list-action-buttons").classList.remove("invisible");
        }
    });

    // Optionally, update the indexes of all items in each list
    const lists = document.querySelectorAll('.list ul');
    lists.forEach(ul => {
       // updateIndexes(ul);
    });

    // Reattach event listeners and set draggable attribute correctly
   // reattachEventListeners();
}

function reattachEventListeners() {
    const checkboxes = document.querySelectorAll('.list-checkbox');
    checkboxes.forEach(checkbox => {
        // Remove any existing event listeners to prevent duplication
        checkbox.removeEventListener('click', handleCheckboxClick);
        checkbox.addEventListener('click', handleCheckboxClick);
    });

    const removeButtons = document.querySelectorAll('.remove-item-button');
    removeButtons.forEach(button => {
        // Remove any existing event listeners to prevent duplication
        button.removeEventListener('click', handleRemoveButtonClick);
        button.addEventListener('click', handleRemoveButtonClick);
    });

    const listItems = document.querySelectorAll('.list li');
    listItems.forEach(item => {
        const checkbox = item.querySelector('input[type="checkbox"]');
        if (checkbox && checkbox.checked) {
            item.setAttribute('draggable', false);
        } else {
            item.setAttribute('draggable', true);
        }
    });
}

function handleCheckboxClick(event) {
    const listID = this.closest('ul').id;
    rearrangeList(listID);
}

function handleRemoveButtonClick(event) {
    const itemID = this.closest('li').id;
    removeThisItem(itemID);
}

function addToList(newItem, categoryID, categoryName){
    console.log("newItem: ", newItem);
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

    // Create new list item using DOM methods to preserve existing checkbox states
    var newListItem = document.createElement('li');
    newListItem.id = itemID;
    
    var checkbox = document.createElement('input');
    checkbox.className = 'list-checkbox';
    checkbox.type = 'checkbox';
    checkbox.onclick = function(e) { 
        e.stopPropagation(); 
        rearrangeList(categoryID); 
    };
    
    var itemTextSpan = document.createElement('span');
    itemTextSpan.className = 'item-text';
    itemTextSpan.textContent = newItem;
    itemTextSpan.onclick = function() { openItemModal(itemID); };
    
    var removeButton = document.createElement('button');
    removeButton.className = 'remove-item-button';
    removeButton.onclick = function(e) { 
        e.stopPropagation(); 
        removeThisItem(itemID); 
    };
    removeButton.textContent = 'x';
    
    newListItem.appendChild(checkbox);
    newListItem.appendChild(itemTextSpan);
    newListItem.appendChild(removeButton);
    
    document.querySelector("#"+categoryID).appendChild(newListItem);
                           
    groceries.push(new Groceries(itemID, newItem, false, categoryName, categoryID, '', ''));

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
   
    //show the action buttons    
    document.querySelector("#"+categoryID+" .list-action-buttons").classList.remove("invisible");
        
    

    console.log("groceries after the fetch: ", groceries);
    //re-arrange the list
    rearrangeList(categoryID);
}

document.addEventListener('DOMContentLoaded', (event) => {
    enableDragAndDrop();
});

function enableDragAndDrop() {
    const listItems = document.querySelectorAll('.list li');
    listItems.forEach(item => {
        item.setAttribute('draggable', true);
        item.addEventListener('dragstart', handleDragStart);
        item.addEventListener('dragover', handleDragOver);
        item.addEventListener('drop', handleDrop);
        item.addEventListener('dragend', handleDragEnd);
    });
}

let draggedItem = null;

function handleDragStart(event) {
    if (this.querySelector('input[type="checkbox"]').checked) {
        event.preventDefault(); // Prevent dragging if the item is checked
        return;
    }
    draggedItem = this;
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/html', this.innerHTML);
    this.classList.add('dragging');
}

function handleDragOver(event) {
    if (event.preventDefault) {
        event.preventDefault(); // Necessary. Allows us to drop.
    }
    event.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
    return false;
}

function handleDrop(event) {
    if (event.stopPropagation) {
        event.stopPropagation(); // Stops some browsers from redirecting.
    }

    // Check if the drop target is within the allowed area
    if (isDropAllowed(this)) {
        if (draggedItem !== this) {
            draggedItem.innerHTML = this.innerHTML;
            this.innerHTML = event.dataTransfer.getData('text/html');
        }
    } else {
        console.error('Drop not allowed in this area.');
    }

    return false;
}

function handleDragEnd(event) {
    this.classList.remove('dragging');
    enableDragAndDrop(); // Re-enable drag and drop to update event listeners
}

function isDropAllowed(target) {
    // Check if the target is not in the bottom area where checked checkboxes are moved
    const checkedItems = document.querySelectorAll('.list li input[type="checkbox"]:checked');
    const checkedItemsArray = Array.from(checkedItems).map(item => item.closest('li'));
    return !checkedItemsArray.includes(target);
}

function removeThisItem(itemID){
    //remove it from the html
    console.log("itemID: ", itemID);
    var forTheID = "#"+itemID;
    var li = document.querySelector(forTheID);
    var parentUl = li.parentNode; // Get the parent <ul> or <ol>
    console.log("parentUl: ", parentUl);
    console.log("li: ", li);
    li.remove(); 

    // Check if there are any <li> elements left in the list and make action buttons invisible if not
    if(parentUl.querySelectorAll('li').length === 0) {
        // No <li> elements left, do something here
        document.querySelector("#"+parentUl.id+" .list-action-buttons").classList.add("invisible");
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

    console.log("newCategory: ", newCategory);
    console.log("newCategoryID: ", newCategoryID);

    // Extract order_index values from categories
    let orderIndexes = categories.map(category => category.order_index);

    // Find the highest order_index value
    let maxOrderIndex = Math.max(...orderIndexes);

    // Calculate the new order_index
    let newOrderIndex = maxOrderIndex + 1;
    var stringNewOrderIndex = newOrderIndex.toString();
  

    var groupOfLists = document.querySelector("#group-of-lists");
    var addToListTextboxID = "add-to-list-"+newCategoryID;
    
    // Create the wrapper div
    var wrapperDiv = document.createElement('div');
    wrapperDiv.id = 'category-wrapper-' + newCategoryID;

    // Create the list div
    var listDiv = document.createElement('div');
    listDiv.className = 'list';
    listDiv.innerHTML = "<div class='heading-and-buttons'><h2>" + newCategory + "</h2><div class='move-list'><button id='" + newCategoryID + "-up' onclick='moveCategoryUp(\"" + newCategoryID + "\")'></button><button id='" + newCategoryID + "-down' onclick='moveCategoryDown(\"" + newCategoryID + "\")'></button></div></div><ul id='" + newCategoryID + "'><button class='uncheck-all-button' onclick='uncheckAll(\"" + newCategoryID + "\")'>Uncheck All</button></ul>";

    // Create the actions div
    var actionsDiv = document.createElement('div');
    actionsDiv.className = 'actions';
    actionsDiv.innerHTML = `
        <input type='text' class='add-item-textbox' id='` + addToListTextboxID + `' placeholder='Add an item'>
        <button class='add-item-button' onclick='addToList(document.querySelector("#` + addToListTextboxID + `").value, "` + newCategoryID + `", "` + escapeHTML(categoryToAdd) + `"); document.querySelector("#` + addToListTextboxID + `").focus()'>Add</button>
        <div class='error-message' id='error-` + newCategoryID + `'></div>
    `;

    // Append the list and actions divs to the wrapper div
    wrapperDiv.appendChild(listDiv);
    wrapperDiv.appendChild(actionsDiv);

    // Append the wrapper div to the group of lists
    groupOfLists.appendChild(wrapperDiv);
   

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

   
    // Remove the category from the DOM
    var categoryWrapperDiv = document.querySelector("#category-wrapper-"+categoryID);
    categoryWrapperDiv.remove();

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

    console.log('categoryOrderIndexes:', categoryOrderIndexes);
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
    
    // Find the category and its order_index
    let currentCategory = categories.find(category => category.id === categoryID);
    let currentIndex = categories.indexOf(currentCategory);

    // Check if the category is already at the top
    if (currentIndex === 0) {
        console.log("Category is already at the top.");
        return;
    }

    // Swap the order_index with the category above it
    let aboveCategory = categories[currentIndex - 1];
    let tempOrderIndex = currentCategory.order_index;
    currentCategory.order_index = aboveCategory.order_index;
    aboveCategory.order_index = tempOrderIndex;

    // Sort categories by order_index
    categories.sort((a, b) => a.order_index - b.order_index);

    // Create an array of objects containing category_id and order_index
    let categoryOrderIndexes = categories.map(category => ({
        category_id: category.id,
        category_name: category.category_name,
        order_index: category.order_index
    }));

    console.log('categoryOrderIndexes:', categoryOrderIndexes);

    // Make an HTTP request to a server-side script to update the order_index values of the categories
    fetch('reorderCategories.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ categoryOrderIndexes: categoryOrderIndexes })
    })
    .then(response => response.json())
    .catch((error) => {
        console.error('reorder Error:', error);
    });

    // Reorder the categories in the DOM
    let groupOfLists = document.querySelector("#group-of-lists");
    let categoryWrapperDivs = Array.from(groupOfLists.children);

    // Create a map of category IDs to order indexes
    let categoryOrderMap = {};
    categories.forEach(category => {
        categoryOrderMap[category.id] = category.order_index;
    });
    // Log the categoryOrderMap for debugging
    console.log("Category Order Map:", categoryOrderMap);
    //console.log("Category Order Map Keys:", Object.keys(categoryOrderMap));
    //console.log("Category Order Map Values:", Object.values(categoryOrderMap));
    

   // Check if the id attributes of the categoryWrapperDivs match the keys in the categoryOrderMap
    categoryWrapperDivs.forEach(div => {
        let id = div.getAttribute('id');
        if (id) {
            id = id.replace('category-wrapper-', '');
            if (!(id in categoryOrderMap)) {
                console.error(`ID ${id} not found in categoryOrderMap`);
            } else {
                console.log(`ID ${id} found in categoryOrderMap with order ${categoryOrderMap[id]}`);
            }
        } else {
            console.error('ID attribute is missing or null');
        }
    });

    // Sort the categoryWrapperDivs array based on the order index from the categories object
    categoryWrapperDivs.sort((a, b) => {
        let idA = a.getAttribute('id') ? a.getAttribute('id').replace('category-wrapper-', '') : null;
        let idB = b.getAttribute('id') ? b.getAttribute('id').replace('category-wrapper-', '') : null;
        let orderA = idA ? categoryOrderMap[idA] : Infinity;
        let orderB = idB ? categoryOrderMap[idB] : Infinity;

        // Log the order values for debugging
        console.log(`Comparing ${idA} (order: ${orderA}) with ${idB} (order: ${orderB})`);

        return orderA - orderB;
    });

    // Append the sorted elements back to the groupOfLists
    categoryWrapperDivs.forEach((categoryWrapperDiv) => {
        groupOfLists.appendChild(categoryWrapperDiv);
        // Log the appended element for debugging
        let id = categoryWrapperDiv.getAttribute('id') ? categoryWrapperDiv.getAttribute('id').replace('category-wrapper-', '') : null;
        console.log(`Appended ${id} to groupOfLists`);
    });
    decorateDeleteCategoryDropdown()

}

function moveCategoryDown(categoryID){
    console.log("moveCategoryDown", categoryID);
    
    // Find the category and its order_index
    let currentCategory = categories.find(category => category.id === categoryID);
    let currentIndex = categories.indexOf(currentCategory);

    // Check if the category is already at the bottom
    if (currentIndex === categories.length - 1) {
        console.log("Category is already at the bottom.");
        return;
    }

    // Swap the order_index with the category below it
    let belowCategory = categories[currentIndex + 1];
    let tempOrderIndex = currentCategory.order_index;
    currentCategory.order_index = belowCategory.order_index;
    belowCategory.order_index = tempOrderIndex;

    // Sort categories by order_index
    categories.sort((a, b) => a.order_index - b.order_index);

    // Create an array of objects containing category_id and order_index
    let categoryOrderIndexes = categories.map(category => ({
        category_id: category.id,
        category_name: category.category_name,
        order_index: category.order_index
    }));

    console.log('categoryOrderIndexes:', categoryOrderIndexes);

    // Make an HTTP request to a server-side script to update the order_index values of the categories
    fetch('reorderCategories.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ categoryOrderIndexes: categoryOrderIndexes })
    })
    .then(response => response.json())
    .catch((error) => {
        console.error('reorder Error:', error);
    });

    // Reorder the categories in the DOM
    let groupOfLists = document.querySelector("#group-of-lists");
    let categoryWrapperDivs = Array.from(groupOfLists.children);

    // Create a map of category IDs to order indexes
    let categoryOrderMap = {};
    categories.forEach(category => {
        categoryOrderMap[category.id] = category.order_index;
    });
    // Log the categoryOrderMap for debugging
    console.log("Category Order Map:", categoryOrderMap);

    // Check if the id attributes of the categoryWrapperDivs match the keys in the categoryOrderMap
    categoryWrapperDivs.forEach(div => {
        let id = div.getAttribute('id');
        if (id) {
            id = id.replace('category-wrapper-', '');
            if (!(id in categoryOrderMap)) {
                console.error(`ID ${id} not found in categoryOrderMap`);
            } else {
                console.log(`ID ${id} found in categoryOrderMap with order ${categoryOrderMap[id]}`);
            }
        } else {
            console.error('ID attribute is missing or null');
        }
    });

    // Sort the categoryWrapperDivs array based on the order index from the categories object
    categoryWrapperDivs.sort((a, b) => {
        let idA = a.getAttribute('id') ? a.getAttribute('id').replace('category-wrapper-', '') : null;
        let idB = b.getAttribute('id') ? b.getAttribute('id').replace('category-wrapper-', '') : null;
        let orderA = idA ? categoryOrderMap[idA] : Infinity;
        let orderB = idB ? categoryOrderMap[idB] : Infinity;

        // Log the order values for debugging
        console.log(`Comparing ${idA} (order: ${orderA}) with ${idB} (order: ${orderB})`);

        return orderA - orderB;
    });

    // Append the sorted elements back to the groupOfLists
    categoryWrapperDivs.forEach((categoryWrapperDiv) => {
        groupOfLists.appendChild(categoryWrapperDiv);
        // Log the appended element for debugging
        let id = categoryWrapperDiv.getAttribute('id') ? categoryWrapperDiv.getAttribute('id').replace('category-wrapper-', '') : null;
        console.log(`Appended ${id} to groupOfLists`);
    });
    decorateDeleteCategoryDropdown()
   
}

function decorateDeleteCategoryDropdown(){
    var theSelect = document.getElementById('category-dropdown'); 
    theSelect.innerHTML = "";
    for(var i = 0; i<categories.length; i++){
        theSelect.innerHTML += "<option id = '"+categories[i].id+"' value='"+encodeURIComponent(categories[i].category_name)+"'>"+categories[i].category_name+"</option>";
    }
}

function hideList(categoryID){
    var theListToHide = document.querySelector("#toHide-"+categoryID);
    theListToHide.classList.toggle("invisible");
    console.log("theListToHide: ", theListToHide);
}

function rearrangeList(listID){
    var ul = document.querySelector("#" + listID);
    //console.log("the UL: ", ul);
    var items = Array.from(ul.children); // Convert to array for stable iteration
    var checkedItems = [];
    var uncheckedItems = [];
    var checkedItemsText = [];
    var uncheckedItemsText = [];

    // First, separate items into checked and unchecked without modifying the DOM
    items.forEach(function(item) {
        var checkbox = item.querySelector('input[type="checkbox"]');
        if(checkbox && checkbox.checked){
            itemText = item.id.replace("#li", "");
            checkedItemsText.push(itemText);
            checkedItems.push(item);
            for(var i = 0; i < groceries.length; i++){
                if(groceries[i].id == item.id){
                    groceries[i].isChecked = 1;
                }
            }
        } else if(checkbox && !checkbox.checked){
            itemText = item.id.replace("#li", "");
            uncheckedItemsText.push(itemText);
            uncheckedItems.push(item);
            for(var i = 0; i < groceries.length; i++){
                if(groceries[i].id == item.id){
                    groceries[i].isChecked = 0;
                }
            }
        }
    });

    //console.log("checkedItems: ", checkedItemsText);
    //console.log("uncheckedItems: ", uncheckedItemsText);

    // Then, rearrange the DOM based on checked status
    uncheckedItems.forEach(function(item) {
        ul.appendChild(item); // This moves unchecked items to the end
    });
    checkedItems.forEach(function(item) {
        ul.appendChild(item); // This moves checked items to the end, maintaining their order
    });
    //console.log("groceries after rearranging: ", groceries);
    // Make an HTTP request to a server-side script
    fetch('rearrangeList.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ checkedItemsText: checkedItemsText, uncheckedItemsText: uncheckedItemsText })
    })
        .then(response => response.json())
        //.then(data => console.log(data))
        .catch((error) => {
            console.error('Error:', error);
        });
}

//helper functions that are used in the main functions

function uncheckAll(categoryID){
    var ul = document.querySelector("#"+categoryID);
    var items = Array.from(ul.children); // Convert to array for stable iteration
    items.forEach(function(item){
        var checkbox = item.querySelector('input[type="checkbox"]');
        if(checkbox){
            checkbox.checked = false;
        }
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

function toggleCategory(categoryID) {
    var wrapper = document.getElementById('category-wrapper-' + categoryID);
    if (wrapper) {
        wrapper.classList.toggle('collapsed');
    }
}

function deleteCheckedItems(categoryID, categoryName) {
    var ul = document.querySelector("#" + categoryID);
    var items = Array.from(ul.querySelectorAll('li'));
    
    // Filter to only checked items
    var checkedItems = items.filter(function(item) {
        var checkbox = item.querySelector('input[type="checkbox"]');
        return checkbox && checkbox.checked;
    });
    
    if (checkedItems.length === 0) {
        alert('No checked items to delete in ' + categoryName);
        return;
    }
    
    // Ask for confirmation
    if (!confirm("Are you sure you want to delete " + checkedItems.length + " checked item(s) in '" + categoryName + "'? This cannot be undone.")) {
        return;
    }
    
    // Get all checked item IDs to delete
    var itemIDsToDelete = [];
    checkedItems.forEach(function(item) {
        itemIDsToDelete.push(item.id);
    });
    
    // Remove from DOM
    checkedItems.forEach(function(item) {
        item.remove();
    });
    
    // Remove from groceries array
    groceries = groceries.filter(function(grocery) {
        return !itemIDsToDelete.includes(grocery.id);
    });
    
    // Check if there are any items left, hide buttons if not
    var remainingItems = ul.querySelectorAll('li');
    if (remainingItems.length === 0) {
        document.querySelector("#" + categoryID + " .list-action-buttons").classList.add("invisible");
    }
    
    // Make an HTTP request to delete checked items from database
    fetch('deleteAllItems.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ categoryID: categoryID, itemIDs: itemIDsToDelete })
    })
    .then(response => response.json())
    .then(data => console.log('Deleted checked items:', data))
    .catch((error) => {
        console.error('Error:', error);
    });
}

// Modal functions for item details
var currentItemID = null;
var uploadedImageFile = null;

function openItemModal(itemID) {
    currentItemID = itemID;
    var item = groceries.find(g => g.id === itemID);
    
    if (!item) return;
    
    document.getElementById('modalItemName').textContent = item.item;
    document.getElementById('itemDescription').value = item.description || '';
    
    // Handle image display
    var previewImg = document.getElementById('previewImg');
    var noImageText = document.getElementById('noImageText');
    var removeBtn = document.getElementById('removeImageBtn');
    
    if (item.image_url && item.image_url !== '') {
        previewImg.src = item.image_url;
        previewImg.style.display = 'block';
        noImageText.style.display = 'none';
        removeBtn.style.display = 'inline-block';
    } else {
        previewImg.style.display = 'none';
        noImageText.style.display = 'block';
        removeBtn.style.display = 'none';
    }
    
    uploadedImageFile = null;
    document.getElementById('imageUpload').value = '';
    document.getElementById('itemDetailsModal').style.display = 'block';
}

function closeItemModal() {
    document.getElementById('itemDetailsModal').style.display = 'none';
    currentItemID = null;
    uploadedImageFile = null;
}

function handleImageUpload(event) {
    var file = event.target.files[0];
    if (file) {
        console.log('=== IMAGE UPLOAD DEBUG ===');
        console.log('File name:', file.name);
        console.log('File type:', file.type);
        console.log('File size:', file.size, 'bytes');
        console.log('File extension:', file.name.split('.').pop().toLowerCase());
        
        uploadedImageFile = file;
        var reader = new FileReader();
        reader.onload = function(e) {
            console.log('File read successfully');
            var previewImg = document.getElementById('previewImg');
            var noImageText = document.getElementById('noImageText');
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            noImageText.style.display = 'none';
            document.getElementById('removeImageBtn').style.display = 'inline-block';
        };
        reader.onerror = function(e) {
            console.error('FileReader error:', e);
        };
        reader.readAsDataURL(file);
    } else {
        console.log('No file selected');
    }
}

function removeImage() {
    uploadedImageFile = null;
    document.getElementById('imageUpload').value = '';
    document.getElementById('previewImg').style.display = 'none';
    document.getElementById('noImageText').style.display = 'block';
    document.getElementById('removeImageBtn').style.display = 'none';
    
    // Mark for deletion on save
    var item = groceries.find(g => g.id === currentItemID);
    if (item) {
        item.image_url = '';
    }
}

function saveItemDetails() {
    if (!currentItemID) return;
    
    var description = document.getElementById('itemDescription').value;
    var item = groceries.find(g => g.id === currentItemID);
    
    if (!item) return;
    
    console.log('=== SAVING ITEM DETAILS ===');
    console.log('Item ID:', currentItemID);
    console.log('Description:', description);
    console.log('Has uploaded file:', !!uploadedImageFile);
    if (uploadedImageFile) {
        console.log('Uploaded file name:', uploadedImageFile.name);
        console.log('Uploaded file type:', uploadedImageFile.type);
        console.log('Uploaded file size:', uploadedImageFile.size);
    }
    
    var formData = new FormData();
    formData.append('itemID', currentItemID);
    formData.append('description', description);
    
    if (uploadedImageFile) {
        formData.append('image', uploadedImageFile);
        console.log('Image appended to FormData');
    } else if (item.image_url === '') {
        formData.append('removeImage', 'true');
        console.log('Remove image flag set');
    }
    
    console.log('Sending request to saveItemDetails.php...');
    
    fetch('saveItemDetails.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            item.description = description;
            if (data.image_url !== undefined) {
                item.image_url = data.image_url;
            }
            console.log('Details saved successfully');
            // Update the indicator
            updateItemIndicator(currentItemID);
            closeItemModal();
        } else {
            console.error('Save failed:', data.message);
            alert('Error saving details: ' + data.message);
        }
    })
    .catch((error) => {
        console.error('Fetch error:', error);
        alert('Error saving details');
    });
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    var modal = document.getElementById('itemDetailsModal');
    if (event.target == modal) {
        closeItemModal();
    }
}

function updateItemIndicator(itemID) {
    var item = groceries.find(g => g.id === itemID);
    if (!item) return;
    
    var listItem = document.getElementById(itemID);
    if (!listItem) return;
    
    var itemTextSpan = listItem.querySelector('.item-text');
    if (!itemTextSpan) return;
    
    var hasDetails = (item.image_url && item.image_url !== '') || (item.description && item.description !== '');
    var existingIndicator = itemTextSpan.querySelector('.details-indicator');
    
    if (hasDetails && !existingIndicator) {
        // Add indicator
        var indicator = document.createElement('span');
        indicator.className = 'details-indicator';
        itemTextSpan.insertBefore(indicator, itemTextSpan.firstChild);
    } else if (!hasDetails && existingIndicator) {
        // Remove indicator
        existingIndicator.remove();
    }
}