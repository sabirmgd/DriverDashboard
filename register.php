<?php
include("config.php");
session_start();
	
   define("MAX_LENGTH", 6);
   $error = "";
 function areAllParametersSet ($data,$parametersArray)
{	$areSet = 1;
	$parameterArrayLength = count ($parametersArray);
	
	for($i = 0; $i < $parameterArrayLength; $i++) {
    if (! isset ($data[$parametersArray[$i]])){$areSet = 0; break; }
	}
	
return $areSet; 	
}
   
   
function filterRequestParameters ($data,$parametersArray)
{
	$filteredData=[];
	$parameterArrayLength = count ($parametersArray);

	for($i = 0; $i < $parameterArrayLength; $i++) {
    $filteredData[$parametersArray[$i]] = filter_var($data[$parametersArray[$i]] ,FILTER_SANITIZE_STRING);
	}

	return $filteredData;
}
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
	  
	  
	 
	 
	  $sql  = "INSERT INTO admins (email, password, PasswordSalt ) VALUES (?, ?, ?)";
	  $stmt = $conn->prepare($sql) ;
      $email = filter_var( $_POST['email'] ,FILTER_SANITIZE_STRING) ;
	 // $_POST['password']
      $mypassword = filter_var($_POST['password'] ,FILTER_SANITIZE_STRING) ;
      $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
	  $intermediateSalt = md5(uniqid(rand(), true));
      $salt = substr($intermediateSalt, 0, MAX_LENGTH);
      $mypassword = hash("sha256", $mypassword . $salt);
	  
	  //echo $salt;
	  //echo $$mypassword;
	  //$sql  = "INSERT INTO tblTestLogin (userName, password, PasswordSalt ) VALUES (?, ?, ?)";
	  
	    
	 //$stmt->bind_param('sss',$email, $mypassword, $salt);
	 $stmt->execute(array($email,  $hash , $salt));
	  
	$conn = null;	
	  
	  if ($stmt) {
    echo "you are succesfully registered";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


	  

   }
?>
<html>
   
   <head>
      <title>register Page</title>
      
      <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         
         .box {
            border:#666666 solid 1px;
         }
      </style>
      
   </head>
   
   <body bgcolor = "#FFFFFF">
	
      <div align = "center">
         <div style = "width:300px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>register</b></div>
				
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  <label>email  :</label><input type = "text" name = "email" class = "box"/><br /><br />
                  <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
                  <input type = "submit" value = " Submit "/><br />
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
					
            </div>
				
         </div>
			
      </div>

   </body>
</html>