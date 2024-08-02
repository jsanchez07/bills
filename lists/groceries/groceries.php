<html>
<head>
    <link rel="stylesheet" type="text/css" href="groceries.css"/>
    <script src="groceries.js"></script>
</head>

<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
    //echo "the errors are turned on <br />";
    
    require('dbConfig.php');
   
    $con = mysqli_connect($localhost, $DBusername, $DBpassword, $database);
    if(!$con){
        echo("Failed to connect to the database");
    }
    ?>
    <script>
        //instantiate the categories and groceries arrays
        var categories = [];
        var groceries = [];
    </script>
    <?php
    $categoriesQuery = "SELECT id, category_name from Categories";
    $categoriesResult = mysqli_query($con, $categoriesQuery);
    $numCategories = mysqli_num_rows($categoriesResult);

    $beginningCategoriesResult = mysqli_query($con, $categoriesQuery);
    $numBeginningCategories = mysqli_num_rows($beginningCategoriesResult);

    $groceriesQuery="SELECT id, isChecked, item, category, category_id FROM Groceries";
    $objectGroceriesListResult = mysqli_query($con, $groceriesQuery);
    $numObjectGroceriesList = mysqli_num_rows($objectGroceriesListResult);
    
    //the loop to populate the categories array
    for ($i=0; $i<$numBeginningCategories; $i++){
        $beginningCategoriesRow = mysqli_fetch_array($beginningCategoriesResult);
        $beginningCategoryName = $beginningCategoriesRow['category_name'];
        $beginningCategoryID = $beginningCategoriesRow['id'];
        ?>
        <script>
            categories.push(new Categories("<?php echo $beginningCategoryID ?>", "<?php echo $beginningCategoryName; ?>"));
        </script>
   <?php   
    }
    //the loop to populate the groceries array
    for($j=0; $j<$numObjectGroceriesList; $j++){
        $objRow = mysqli_fetch_array($objectGroceriesListResult);
    ?>
        <script>
            groceries.push(new Groceries("<?php echo $objRow['id']; ?>", "<?php echo $objRow['item']; ?>", <?php echo $objRow['isChecked']; ?>, "<?php echo $objRow['category']; ?>", "<?php echo $objRow['category_id']; ?>"));
       </script>
    <?php   
        }
    ?>
    <body>
        <h1>Lists</h1>
        <div id = "group-of-lists">
            <script>
                var groupOfLists = document.querySelector("#group-of-lists");
                for(i=0; i<categories.length; i++){
                    categoryID = categories[i].id;
                    categoryName = categories[i].category_name;
                    numItemsinCategory = 0;
                    var addToListTextboxID = "add-to-list-"+categoryID;

                    // Create a new div element with class 'list-wrapper'
                    var listWrapper = document.createElement("div");
                    listWrapper.classList.add("list-wrapper");
                   
                    // Create the list div and append it to the listWrapper
                    //var listDiv = document.createElement('div');
                    //listDiv.className = 'list';
                     // Assuming you have a unique ID for each category

                    //listDiv.innerHTML += "<div class='heading-and-buttons'><h2>"+categoryName+"</h2><div class='move-list'><button id = '"+categoryID+"-up'></button><button id = '"+categoryID+"-down'></button></div></div>";
                    var headingAndButtons = document.createElement('div');
                    headingAndButtons.className = 'heading-and-buttons';

                    var heading = document.createElement('button');
                    heading.textContent = categoryName;
                    heading.id = "heading-"+categoryID;
                    heading.className = 'heading';
                    
                    var toHideDiv = document.createElement('div');
                    toHideDiv.className = 'to-hide';
                    toHideDiv.id = "toHide-"+categoryID;

                    var theList = document.createElement('ul');
                    theList.id = categoryID;

                    // Add click event listener to the heading
                   // heading.onclick= function() {
                    //    hideList(categoryID);
                    //};
                    heading.onclick = (function(capturedCategoryID) {
                        return function() {
                            hideList(capturedCategoryID);
                        };
                    })(categoryID);

                    var buttonsDiv = document.createElement('div');
                    buttonsDiv.className = 'move-list';

                    var upButton = document.createElement('button');
                    upButton.id = categoryID + "-up";

                    var downButton = document.createElement('button');
                    downButton.id = categoryID + "-down";

                    buttonsDiv.appendChild(upButton);
                    buttonsDiv.appendChild(downButton);
                    headingAndButtons.appendChild(heading);
                    headingAndButtons.appendChild(buttonsDiv);
                    listWrapper.appendChild(headingAndButtons);


                    //listWrapper.appendChild(listDiv);
                    //<ul id='"+ categoryID+"'><button class='uncheck-all-button' onclick='uncheckAll(\""+categoryID+"\")'>Uncheck All</button>
                    
                    var uncheckAllButton = document.createElement('button');
                    uncheckAllButton.className = 'uncheck-all-button';
                    uncheckAllButton.innerHTML = 'Uncheck All';
                    uncheckAllButton.onclick = (function(uncheckCapturedCategoryID) {
                        return function() {
                            uncheckAll(uncheckCapturedCategoryID);
                        };
                    })(categoryID);

                    theList.appendChild(uncheckAllButton);
                    
                    toHideDiv.appendChild(theList);


                    for(j=0; j<groceries.length; j++){
                        //write each li element for each item in the groceries array                     
                        if(categories[i].category_name == groceries[j].category){
                            var itemID  = groceries[j].id;
                            if(groceries[j].isChecked == 1){
                                var listItem = document.createElement('li');
                                listItem.id = itemID;
                                listItem.innerHTML = `<input class='list-checkbox' type='checkbox' onchange='rearrangeList("${categoryID}")' checked>${groceries[j].item}<button class='remove-item-button' onclick='removeThisItem("${itemID}")'>x</button>`;
                                theList.appendChild(listItem);

                                //document.querySelector("#"+categoryID).innerHTML += "<li id='"+itemID+"'><input class='list-checkbox' type='checkbox' onchange='rearrangeList(\""+categoryID+ "\")' checked>"+groceries[j].item+"<button class='remove-item-button' onclick='removeThisItem(\""+itemID+"\")'>x</button></li>";
                            }
                            else{
                                var listItem = document.createElement('li');
                                listItem.id = itemID;
                                listItem.innerHTML = `<input class='list-checkbox' type='checkbox' onchange='rearrangeList("${categoryID}")'>${groceries[j].item}<button class='remove-item-button' onclick='removeThisItem("${itemID}")'>x</button>`;
                                theList.appendChild(listItem);

                                //document.querySelector("#"+categoryID).innerHTML += "<li id='"+itemID+"'><input class='list-checkbox' type='checkbox' onchange='rearrangeList(\""+categoryID+ "\")'>"+groceries[j].item+"<button class='remove-item-button' onclick='removeThisItem(\""+itemID+"\")'>x</button></li>";
                            }
                            numItemsinCategory++;
                        }
                    }
            
                    document.write(`</ul>`);

                     // Create the actions div and append it to the listWrapper
                    var actionsDiv = document.createElement('div');
                    actionsDiv.className = 'actions';
                    actionsDiv.innerHTML = `<input type='text' class='add-item-textbox' id='${addToListTextboxID}' placeholder='Add an item'>
                                            <button class='add-item-button' onclick='addToList(document.querySelector("#${addToListTextboxID}").value, "${categoryID}", "${escapeHTML(categoryName)}"); document.querySelector("#${addToListTextboxID}").focus()'>Add</button>
                                            <div class='error-message' id='error-${categoryID}'></div>`;
                    
                    toHideDiv.appendChild(actionsDiv);
                    listWrapper.appendChild(toHideDiv);

                    // Append the listWrapper to the groupOfLists container
                    document.querySelector('#group-of-lists').appendChild(listWrapper);
            
                    //write the actions div at the bottom of each list to add a new item
                   
                    if(numItemsinCategory == 0){
                        //document.querySelector("#"+categoryID).removeClass("visible");
                        document.querySelector("#"+categoryID+" button").classList.add("invisible");
                    }
                    else if (numItemsinCategory > 0){
                        document.querySelector("#"+categoryID+" button").classList.remove("invisible");
                        //document.querySelector("#"+categoryID).addClass("visible");
                    }
                     rearrangeList(categoryID);
                }
                    //console.log(categories);
                    //console.log(groceries);
            </script>     
        </div>

        <!-- The categories actions div to add and delete categories -->
        <h1>Categories Section</h1>
        <div class = "category_actions">
            <input type='text' id="newCategory" placeholder="Add a category">
            <button id= "add-category" onclick="addCategory(newCategory.value)">Add Category</button>
            <div class = 'error-message' id='error-newCategory'></div>
        </div>
        <div class = "category_actions">
            <select id='category-dropdown'>
                <script>
                    decorateDeleteCategoryDropdown();
                </script>
            </select>
            <button id= "delete-category-button" onclick="deleteCategory(document.querySelector('#category-dropdown').selectedOptions[0].id)">Delete Category</button>
        </div>
        <?php mysqli_close($con);?>
    </body>
</html>