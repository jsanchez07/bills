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
    $categoriesQuery = "SELECT id, category_name, order_index from Categories ORDER BY order_index";
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
        $beginningCategoryOrderIndex = $beginningCategoriesRow['order_index'];
        ?>
        <script>
            categories.push(new Categories("<?php echo $beginningCategoryID ?>", "<?php echo $beginningCategoryName; ?>", "<?php echo $beginningCategoryOrderIndex; ?>"));
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

                     // Create the wrapper div
                     var wrapperDiv = document.createElement('div');
                    wrapperDiv.id = 'category-wrapper-'+categoryID;

                    // Create the list div
                    var listDiv = document.createElement('div');
                    listDiv.className = 'list';
                    listDiv.innerHTML = "<div class='heading-and-buttons'><h2>" + categoryName + "</h2><div class='move-list'><button id='" + categoryID + "-up' onclick='moveCategoryUp(\"" + categoryID + "\")'></button><button id='" + categoryID + "-down' onclick='moveCategoryDown(\"" + categoryID + "\")'></button></div></div><ul id='" + categoryID + "'><button class='uncheck-all-button' onclick='uncheckAll(\"" + categoryID + "\")'>Uncheck All</button></ul>";

                    // Append list items to the list div
                    for (j = 0; j < groceries.length; j++) {
                        if (categories[i].category_name == groceries[j].category) {
                            var itemID = groceries[j].id;
                            var listItem = document.createElement('li');
                            listItem.id = itemID;
                            listItem.innerHTML = "<input class='list-checkbox' type='checkbox' onchange='rearrangeList(\"" + categoryID + "\")'" + (groceries[j].isChecked == 1 ? " checked" : "") + ">" + groceries[j].item + "<button class='remove-item-button' onclick='removeThisItem(\"" + itemID + "\")'>x</button>";
                            listDiv.querySelector('ul').appendChild(listItem);
                            numItemsinCategory++;
                        }
                    }
                    
               // Create the actions div
               var actionsDiv = document.createElement('div');
                    actionsDiv.className = 'actions';
                    actionsDiv.innerHTML = "<input type='text' class='add-item-textbox' id='" + addToListTextboxID + "' placeholder='Add an item'><button class='add-item-button' onclick='addToList(document.querySelector(\"#" + addToListTextboxID + "\").value, \"" + categoryID + "\", \"" + escapeHTML(categoryName) + "\"); document.querySelector(\"#" + addToListTextboxID + "\").focus()'>Add</button><div class='error-message' id='error-" + categoryID + "'></div>";

                    // Append the list and actions divs to the wrapper div
                    wrapperDiv.appendChild(listDiv);
                    wrapperDiv.appendChild(actionsDiv);

                    // Append the wrapper div to the group of lists
                    groupOfLists.appendChild(wrapperDiv);

                    //Clean the list up and hide the uncheck all button if there are no items in the category
                    rearrangeList(categoryID);
                    if(numItemsinCategory == 0){
                        document.querySelector("#"+categoryID+" button").classList.add("invisible");
                    }
                    else if (numItemsinCategory > 0){
                        document.querySelector("#"+categoryID+" button").classList.remove("invisible");
                    }
                }

                    console.log(categories);
                    console.log(groceries);
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