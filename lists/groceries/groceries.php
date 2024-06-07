<html>
<head>
    <link rel="stylesheet" type="text/css" href="groceries.css"/>
    <script src="groceries.js"></script>
</head>

<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
    echo "the errors are turned on <br />";
    require('dbConfig.php');
    $query="SELECT * FROM Groceries order by category";
    $con = mysqli_connect($localhost, $DBusername, $DBpassword, $database);

    $result=mysqli_query($con, $query);

    $num=mysqli_num_rows($result);
    /*
    for($i=0; $i<$num; $i++){
        $row = mysqli_fetch_array($result);
        echo "<br />";
        echo $row['item'];
        echo "<br />";
        echo $row['category'];
        echo "<br />";
        echo $row['isChecked'];
        echo "<br />";
        
        
       // $item=mysql_result($result_id,$i,"item");
        //echo $item;
    }
        */
   

?>
<!--<script>
    var arrOfItems = [];

    

    for(var i = 0; i < <?php// echo $num; ?>; i++){
        <?php// $row = mysqli_fetch_array($result); ?>
        var item = <?php //echo $row['item']; ?>
        console.log(item);
        var category = <?php //echo $row['category']; ?>;
        console.log(category);
        var isChecked = <?php //echo $row['isChecked']; ?>;
        console.log(isChecked);
        arrOfItems.push({item: item, category: category, isChecked: isChecked});
    }
    console.log(arrOfItems);
    
</script>-->
    <body>

        <p>
            Hello this is a list of groceries
        </p>
        <div class = "list">
            <ul id = "theList">
                <?php
                    for($i=0; $i<$num; $i++){
                        $row = mysqli_fetch_array($result);
                        if($row['isChecked'] == 1){
                            echo "<li id = ".$row['item']."><input type='checkbox' checked>". $row['item']." category: ".$row['category']."<button id= 'remove' onclick='removeThisItem(".$row['item'].")'>Remove</button></li>";
                        }
                        else{
                            echo "<li id = ".$row['item']."><input type='checkbox'>". $row['item']." category: ".$row['category']."</li>";
                        }
                    }
                    mysqli_close($con);
                ?>
            </ul>
        </div>
        <div class = "actions">
            <input type="text" id="item" placeholder="Add a grocery item">
            <input type='select' id="category" placeholder="Add a category">
            <button id="add" onclick="addToList(item.value)">Add</button>
            <button id= "remove" onclick="removeFromList()">Remove</button>
        </div>
    </body>
</html>