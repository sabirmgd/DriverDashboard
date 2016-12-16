<?php
	//session_start();
   include('session.php');
   
?>
<html">
   
   <head>
      <title>Welcome </title>
   </head>
   
   <body>
      <h1>Welcome <?php echo $login_session; ?></h1> 
      <h2><a href = "configuration.php">configurations and prices</a></h2>
	   <h2><a href = "passengers.php">passengers</a></h2>
	   <h2><a href = "add_driver.php">add driver</a></h2>
	   <h2><a href = "drivers.php">drivers</a></h2>
	   <h2><a href = "rides.php">rides</a></h2>
   </body>
   
</html>