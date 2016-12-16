<?php 
 
include("config.php");
 
$output = array('success' => false, 'messages' => array());
 
$memberId = $_POST['member_id'];
 
$sql = "DELETE FROM prices WHERE ID = {$memberId}";
$query = $conn->query($sql);
if($query == TRUE) {
    $output['success'] = true;
    $output['messages'] = 'Successfully removed';
} else {
    $output['success'] = false;
    $output['messages'] = 'Error while removing the member information';
}
 
// close database connection

 
echo json_encode($output);

?>