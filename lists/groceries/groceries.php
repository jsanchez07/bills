<html>
<head>
    <link rel="stylesheet" type="text/css" href="groceries.css"/>
    <script src="groceries.js"></script>
</head>

<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
    echo "the errors are turned on<br />";
    require('dbConfig.php');
    $query="SELECT * FROM Groceries order by category";
    $con = mysqli_connect($localhost, $DBusername, $DBpassword, $database);

    $result=mysqli_query($con, $query);

    $num=mysqli_num_rows($result);

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
    mysqli_close($con);

?>

    <body>

        <p>
            Hello this is a list of groceries
        </p>
        <div class = "list">
            <ul id = "theList">
                <?php
                    for($i=0; $i<$num; $i++){
                        $row = mysqli_fetch_array($result);
                        echo "<li><input type='checkbox'>".$row[$i]."</li>";
                        echo "<li id = $row[$i]><input type='checkbox'>". $row[1]."</li>";
                    }
                ?>
            </ul>
        </div>
        <div class = "actions">
            <input type="text" id="item" placeholder="Add a grocery item">
            <button id="add" onclick="addToList(item.value)">Add</button>
            <button id= "remove" onclick="removeFromList()">Remove</button>
        </div>
    </body>
</html>