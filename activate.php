<?php 
function adminActiveDriver ($email,$active)
{include("config.php");
	
	$sql = " UPDATE drivers SET adminActive = ? WHERE email = ? " ;
	$stml = $conn->prepare($sql);
	$stml->execute(array($active,$email));
}
$email = $_REQUEST["email"];
adminActiveDriver($email,"0");

?>