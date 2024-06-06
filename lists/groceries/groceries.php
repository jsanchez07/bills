<html>
<head>
    <link rel="stylesheet" type="text/css" href="groceries.css"/>
    <script src="groceries.js"></script>
</head>

<?php
    echo "I am here<br />";
    require('dbConfig.php');
    echo $localhost."<br />";
    echo $DBusername."<br />";
    echo $DBpassword."<br />";
    echo $database."<br />";
    echo "after require<br /><br />";
    $query="SELECT * FROM groceries order by store";
    echo "after query<br />";
    $connection = mysqli_connect($localhost, $DBusername, $DBpassword, $database) or die("Could not connect to database");
    echo "after getting con<br />";
    $result=mysqli_query($connection, $query);
    echo "after getting result<br />";
    $num=mysqli_num_rows($result);
    echo "obvously not getting here<br />";
    echo $num;
    for($i=0; $i<$num; $i++){
        $row = mysqli_fetch_array($result);
        echo row[1];
        echo row[2];
        echo "row[0]";
        echo row['item'];
        echo row[item];
        
        echo "this is a new change";
        $item=mysql_result($result_id,$i,"item");
        echo $item;
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
                        //echo "<li><input type='checkbox'>".$row[1]."</li>";
                        //echo "<li id = $row[id]><input type="checkbox"> $row[1]</li>";
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