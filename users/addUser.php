<?php
	$headerPath = $_SERVER['DOCUMENT_ROOT'];
	$headerPath .= "/jts/includes/jtsHeader.php";
	include_once($headerPath);
	
	$configPath = $_SERVER['DOCUMENT_ROOT'];
	$configPath .= "/jts/config.php";
	include_once($configPath);

	$sql = 'SELECT RoleName FROM tblroles WHERE 1' ;
	$result = $conn->query($sql);
	$conn->close();


?>

<link rel="stylesheet" type="text/css" href="/jts/styles/jtsHeaderStyle.css">

<link rel="stylesheet" type="text/css" href="/jts/lib/css/bootstrap.min.css">


<form>
  <div class="form-group">
    <label for="username">username</label>
    <input type="text" class="form-control" id="username" aria-describedby="username" placeholder="username">
    <small id="username" class="form-text text-muted">please enter the username</small>
  </div>
  <div class="form-group">
    <label for="RoleSelect">Role</label>
    <select class="form-control" id="RoleSelect">
	 <?php 
	 if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
       echo '<option>' .  $row['RoleName'] . '</option>' ;
    }
} else {
    echo '<option>' .  'no roles to select from' . '</option>' ;
}
	 ?>
      
    </select>
  </div>
  
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
  

  <fieldset class="form-group">
    <legend>Radio buttons</legend>
    <div class="form-check">
      <label class="form-check-label">
        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="option1" checked>
        Option one is this and that&mdash;be sure to include why it's great
      </label>
    </div>
    <div class="form-check">
    <label class="form-check-label">
        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="option2">
        Option two can be something else and selecting it will deselect option one
      </label>
    </div>
    <div class="form-check disabled">
    <label class="form-check-label">
        <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios3" value="option3" disabled>
        Option three is disabled
      </label>
    </div>
  </fieldset>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>