<html>
<head>
    <link rel="stylesheet" type="text/css" href="groceries.css"/>
    <script src="groceries.js"></script>
</head>

<?php
    echo "I am here";
    require('dbConfig.php');
    echo "after require";
    $con = mysqli_connect($localhost,$DBusername,$DBpassword, $database);
    echo "after getting con";
    $query="SELECT * FROM groceries order by store";
    $result=mysqli_query($con, $query);
    echo "after getting result";
    $num=mysqli_num_rows($result);
    echo "obvously not getting here";
    echo $num;
    for($i=0; $i<$num; $i++){
        $row = mysqli_fetch_array($result);
        echo row[1];
        echo row[2];
        echo "row[0]";
        echo row['item'];
        echo row[item];
        
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