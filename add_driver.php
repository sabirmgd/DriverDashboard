<?php


include("config.php");
session_start();
$error = "";

?>


<html>
   
   <head>
      <title>add driver page </title>
      
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
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>add a driver</b></div>
				
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  <label>email : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><input type = "text" name = "email" class = "box"/><br /><br />
                  <label>Password :</label><input type = "password" name = "password" class = "box" /><br/><br />
				  <label>gender :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><INPUT TYPE="Radio" Name="Gender" Value="Male">Male
				  <INPUT TYPE="Radio" Name="Gender" Value="Female">Female <br/><br />
                  <label>full name :&nbsp;</label><input type = "password" name = "password" class = "box" /><br/><br />
				  <label>phone :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label><input type = "text" name = "email" class = "box"/><br /><br />
                  
                  <input type = "submit" value = " Submit "/><br />
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
					
            </div>
				
         </div>
			
      </div>

   </body>
</html>