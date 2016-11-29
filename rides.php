<?php
include("config.php");
//include('session.php');

$sql = "SELECT 
  r.ID AS requestID,
  p.fullname AS passengerName,
  p.gender As passenger_gender,
  d.fullname AS driverName,
  d.gender AS driver_gender,
  r.price,
  r.requestTime,
  r.notes,
  r.status
FROM requests AS r
INNER JOIN passengers AS p ON r.passengerID  = p.ID
LEFT JOIN drivers    AS d ON d.ID = r.driverID";
$sth = $conn->prepare($sql );
$sth->execute();
$dataAraay = $sth->fetchAll();


$tableDatabaseColumns = array("requestID","passengerName","passenger_gender","driverName","driver_gender","price","requestTime","notes","status");

$tableHeader = array("requestID","passengerName","passenger_gender","driverName","driver_gender","price","date","time","seconds","notes","status");

function makeDataTableTable ($tableID,$tableHeader,$tableDatabaseColumns,$dataAraay)
{
	//$tableDatabaseColumns = $tableHeader;
	
	echo "<table id= " . $tableID . " class='display' style='float:left; width:100%;' cellspacing='0' >" ;
	
	echo '<thead>
			<tr> ' ; 
			
	foreach ($tableHeader as $HeaderColumn)
	{
		echo 	'<th>' .  $HeaderColumn . '</th>' ;
	}
	echo '</tr></thead> ';

	/*
	echo '<tfoot><tr> ' ; 
			
	foreach ($tableHeader as $HeaderColumn)
	{
		echo 	'<th>' .  $HeaderColumn . '</th>' ;
	}
	echo '</tr></tfoot> '	;
	*/
	
	echo '<tbody>';
	// make the body 
	foreach ($dataAraay as $row) {
        echo "<tr>";
         foreach ($tableDatabaseColumns as $columnName) {
			 if ($columnName == 'requestTime')
			 { //echo 'am here';
				 list ($day,$time) = explode(" ", $row['requestTime'] ,2 );
				 
				 $str_time = $time;

				$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

			sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

			$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
				 echo  "<td>" . date("d/m/Y", strtotime($day))  .  "</td>" ;
				 echo  "<td>" .  $time .  "</td>" ;
				 echo  "<td>" .  $time_seconds .  "</td>" ;
			 }
			 else {
				 echo  "<td>" . $row[$columnName] .  "</td>" ;
			 }
			
		 }
			  "</tr>" ;
    }
	echo '</tbody>' ;
	echo "</table>";
}
	
 $PoCTabDivs ='<p>min: <input type="text" id="min"></p>
      <p>max: <input type="text" id="max"></p>'	;
	
	//echo $PoCTabDivs;

//echo '<script> $("#drivers").DataTable(); </script>';
$conn = null;
?>
<html>



<head>


<!-- Include Required Prerequisites -->
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
                    $('#requests').dataTable({
						"aoColumns": [ null,null,null,null,null,null,null,null,{ "bVisible":    false },null,null],
						responsive: true
						
						
						
					}
					
					).yadcf([
					{column_number: 6, filter_type: "range_date",  date_format: "dd/mm/yyyy",filter_container_id: "external_filter_container"},
					{column_number : 1, text_data_delimiter: ",", filter_type: "auto_complete"},
					{column_number : 3, text_data_delimiter: ",", filter_type: "auto_complete"},
					{column_number : 10, data: ["noDriver", "completed","canceled","pending","accepted"], filter_default_label: "Select"},
					{column_number : 2, data: ['male','female'], filter_default_label: "Select gender",filter_match_mode : "exact"},
					{column_number : 4, data: ['male','female'], filter_default_label: "Select gender",filter_match_mode : "exact"},
					 {column_number:8,filter_container_id:"_rrwebapp_filtergender", filter_type: "range_number_slider"},
					{     column_number: 5,        filter_type: "range_number_slider" }]);	
					
					 makeAndAttachSumaryTableHTML( getRidesDataArray() );
					 initializeAnalysisTable (); 
					updateSumaryTableWhenRideTableChangesChanges ();
					
					
                });
				
function getRidesDataArray(){
	
	/* this function gets the data from for the analysis table from the first table
	ie., the data from the first table will be used in the second table */ 
	var Requests_Table = $('#requests').DataTable();
	var filteredDataArray = Requests_Table.rows( { filter : 'applied'} ).data();
	// { filter : 'applied'}
	return filteredDataArray ;
}

function makeAndAttachSumaryTableHTML (){
	
	var sumaryTableHTML = makeSumaryTableHTML( getRidesDataArray() );
	//console.log (AnalysisTableDivHTML);
	$('#summaryTableDiv').html(sumaryTableHTML); 
}


function makeSumaryTableHTML (filteredPoC_TableArray){  
//console.log ("table array");
//console.log(filteredPoC_TableArray);
// define constants for the column numbers 

var MONEY =3;
var STATUS = 8;



var no_ofRequests = filteredPoC_TableArray.length;
var no_ofCompleted=0;
var no_ofPending=0;
var no_ofAccepted=0;
var no_ofCanceled=0;
var no_ofNoDriver=0;
var money =0;


var i;

for (i=0;i<filteredPoC_TableArray.length;i++)
{
	// calculate no_ofApprovedPoC, no_ofPendingPoC,no_ofRejectedPoC
	if ( (filteredPoC_TableArray[i][STATUS]).toLowerCase() == "accepted")
	no_ofAccepted++;
	else if ( (filteredPoC_TableArray[i][STATUS]).toLowerCase() == "nodriver")
	no_ofNoDriver++;
	else if ( (filteredPoC_TableArray[i][STATUS]).toLowerCase() == "completed")
	no_ofCompleted++;
    else if ( (filteredPoC_TableArray[i][STATUS]).toLowerCase() == "canceled")
	no_ofCanceled++;
	else if ( (filteredPoC_TableArray[i][STATUS]).toLowerCase() == "pending")
	no_ofPending++;

	money += filteredPoC_TableArray[i][MONEY] ;
}

// create the HTML 
	var analysisTableHTML;
	// add the table ID and the header 
	analysisTableHTML= '<table id="summaryTable"  style="float:left; border: solid 1px black; width:500px;" class="display dataTable" cellspacing="0" ">\
	<thead style="display:none;">\
		<tr>\
			<th> property  </th>\
			<th> value </th>\
		</tr>\
	</thead>';
	// add the footer 
	
	
	// add the body of the table 
	analysisTableHTML += 
	'<tbody>';
		
	 analysisTableHTML +=		'<tr style="color:#c0c0c0 background-color: black" class="borderRowColumn" ><td class="borderRowColumn">'+  "No. of Requests"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + no_ofRequests + '</td></tr>';
     
	 analysisTableHTML +=		'<tr class="borderRowColumn" ><td class="borderRowColumn">'+  "No. of Completed Requests"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + no_ofCompleted +'</td></tr>';

	 analysisTableHTML +=		'<tr class="borderRowColumn" ><td class="borderRowColumn">'+  "No. of Canceled Requests"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + no_ofCanceled +'</td></tr>';
	 
	 analysisTableHTML +=		'<tr class="borderRowColumn"><td class="borderRowColumn">'+  "No. of No Driver Requests"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + no_ofNoDriver +'</td></tr>';
	 
	 analysisTableHTML +=		'<tr class="borderRowColumn"><td class="borderRowColumn">'+  "No. of Pending Requests"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + no_ofPending +'</td></tr>';
	 
	 analysisTableHTML +=		'<tr class="borderRowColumn"><td class="borderRowColumn">'+  "No. of Accepted Requests"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + no_ofAccepted + '%' +'</td></tr>';
	 
	 
	analysisTableHTML += '</tbody></table>';
	
	return analysisTableHTML;
}


function initializeAnalysisTable (){
	
	$('#summaryTable').dataTable({
	    responsive: true,
	    "bFilter": false,
		"bLengthChange": false,
		"bPaginate": false,
		"showNEntries": false,
		"bLengthChange": false,
		"bInfo" : false,
        /* Disable initial sort */
        "aaSorting": []
     }); 
}


function updateSumaryTableWhenRideTableChangesChanges (){

var table = $('#requests').DataTable();
table.on( 'search.dt', function () {

$('#requests').on( 'draw.dt', function () { 
		
		setTimeout(  function() // when the PoC table changes 
		{
	
	//getRidesDataArray() ;
	
	makeAndAttachSumaryTableHTML () ; 
	
	initializeAnalysisTable ();
	     } 
	, 500);
} );
} );		
		
	
}
 				

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

		<div id="requestsDiv" style="width:80%; margin:auto;">
		
		<div style="text-align: center;">
<span class = "title"> list of rides </span><br><br>
</div>
 <div id="external_filter_container_wrapper">
        <label>choose the date :</label>
        <div id="external_filter_container"></div>
		</div>
		<br>
	<div id="external_filter_container_wrapper">
        <label>choose the time  :</label>
        <div id="_rrwebapp_filtergender"></div>	
		</div>
		<br>
<?php $tableID = 'requests';
	makeDataTableTable ($tableID,$tableHeader,$tableDatabaseColumns,$dataAraay);
	
	?>		
	</div>
	
	<div style="text-align: center;">
<span class = "title"> summary </span><br><br>
</div>

<div id="summaryTableDiv" style="width:70%; margin:auto">
</div>

	</body>





</html>