 <?php
include('session.php');

include("config.php");
require __DIR__ . '/vendor/autoload.php';

function areAllParametersSet ($parametersArray)
{	$areSet = 1;
	$parameterArrayLength = count ($parametersArray);
	
	for($i = 0; $i < $parameterArrayLength; $i++) {
    if (! isset ($_POST[$parametersArray[$i]])){$areSet = 0; break; }
	}
	
return $areSet; 	

}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

	
	 function  send_mail($email,$message,$subject)
		{	 
			
			
			$mail = new PHPMailer;
			$mail->SMTPDebug = 3;                               // Enable verbose debug output
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = "smtp.gmail.com"; // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username='sabirmgd@gmail.com';  
			$mail->Password='kooora.com100plusfuck';                         // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->SetFrom('sabirmgd@gmail.com',"Driver");
			$mail->AddReplyTo('sabirmgd@gmail.com',"Driver");
			$mail->Subject    = $subject;
			$mail->MsgHTML($message);
			$mail->addAddress($email, 'sabirmgd@gmail.com');
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject= $subject;
			$mail->MsgHTML($message);
			/*
			$mail->SMTPOptions = array(
			'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
			)
			);*/
			if(!$mail->send()) {
				
				return $mail->ErrorInfo;
           }else {
				return 1;
				}
		}
	 
	 function  generateRandomCode($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}
	

$error = "";
$emailError = $passwordError = $genderError = $phoneError = $carModelError = $carNumberError = $carYearError = $carColorError = $nameError = "";
	$email = $password = $gender =$phone = $carModel = $carNumber = $carYear = $carColor = $name =  "";
	
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      //echo $_POST['gender'];
	  // set the request excpected paramaters 
	$ExpectedParametersArray = array ('email','password','gender','fullname','phone','model','plate_number','year','color');
	// check if all the paramaters are set 
	
	
	
	  if (empty($_POST["email"])) {
    $emailError = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
  }
  
    if (empty($_POST["password"])) {
    $passwordError = "password is required";
  } else {
    $password = test_input($_POST["password"]);
  }
  
    if (empty($_POST["gender"])) {
    $genderError = "gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }
  
  
    if (empty($_POST["fullname"])) {
    $nameError = "Name is required";
  } else {
    $name = test_input($_POST["fullname"]);
  }
  
  
    if (empty($_POST["phone"])) {
    $phoneError = "Phone is required";
  } else {
    $phone = test_input($_POST["phone"]);
	
	} 

	if (empty($_POST["model"])) {
    $carModelError = "model is required";
  } else {
    $carModel = test_input($_POST["model"]);
  }
	
	
	
	if (empty($_POST["plate_number"])) {
    $carNumberError = "plate number is required";
  } else {
    $carNumber = test_input($_POST["plate_number"]);
  }
  
  
 
	
	

	
	
	if ( $emailError == $passwordError && $passwordError == $genderError && $genderError == $phoneError &&
	$phoneError == $carModelError && $carModelError  == $carNumberError && $carNumberError == $carYearError &&
	$carYearError == $carColorError &&  $carColorError == $nameError && $nameError =="")
	//== $passwordError == $genderError == $phoneError == $carModelError == $carNumberError == $carYearError == $carColorError == $nameError == "")
	{echo "we are good to go" ;
	
	$continue =1;
	
	if ($continue)
	{
	$driverStatement = $conn->prepare('SELECT * FROM drivers where email = ?');
	$driverStatement->execute(array($email));
	$numberOfRows = $driverStatement->fetchColumn(); 
	if ($numberOfRows != 0) {
		$continue =0;
		$error=  "some user has already registered with this email";
	}
	}
	
	if ($continue){
		$phoneStatement = $conn->prepare('SELECT * FROM drivers where phone = ?');
	$phoneStatement->execute(array($phone));
	$numberOfRows = $phoneStatement->fetchColumn(); 
	if ($numberOfRows != 0) {
		$continue = 0;
		$error ='User already exist with this phone number';
	}
	}
	
	if ($continue ){
		
		$randomCode = generateRandomCode (6);
		$message = ' welcome to Driver, we are blessed to have you here, your password is: '		. $randomCode;
		$continue = send_mail($email,$message,'welcome to Driver! ');
		$hash = password_hash($password, PASSWORD_DEFAULT);
		
		$insertStatement = $conn->prepare('INSERT INTO `drivers`(`email`, `gender`, `fullname`, `password`,`phone` , `active`)  VALUES(?,?,?,?,?,?)');
	
	try {
		$insertStatement->execute(array( $email,$gender , $name,$hash,$phone ,1));
		$driverId =$conn->lastInsertId();
	  }catch(PDOException $ex) {
		$continue =0;
		$error = $ex->getMessage();
		
	}
	}
	if ($continue == '1')
	{
		// add the car info
		
		$addCarSql= 'INSERT INTO `cars`(`driverID`, `color`, `model`, `year`, `plateNumber`) VALUES (?,?,?,?,?)';
		$addCarStmt = $conn->prepare($addCarSql);
		try{
			$addCarStmt->execute(array($driverId,$carColor,$carModel,$carYear,$carNumber));
		}
		catch (PDOException $ex)
		{
			$continue =0;
		    $error = $ex->getMessage();	
			echo $error;
		}
		
		
		
	}
	if ($continue =='1')
	{
		$error = 'driver has been succesfully added to the database';
	}
	
	
	
	
	
	
	}
	
	
   }
	

   
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
		 .error {color: #FF0000;}
      </style>
      
   </head>
   
   
   
   <body bgcolor = "#FFFFFF">
	
	



      <div align = "center">
         <div style = "width:400px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#800080; color:#FFFFFF; padding:3px;"><b>add a driver</b></div>
				
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  <label>email : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></br><input type = "text" value = "<?php echo $email ;?>" name = "email" class = "box"/>&nbsp;&nbsp;<span class="error"><?php echo $emailError;?></span><br>
                  <label>Password :</label></br><input type = "password" name = "password" class = "box" value = "<?php echo $password ;?>"/>&nbsp; &nbsp;<span class="error"><?php echo $passwordError;?></span><br>
				  <label>gender :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> <input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female">Female
				  <input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male">Male
				  <span class="error">* <?php echo $genderError;?></span></br>
			
                  <label>full name :&nbsp;</label></br><input type = "text" name = "fullname" value = "<?php echo $name; ?>" class = "box"  /><span class="error"><?php echo $nameError;?></span><br>
				  <label>phone :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></br><input type = "text" name = "phone" value = "<?php echo $phone ;?>" class = "box" /><span class="error"><?php echo $phoneError;?></span><br>
                  
				   <label>car model :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></br><input type = "text" name = "model"  value = "<?php echo $carModel ;?>" class = "box"/><span class="error"><?php echo $carModelError;?></span><br>
				  <label>car plate number :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></br><input type = "text" name = "plate_number" value = "<?php echo $carNumber ;?>"  class = "box" /><span class="error"><?php echo $carNumberError;?></span><br>
				  <label>car year :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></br><input type = "text" name = "year" value = "<?php  echo $carYear ;?>" class = "box"    ><br />
				  <label>car color :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></br><input type = "text" name = "color" value = "<?php echo $carColor ;?>" class = "box" /><br />
                  
                  <input type = "submit" value = " Submit "/><br />
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
					
            </div>
				
         </div>
			
      </div>

   </body>
</html>