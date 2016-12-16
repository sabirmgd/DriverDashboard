<?php 

include("config.php");
include("/src/Firebase.php");
	$email = $_REQUEST["email"];
	$tableName = 'drivers'; 
	
	
	 function getRegistrationTokenUsingEmail ($email,$tableName)	{
		
		include("config.php");
	$getTokenSql = " SELECT GCMID FROM  $tableName WHERE email=? " ;
	//echo $getTokenSql;
	$getTokenStatement = $conn->prepare($getTokenSql);
	$getTokenStatement->execute(array($email));
	$GCMID = $getTokenStatement->fetch()['GCMID'];
	return $GCMID;
	}	
	
	
	$GCMID = getRegistrationTokenUsingEmail ($email,$tableName);
	echo $GCMID;
	$sql = " DELETE FROM drivers WHERE email = ?" ;
	$stml = $conn->prepare($sql);
	$stml->execute(array($email));


// password too short 
// screenshot (menu not showing)
// logout (doesn't log out)
// edit phone no.
// price (sabir)
// location (tell Islam to make back to normal)
// Islam Bug 
// routing 
// versioning


?>