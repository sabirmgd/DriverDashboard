<?php 
include("config.php");
$validatorr = array('success' => false, 'messages' => array());
 
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $price = $_POST['price'];
	$minPrice = $_POST['minimumPrice'];
 
    $sql = "INSERT INTO prices (startTime, endTime, perkm, permin,min) VALUES ('$startTime', '$endTime', '$price', '0',$minPrice)";
    $query = $conn->query($sql);
 
    if($query == TRUE) {           
        $validator['success'] = true;
        $validator['messages'] = "Successfully Added";      
    } else {        
        $validator['success'] = false;
        $validator['messages'] = "Error while adding the member information";
    }
 
    // close the database connection
    
 
    echo json_encode($validator);
 

?>