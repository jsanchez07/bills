<html>
<head>
    <link rel="stylesheet" type="text/css" href="groceries.css"/>
    <script src="groceries.js"></script>
</head>

<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
    echo "the errors are turned on <br />";
    //echo "i made it to this page, groceries.php <br />";
    require('dbConfig.php');
   
    $con = mysqli_connect($localhost, $DBusername, $DBpassword, $database);
    if(!$con){
        echo("Failed to connect to the database");
    }
    $categoriesQuery = "SELECT * from Categories";
    $categoriesResult = mysqli_query($con, $categoriesQuery);
    $numCategories = mysqli_num_rows($categoriesResult);
    echo "the number of categories is: ".$numCategories;


?>
    <body>
  
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
                            if($row['isChecked'] == 1 && $row['category'] == $currentCategory){
                                echo "<li id = ".$row['item']."><input type='checkbox' checked>". $row['item']." category: "."<button id= 'removeThis' onclick='removeThisItem(".$row['item'].")'>x</button></li>";
                            }
                            elseif($row['category'] == $currentCategory){
                                echo "<li id = ".$row['item']."><input type='checkbox'>". $row['item']."<button id= 'removeThis' onclick='removeThisItem(".$row['item'].")'>x</button></li>";
                            }
                            else{
                                //do nothing
                            }
                        }
                        echo "</ul>";
                        echo "</div>";
                        echo "<div class = 'actions'>";
                        echo "<input type='text' id='item' placeholder='Add a grocery item'>";
                       
                        echo "<button id='add' onclick='addToList(item.value)'>Add</button>";
                       
                    echo"</div>";
                        
                    }
                    mysqli_close($con);
                ?>
            </ul>
        </div>
        <div class = "category_actions">
            <input type='select' id="category" placeholder="Add a category">
            <button id= "remove" onclick="removeFromList()">Remove</button>
        </div>
    </body>
</html>