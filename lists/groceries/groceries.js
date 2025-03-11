
function Groceries(id, item, isChecked, category, categoryID, order_index) {
    this.id = id;
    this.item = item;
    this.isChecked = isChecked;
    this.category = category;
    this.categoryID = categoryID;
    this.order_index = order_index;
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

     // Determine the order_index for the new item
    const uncheckedItems = groceries.filter(item => item.categoryID === categoryID && !item.isChecked);
    console.log('Unchecked items:', uncheckedItems);

    const orderIndexes = uncheckedItems.map(item => item.order_index);
    console.log('Order indexes:', orderIndexes);

    const order_index = uncheckedItems.length > 0 ? Math.max(...orderIndexes) + 1 : 0;
    console.log('Order index:', order_index);

     // Add the new item to the groceries array
     groceries.push(new Groceries(itemID, newItem, 0, categoryName, categoryID, order_index));
 
     // Create the new list item element
     var li = document.createElement('li');
     li.setAttribute('id', itemID);
     li.innerHTML = `<input type='checkbox' class='list-checkbox' onclick="rearrangeList('${categoryID}')"> ${newItem}<button class='remove-item-button' onclick="removeThisItem('${itemID}')">x</button>`;
 
     // Append the new item to the bottom of the unchecked items list
     var ul = document.querySelector("#" + categoryID);
     ul.appendChild(li);
    
     //console.log("ul: ", ul);
     // Update the indexes of all items in the list
      //re-arrange the list
    rearrangeList(categoryID);

    updateIndexes(ul);

    // Create a variable with the index of all items inside the list along with the item ID
    const listItems = Array.from(ul.children).filter(item => item.tagName.toLowerCase() === 'li');
    const itemsWithIndex = listItems.map((item, index) => ({
        id: item.getAttribute('id'),
        index: index
       
    }));

    console.log('listItems:', listItems);   

    console.log('Items with index in the add:', itemsWithIndex);
     
     
    // Make an HTTP request to a server-side script
    fetch('addItem.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ item: newItem, categoryName: categoryName, id: itemID, categoryID: categoryID, itemsWithIndex: itemsWithIndex})
    })
    .then(response => response.json())
    //.then(data => console.log(data))
    .catch((error) => {
        console.error('Error:', error);
    });

    document.querySelector('#add-to-list-'+categoryID).value = "";
   
    //show the uncheck all button    
    document.querySelector("#"+categoryID+" button").classList.remove("invisible");
}

document.addEventListener('DOMContentLoaded', (event) => {
    enableDragAndDrop();
});

function enableDragAndDrop() {
    const lists = document.querySelectorAll('.list ul');
    lists.forEach(list => {
        new Sortable(list, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            filter: '.checked', // Prevent dragging of checked items
            onMove: function (evt) {
                const target = evt.related;
                // Prevent moving checked items and dropping on checked items
                return !target.classList.contains('checked') && !evt.dragged.classList.contains('checked');
            },
            onEnd: function (evt) {
                const itemEl = evt.item;  // dragged HTMLElement
                console.log("itemEl: ", itemEl);
                const fromIndex = Array.from(evt.from.children).filter(item => item.tagName.toLowerCase() === 'li').indexOf(itemEl);
                const toIndex = Array.from(evt.to.children).filter(item => item.tagName.toLowerCase() === 'li').indexOf(itemEl);


                // Check if the drop target is within the allowed area
                if (!isDropAllowed(itemEl, toIndex)) {
                  
                    evt.from.insertBefore(itemEl, fromIndex); // Revert the change
                    return;
                }

                // Update the order in the groceries array
                updateOrder(itemEl, fromIndex, toIndex);
            }
        });
    });
}

function updateOrder(itemEl, fromIndex, toIndex) {
    // Update the order in the groceries array based on the new positions
    const itemID = itemEl.getAttribute('id');
    const itemIndex = groceries.findIndex(item => item.id === itemID);
    const [movedItem] = groceries.splice(itemIndex, 1);
    groceries.splice(toIndex, 0, movedItem);

    // Create a variable with the index of all items inside the list along with the item ID
    const listItems = Array.from(itemEl.parentNode.children).filter(item => item.tagName.toLowerCase() === 'li');
    const itemsWithIndex = listItems.map((item, index) => ({
        id: item.getAttribute('id'),
        index: index
    }));


    // Update the indexes of all items in the list
    updateIndexes(itemEl.parentNode);

    // Optionally, update the order on the server
     fetch('updateOrder.php', {
         method: 'POST',
         headers: {
             'Content-Type': 'application/json',
         },
         body: JSON.stringify({ itemsWithIndex: itemsWithIndex })
     })
     //.then(response => response.json())
    //.then(data => console.log(data))
    .catch((error) => {
       // console.error('Update order error:', error);
    });
}

function isDropAllowed(itemEl, toIndex) {
    // Check if the drop target is not in the bottom area where checked checkboxes are moved
    const checkedItems = document.querySelectorAll('.list li input[type="checkbox"]:checked');
    const checkedItemsArray = Array.from(checkedItems).map(item => item.closest('li'));
    const listItems = Array.from(itemEl.parentNode.children).filter(item => item.tagName.toLowerCase() === 'li');
    let checkedItemsStartIndex = listItems.findIndex(item => checkedItemsArray.includes(item));
    // If no checked items are found, set checkedItemsStartIndex to a large number
    if (checkedItemsStartIndex === -1) {
        checkedItemsStartIndex = 10000;
    }

    console.log('Checked items:', checkedItems);
    console.log('CheckedItemsArray:', checkedItemsArray);
    console.log('List items:', listItems);
    console.log('Checked items start index:', checkedItemsStartIndex);
    console.log("toIndex: ", toIndex);
    console.log('result: ', toIndex < checkedItemsStartIndex);
    // Allow drop if the target index is before the first checked item
    return toIndex < checkedItemsStartIndex;
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

     // Update the indexes of all items in the list
     updateIndexes(parentUl);

     // Create a variable with the index of all items inside the list along with the item ID
    const listItems = Array.from(parentUl.children).filter(item => item.tagName.toLowerCase() === 'li');
    const itemsWithIndex = listItems.map((item, index) => ({
        id: item.getAttribute('id'),
        index: index
    }));

    
    // Make an HTTP request to a server-side script
     fetch('removeItem.php', {
        method: 'POST',
        body: JSON.stringify({ itemID: itemID, itemsWithIndex: itemsWithIndex }),
    })
    .then(response => response.json());
}

function updateIndexes(ul) {
    
    // Convert HTMLCollection to Array and log the unfiltered list
    const unfilteredListItems = Array.from(ul.children);

    // Filter to include only 'li' elements and log the filtered list
    const listItems = unfilteredListItems.filter(item => item.tagName.toLowerCase() === 'li');

    listItems.forEach((item, index) => {
        const itemID = item.getAttribute('id');
        const groceryItem = groceries.find(grocery => grocery.id === itemID);
        if (groceryItem) {
            groceryItem.order_index = index;
        }
    });

    //console.log('Updated indexes:', groceries);
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
    
    // Create the wrapper div
    var wrapperDiv = document.createElement('div');
    wrapperDiv.id = 'category-wrapper-' + newCategoryID;

    // Create the list div
    var listDiv = document.createElement('div');
    listDiv.className = 'list';
    listDiv.innerHTML = "<h2>" + categoryToAdd + "</h2><ul id='" + newCategoryID + "'></ul>";

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
    
    //console.log(categories);
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

    //console.log('categoryOrderIndexes:', categoryOrderIndexes);
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
   // console.log("moveCategoryUp", categoryID);
    
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

    //console.log('categoryOrderIndexes:', categoryOrderIndexes);

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
    //console.log("Category Order Map:", categoryOrderMap);
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
        //console.log(`Comparing ${idA} (order: ${orderA}) with ${idB} (order: ${orderB})`);

        return orderA - orderB;
    });

    // Append the sorted elements back to the groupOfLists
    categoryWrapperDivs.forEach((categoryWrapperDiv) => {
        groupOfLists.appendChild(categoryWrapperDiv);
        // Log the appended element for debugging
        let id = categoryWrapperDiv.getAttribute('id') ? categoryWrapperDiv.getAttribute('id').replace('category-wrapper-', '') : null;
        //console.log(`Appended ${id} to groupOfLists`);
    });
    decorateDeleteCategoryDropdown()

}

function moveCategoryDown(categoryID){
    //console.log("moveCategoryDown", categoryID);
    
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

    //console.log('categoryOrderIndexes:', categoryOrderIndexes);

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
        //console.log(`Comparing ${idA} (order: ${orderA}) with ${idB} (order: ${orderB})`);

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

function rearrangeList(listID) {
    let ul = document.querySelector("#" + listID);
    let items = Array.from(ul.children).filter(item => item.tagName.toLowerCase() === 'li'); // Convert to array and filter for only li items
    let checkedItems = [];
    let uncheckedItems = [];
    let checkedItemsText = [];
    let uncheckedItemsText = [];

    // First, separate items into checked and unchecked without modifying the DOM
    items.forEach(function(item) {
        var checkbox = item.querySelector('input[type="checkbox"]');
        
        console.log("item: ", item);
        console.log("checkbox: ", checkbox);
        console.log("checkbox.checked: ", checkbox.checked);

        if (checkbox && checkbox.checked) {
            item.classList.remove("unchecked");
            item.classList.add("checked");
            item.setAttribute('draggable', false); // Ensure draggable is set to false for checked items
            itemText = item.id.replace("#li", "");
            checkedItemsText.push(itemText);
            checkedItems.push(item);
            for (var i = 0; i < groceries.length; i++) {
                if (groceries[i].id == item.id) {
                    groceries[i].isChecked = 1;
                }
            }
        } else if (checkbox && !checkbox.checked) {
            item.classList.add("unchecked");
            item.classList.remove("checked");
            item.setAttribute('draggable', true); // Ensure draggable is set to true for unchecked items
            itemText = item.id.replace("#li", "");
            uncheckedItemsText.push(itemText);
            uncheckedItems.push(item);
            for (var i = 0; i < groceries.length; i++) {
                if (groceries[i].id == item.id) {
                    groceries[i].isChecked = 0;
                }
            }
        }
    });

    // Now, append unchecked items first, then checked items
    uncheckedItems.forEach(function(item) {
        ul.appendChild(item);
    });
    checkedItems.forEach(function(item) {
        ul.appendChild(item);
    });

    // Optionally, update the order in the groceries array
    updateIndexes(ul);

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