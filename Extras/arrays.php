<?php /*

$db = mysqli_connect('localhost', 'root', '', 'test'); 

$main = array(array(), array(), array());
$upcoming = array(array(), array(), array());

$i = 0;
$prev_id = 0;
$id = 0;
$a = 0;


$result  = mysqli_query($db,"SELECT eventid FROM events WHERE event_type='main'");

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['eventid'];
        if ($id == $prev_id) {
            continue;
        }
        $prev_id = $id;
    }
} else {
    echo "0 results";
}

$result_main = mysqli_query($db, "SELECT event_name, event_desc, event_time FROM events WHERE event_type='main' and eventid = $id");

do { 
    $i ++;
    if ($id == $row['eventid']) { 
        if ($result_main) {
            $row = mysqli_fetch_assoc($result_main);
            $main[$i][0] = $row['event_name'];
            $main[$i][1] = $row['event_desc'];
            $main[$i][2] = $row['event_time'];
            $i++;
    
        } else {
            $main[$i][0] = "Error: " . mysqli_error($db);
            $main[$i][1] = "Error: " . mysqli_error($db);
            $main[$i][2] = "Error: " . mysqli_error($db);
            $i++;
        } 
        }
    else {
        
            
    }
    
    if ($id == $prev_id) {
        
    }
    
} while ($i < 3);

for ($i = 0; $i < 3; $i++) {

    $result_upcoming = mysqli_query($db, "SELECT event_name, event_desc, event_time FROM events  WHERE event_type='upcoming'");
    if ($result_upcoming) {
        $row = mysqli_fetch_assoc($result_upcoming);
        $upcoming[$i][0] = $row['event_name'];
        $upcoming[$i][1] = $row['event_desc'];
        $upcoming[$i][2] = $row['event_time'];
    } else {
        $upcoming[$i][0] = "Error: " . mysqli_error($db);
        $upcoming[$i][1] = "Error: " . mysqli_error($db);
        $upcoming[$i][2] = "Error: " . mysqli_error($db);
    }
}
*/
?>
