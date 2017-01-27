<?php
include("config.php");
//include('session.php');

$sql = "SELECT * FROM passengers";
$sth = $conn->prepare($sql );
$sth->execute();
$dataAraay = $sth->fetchAll();


$tableHeader = array("ID","email","fullname","gender","phone");



function makeDataTableTable ($tableID,$tableHeader,$tableDatabaseColumns,$dataAraay)
{
	//$tableDatabaseColumns = $tableHeader;
	
	echo "<table id= " . $tableID . " class='display' cellspacing='0' width='100%'>" ;
	
	echo '<thead>
			<tr> ' ; 
			
	foreach ($tableHeader as $HeaderColumn)
	{
		echo 	'<th>' .  $HeaderColumn . '</th>' ;
	}
	echo '</tr></thead> ' ;
	echo '<tbody>';
	// make the body 
	foreach ($dataAraay as $row) {
        echo "<tr>";
         foreach ($tableDatabaseColumns as $columnName) {
			 echo  "<td>" . $row[$columnName] .  "</td>" ;
		 }
			  "</tr>" ;
    }
	echo '</tbody>' ;
	echo "</table>";
}
	
	
	
	

//echo '<script> $("#drivers").DataTable(); </script>';
$conn = null;
?>
<html>



<head>



<script type="text/javascript" language="javascript"  src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script type="text/javascript" language="javascript"  src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" >

 <script type="text/javascript">
                $(document).ready(function() {
                    $('#passengers').dataTable();
                });
 </script>


</head>


<style>
.title {

									font-family:Georgia,serif;
									color:#4E443C;
									font-variant: small-caps; text-transform: none; font-weight: 100; margin-bottom: 0;
									margin:auto;
									font-size:30px;
									text-align: center;
									}

</style>
<body>
 <?php include('welcome.php'); ?>
 <div style="text-align: center;"><br><br>
 <span class = "title"> list of passengers </span><br><br>
 </div>
<?php $tableID = 'passengers';
	makeDataTableTable ($tableID,$tableHeader,$tableHeader,$dataAraay);?>
</body>


</html>