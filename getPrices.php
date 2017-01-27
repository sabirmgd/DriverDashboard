<?php
include("config.php");
// added a comment 

$output = array('data' => array());
  $sql = 'SELECT * FROM prices';
    foreach ($conn->query($sql) as $row) {
       
$removeButton = '<a type="button" data-toggle="modal" data-target="#removeMemberModal" onclick="removeMember('.$row['ID'].')"> <span class="glyphicon glyphicon-trash"></span> </a>
				<a type="button" data-toggle="modal" data-target="#editMemberModal" onclick="editMember('.$row['ID'].')"> <span class="glyphicon glyphicon-edit"></span></a>' ;
$startTime  = date("g:i a", strtotime($row['startTime']));
$endTime   = date("g:i a", strtotime($row['endTime']));
    $output['data'][] = array(
        $row['ID'],
        $startTime ,
        $endTime,
        $row['perkm'],
		$row['min'],
		$removeButton
    );
    }
 
 
   

 
// database connection close

 
echo json_encode($output);



?>