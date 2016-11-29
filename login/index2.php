<!DOCTYPE HTML>
<?php
   session_start();
   $configPath = $_SERVER['DOCUMENT_ROOT'];
	$configPath .= "/jts/config.php";
	include_once($configPath);
   define("MAX_LENGTH", 6);
   
  $error = "";

  
    $error = "";
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		// username and password sent from form 
      
      $myusername = mysqli_real_escape_string($conn,$_POST['LoginID']);
      $mypassword = mysqli_real_escape_string($conn,$_POST['UserPassword']); 
      $sql = "SELECT  `UserPassword` , `LastLogin`, `PasswordSalt` FROM `tblusers` WHERE LoginID = ? ";
      //echo  $myusername;
	  //$stmt = $conn->stmt_init();
	  if ( ! $stmt = $conn->prepare($sql) )
	  {
		  
		  print "Failed to prepare statement\n";
		  
		  
	  }
	else {
		$stmt->bind_param('s',$myusername);
		$stmt->execute();
		$stmt->bind_result($UserPassword,$LastLogin,$PasswordSalt);
		$stmt->fetch();
		
		
		$mypassword = hash("sha256", $mypassword . $PasswordSalt);
		if($mypassword == $UserPassword )
		{
		  //echo 'nigga';
		  
        // session_register("myusername");
         $_SESSION['login_user'] = $myusername;
         $_SESSION['timeout'] = time();
		 if ($LastLogin == NULL ){header ("location: ../firstTimeChangePassword.php");}
		 else {header("location: ../welcome.php");}
		} else {
			$error = "*Invalid user name or password";
		}
		 
	}
		
	
	  
	 
   
	  // compare the salted password to the password from the database
      
	$stmt->close();
	$conn->close();
	
	}
?>
<html>
   
<head>
	<title>Job Tracker | Login</title>
	<link rel="stylesheet" type="text/css" href="../styles/loginStyle.css">
</head>
   
<body>

	<div class="headerContainer">
		<div class="left">
			<img src="../images/weblogo_small.jpg" alt="logo" >
		</div>
		
		<div class="right">
			<h1 id="title">Job Tracker System</h1>
		</div>
	</div>
	<hr/>
	
	<br/><br/>
	
	<div class="bodyContainer">
 
		<h2>User Login</h2>
		<br/>
			   
        <form action = "" method = "post">
            <label>Login ID  :</label><input type = "text" name = "LoginID" class = "box"/>
			<br/><br/>
            <label>Password  :</label><input type = "password" name = "UserPassword" class = "box" />
			<br/>
            <div style = "font-size:14px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
            <div id="loginbtn">
				<input type = "submit" value = " Sign In "/>
			</div>
			<br/>
        </form>
               
					
    </div>  
	
</body>
</html>