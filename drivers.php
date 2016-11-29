<?php
include("config.php");
//include('session.php');

$sql = "SELECT * FROM drivers";
$sth = $conn->prepare($sql );
$sth->execute();
$dataAraay = $sth->fetchAll();


$tableHeader = array("ID","email","fullname","gender","phone","active","lastUpdated","longitude","latitude");

// drivers and requests table 

$sql2 = "SELECT 
  rd.requestID,
  d.fullname AS driver,
  r.price,
  r.requestTime AS time,
  rd.status
FROM request_driver AS rd
INNER JOIN drivers AS d ON rd.driverID  = d.ID
INNER JOIN requests    AS r ON r.ID = rd.requestID";

$sth2 = $conn->prepare($sql2 );
$sth2->execute();
$dataAraay2 = $sth2->fetchAll();

$tableHeader2 = array ("requestID","driver","price","time","status");
$tableDatabaseColumns2= $tableHeader2;
function makeDataTableTable ($tableID,$tableHeader,$tableDatabaseColumns,$dataAraay)
{
	//$tableDatabaseColumns = $tableHeader;
	echo '<div style = "width: 70%; margin:auto;" >' ;
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
			 if ($columnName == "gender")
			 {
				 if ( trim($row[$columnName])   == "male")
				 {
					 echo  "<td>male</td>" ;
				 }else
				 {
					echo "<td>female</td>" ;
				 }
			 }
			 else{
				 echo  "<td>" . trim($row[$columnName]) .  "</td>" ;
			 }
			 
		 }
			  "</tr>" ;
    }
	echo '</tbody>' ;
	echo "</table>";
	echo '</div>' ;
}
	
	
	//$tableID = 'drivers';
	//makeDataTableTable ($tableID,$tableHeader,$tableHeader,$dataAraay);
	

//echo '<script> $("#drivers").DataTable(); </script>';
$conn = null;
?>
<html>



<head>




<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>

<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
 <link rel="stylesheet" type="text/css" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" >
<!-- Include Date Range Picker -->

<link href="jquery.datatables.yadcf.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript"  src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="jquery.dataTables.yadcf.js"></script>
 
 <script type="text/javascript">
                $(document).ready(function() {
                    $('#drivers').dataTable().yadcf([
					{column_number: 0, text_data_delimiter: ",", filter_type: "auto_complete"},
					{column_number : 1, text_data_delimiter: ",", filter_type: "auto_complete"},
					{column_number : 2, text_data_delimiter: ",", filter_type: "auto_complete"},
					{column_number : 3, data: ['male','female'], filter_default_label: "Select gender",filter_match_mode : "exact"},
					{column_number : 4,  text_data_delimiter: ",", filter_type: "auto_complete"},  
					{column_number: 5, data: ["1","0"], filter_default_label: "Select active"   }]);;
					$('#request_driver').dataTable();
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
<div style="text-align: center;>
<span class = "title"> list of drivers </span><br><br>
</div>

<?php $tableID = 'drivers';
	makeDataTableTable ($tableID,$tableHeader,$tableHeader,$dataAraay);?>
</body>

<div style="text-align: center;>
<span class = "title"> drivers and their rides </span><br><br>
</div>
<?php 
	makeDataTableTable ("request_driver",$tableHeader2,$tableDatabaseColumns2,$dataAraay2);?>
</body>

<div style="text-align: center;>
<span class = "title"> drivers performance </span><br><br>
</div>




</html>