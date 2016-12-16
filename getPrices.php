<?php
include("config.php");


$output = array('data' => array());
  $sql = 'SELECT * FROM prices';
    foreach ($conn->query($sql) as $row) {
       
$removeButton = '<a type="button" data-toggle="modal" data-target="#removeMemberModal" onclick="removeMember('.$row['ID'].')"> <span class="glyphicon glyphicon-trash"></span> </a>' ;

    $output['data'][] = array(
        $row['ID'],
        $row['startTime'],
        $row['endTime'],
        $row['perkm'],
		$removeButton
    );
    }
 
 
   

 
// database connection close

 
echo json_encode($output);



?>