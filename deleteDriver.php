<?php 

include("config.php");
include("/src/Firebase.php");
	$email = $_REQUEST["email"];
	$tableName = 'drivers'; 
	
	
	
		
	$getTokenSql = " SELECT ID,GCMID FROM  drivers WHERE email=? " ;
	
	$getTokenStatement = $conn->prepare($getTokenSql);
	$getTokenStatement->execute(array($email));
	$GCMID_ID = $getTokenStatement->fetch();
	
	$GCMID = $GCMID_ID['$GCMID'];
	$ID = $GCMID_ID['ID'];
	
	$sql = " DELETE FROM cars WHERE driverID = ?" ;
	$stml = $conn->prepare($sql);
	$stml->execute(array($ID));
	
	
	$firebaseData = array("status" => "3");
	Firebase::sendData($firebaseData,$GCMID,"driver");
	
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