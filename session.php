<?php
   include('config.php');
   session_start();
   
   function isSessionValid (){
	   return($_SESSION['timeout'] + 6000 > time() && (isset($_SESSION['login_user']))  ) ;
	   
   // && (isset($_SESSION['login_user'])) 
	   
   }
   function updateSessionTime () {
	   $_SESSION['timeout'] = time();
   }
   function redirect_to_homepage (){
	   header("location:login.php");
	   
	   
   }
   


   if( ! isSessionValid ()){
	   echo 'session is not valid';
      redirect_to_homepage () ;
   }
   else {
	updateSessionTime () ;
	 
	$user_check = $_SESSION['login_user'];
	$ses_sql = "select email FROM admins WHERE email = '$user_check' " ;
    $stmt= $conn->prepare($ses_sql);
	$stmt->execute();
	$row = $stmt->fetch();
    $login_session = $row['email'];  
	   
   }
?>