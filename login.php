<!DOCTTYPE HTML>
<?php
   session_start();
   $configPath = $_SERVER['DOCUMENT_ROOT'];
   $configPath .= "/uberDashboard/config.php";
   include_once($configPath);
   define("MAX_LENGTH", 6);
   
  $error = "";
   function generateHashWithSalt($password) {
    $intermediateSalt = md5(uniqid(rand(), true));
    //$salt = substr($intermediateSalt, 0, MAX_LENGTH);
   // return hash("sha256", $password . $salt);
	return hash("sha256", $password );
}
  
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
     $email = filter_var( $_POST['email'] ,FILTER_SANITIZE_STRING) ;
	 // $_POST['password']
      $mypassword = filter_var($_POST['password'] ,FILTER_SANITIZE_STRING) ;
      
	  
	  //echo $email;
	  //echo $mypassword;
	  
	  // get salt based on the username
      $sql = "SELECT * FROM admins WHERE email = '$email' ";
      
	  $result = $conn->query($sql);
      $row = $result->fetch();
	  $salt =  $row["PasswordSalt"]; 
	  
	  //create the salted password
	  $mypassword = hash("sha256", $mypassword . $salt);
	  
	  
	  // compare the salted password to the password from the database
      
	  
	 // $count = $result->num_rows;
    
	if($mypassword == $row["password"]) {
		  
		  //echo 'nigga';
		  
        // session_register("email");
         $_SESSION['login_user'] = $email;
         $_SESSION['timeout'] = time();
         header("location: welcome.php");
		 
      }else {
         $error = "*Invalid user name or password";
	  }
      
   }
?>
<html>
   
   <head>
      <title>Login Page</title>
      
      <style type = "text/css">
         .bodyContainer {
            font-family:Arial, Helvetica, sans-serif;
            //font-size:14px;
			margin-left: 26%;
         }
         
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
			display: inline-block;
         }
         
         .box {
            border:#666666 solid 1px;
         }
		 
		 .headerContainer {
			display: flex;
			flex-direction: row;            
			flex-wrap: nowrap;              
			justify-content: initial; 
		 }
		 
		 .right {
			 width: 90%;
			 margin-left: 40px;
			 //border: 1px black solid;
		 }
		 
		 #title {
			 font-family: "Arial";
			 padding-left: 15%;
			 color: #29a3a3;
			 font-weight: 900;
			 font-size: 35px;
		 }

		 #loginbtn {
			margin-left: 205px;
		}
		
		
      </style>
      
   </head>
   
   <body bgcolor = "#FFFFFF">
   
	<div class="headerContainer">
		<div class="left">
			<img src="/jts/images/weblogo_small.JPG" alt="logo" >
		</div>
		
		<div class="right">
			<h1 id="title">Driver dashboard</h1>
		</div>
	</div>
	<hr/>
	
	<br/><br/>
	
	<div class="bodyContainer">
      <!--<div align = "center">
         <div style = "width:300px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
				
            <div style = "margin:30px">-->
			
				<h2>User Login</h2>
				<br/>
               
               <form action = "" method = "post">
                  <label>email  :</label><input type = "text" name = "email" class = "box"/>
				  <br /><br />
                  <label>Password  :</label><input type = "password" name = "password" class = "box" />
				  <br/>
				  
				  <div style = "font-size:14px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
				  <br/>
				  <div id="loginbtn">
                  <input type = "submit" value = " Sign In "/><br />
				  </div>
               </form>
               
					
            <!--</div>
				
         </div>
			
      </div>-->
	</div>  

   </body>
</html>