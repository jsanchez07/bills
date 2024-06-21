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
        var categories = [];
    </script>
    <?php
    $categoriesQuery = "SELECT * from Categories";
    $categoriesResult = mysqli_query($con, $categoriesQuery);
    $numCategories = mysqli_num_rows($categoriesResult);

    $beginningCategoriesResult = mysqli_query($con, $categoriesQuery);
    $numBeginningCategories = mysqli_num_rows($beginningCategoriesResult);
    for ($i=0; $i<$numBeginningCategories; $i++){
        $beginningCategoriesRow = mysqli_fetch_array($beginningCategoriesResult);
        $beginningCategory = $beginningCategoriesRow['category_name'];
        ?>

        <script>
            categories.push("<?php echo $beginningCategory; ?>");
        </script>
   <?php    
    }




?>
    <body>
        <div id = "group-of-lists">
                <?php
                    for($i=0; $i<$numCategories; $i++){
                        $catRow = mysqli_fetch_array($categoriesResult);
                        $currentCategory =  $catRow['category_name'];
                        echo "<div class = 'list'>";
                        echo "<h2>".$catRow['category_name']."</h2>";
                        echo "<ul id ='".$catRow['category_name']."-list' >";
                        
                        $query="SELECT isChecked, item, category FROM Groceries WHERE category = '".$currentCategory."'";
                        $result=mysqli_query($con, $query);
                        $num=mysqli_num_rows($result);
                        for($j=0; $j<$num; $j++){
                            $row = mysqli_fetch_array($result);
                            $idForItem = str_replace(" ", "-", $row['item']);
                            if($row['isChecked'] == 1 && $row['category'] == $currentCategory){
                                echo "<li id = '$idForItem'><input class = 'list-checkbox' type='checkbox' checked>". $row['item']." category: "."<button class='remove-item-button' onclick='removeThisItem(\"".$idForItem."\")'>x</button></li>";
                            }
                            elseif($row['category'] == $currentCategory){
                                echo "<li id = '$idForItem'><input class = 'list-checkbox' type='checkbox'>". $row['item']."<button class='remove-item-button' onclick='removeThisItem(\"".$idForItem."\")'>x</button></li>"; 
                            }
                            else{
                                //do nothing
                            }
                        }
                        $buttonID = "item-".$catRow['category_name'];
                    ?>
                        </ul>
                        </div>
                        <div class = 'actions'>
                        <input type='text' class = 'add-item-textbox' id='<?php echo $buttonID?>' placeholder='Add an item hoe'>
                        <button class='add-item-button' onclick='addToList(document.querySelector("#<?php echo $buttonID; ?>").value, "<?php echo $buttonID; ?>"); document.querySelector("#<?php echo $buttonID; ?>").focus()'>Add</button>
                        <div class = 'error-message' id = 'error-<?php echo $buttonID?>'></div>
                    </div>
                        
                    <?php } ?>
                
                    </ul>
                </div>
        </div>
        <div class = "category_actions">
            <input type='text' id="newCategory" placeholder="Add a category">
            <button id= "add-category" onclick="addCategory()">Add Category</button>
        </div>
        <div class = "category_actions">
            <?php
                $delCatResult = mysqli_query($con, $categoriesQuery);
                echo "<select id='category-dropdown'>";
                while ($delCatRow = mysqli_fetch_array($delCatResult)) {
                    echo "<option id = '".$delCatRow['category_name']."' value='" . $delCatRow['category_name'] . "'>" . $delCatRow['category_name'] . "</option>";
                }
                ?>
            </select>
            <button id= "delete-category-button" onclick="deleteCategory(category-dropdown.value)">Delete Category</button>
        </div>
        <?php mysqli_close($con);?>
    </body>
</html>