<?php
date_default_timezone_set("Asia/Kuala_Lumpur");
include("config.php");
//include('session.php');


$sql = "SELECT * FROM drivers";
$sth = $conn->prepare($sql );
$sth->execute();
$dataAraay = $sth->fetchAll();
function adminActiveDriver ($email,$active)
{include("config.php");
	
	$sql = " UPDATE drivers SET adminActive = ? WHERE email = ? " ;
	$stml = $conn->prepare($sql);
	$stml->execute(array($active,$email));
}
//,"longitude","latitude"
//adminActiveDriver("insomniaa@gmail.com",'0');
$tableHeader = array("driver","email","gender","phone","active","last active day","last active time","seconds","admin active control","");

$tableDatabaseColumns = array("fullname","email","gender","phone","active","lastUpdated","adminActive");

// drivers and requests table 
// lastUpdated means the request time 
$sql2 = "SELECT 
  rd.requestID,
  d.fullname AS driver,
  r.price,
  r.requestTime AS lastUpdated, 
  rd.status,
  d.gender,
  r.pickup_text,
  r.dest_text
FROM request_driver AS rd
INNER JOIN drivers AS d ON rd.driverID  = d.ID
INNER JOIN requests    AS r ON r.ID = rd.requestID";

$sth2 = $conn->prepare($sql2 );
$sth2->execute();
$dataAraay2 = $sth2->fetchAll();
//"day","time","seconds"
$tableDatabaseColumns2    = array ("requestID","driver","gender","price","lastUpdated","pickup_text","dest_text","status","adminActive");
$tableHeader2=array ("requestID","driver","gender","price","day","time","seconds","from","to","status","active","delete");
function makeDataTableTable ($tableID,$tableHeader,$tableDatabaseColumns,$dataAraay){
	//$tableDatabaseColumns = $tableHeader;
	echo '<div style = "width: 95%;" >' ;
	echo "<table id= " . $tableID . " class='display' cellspacing='0' width='95%'>" ;
	
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
		
			 
				if ($columnName == 'lastUpdated' )
			 { //echo 'am here';
				
				 $time = strtotime($row['lastUpdated'].' UTC');
				 $dateInLocal = date("Y/m/d H:i:s", $time);
				 list ($day,$time) = explode(" ", $dateInLocal ,2 );
				 $str_time = $time;

				$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

			sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

			$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
				 echo  "<td>" . date("d/m/Y", strtotime($day))  .  "</td>" ;
				 echo  "<td>" .  $time .  "</td>" ;
				 echo  "<td>" .  $time_seconds .  "</td>" ;
			 }
			 else if ($columnName == 'adminActive' ){
				 $active = $row['adminActive'] ;
				 if ( $active == '1')
					  echo  "<td>" . ' <input type="checkbox" name="active" class="checkbox" checked> ' .  "</td>" ;
				 else  
					 echo  "<td>" . ' <input type="checkbox" class="checkbox" name="active" > ' .  "</td>" ;
				 
			 }
			 else if ($columnName == 'email')
			 {
				  echo  "<td class='email'>" . $row[$columnName] .  "</td>" ;
				 
			 }
			 else {
				 echo  "<td>" . $row[$columnName] .  "</td>" ;
			 }
			 
			 
		 }
		 echo "<td>" .  '<span style="color:red;" class="glyphicon glyphicon-remove delete"></span>' .  "</td>" ;
			  "</tr>" ;
    }
	echo '</tbody>' ;
	echo "</table>";
	echo '</div>' ;
}
	

$conn = null;
?>
<html>



<head>




<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>

<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
 <link rel="stylesheet" type="text/css" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
 <link rel="stylesheet" type="text/css" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<!-- Include Date Range Picker -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link href="jquery.datatables.yadcf.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript"  src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="jquery.dataTables.yadcf.js"></script>
 
 
  <script type="text/javascript">
  function getDriversDataArray(){
	
	/* this function gets the data from for the analysis table from the first table
	ie., the data from the first table will be used in the second table */ 
	var Drivers_Table = $('#drivers').DataTable();
	var filteredDataArray = Drivers_Table.rows( { filter : 'applied'} ).data();
	// { filter : 'applied'}
	return filteredDataArray ;
}

function makeDriversSumaryTableHTML (filterdDrivers_TableArray){  
//console.log ("table array");
//console.log(filterdDrivers_TableArray);
// define constants for the column numbers 

var GENDER =2;
var STATUS = 4;



var no_ofDrivers = filterdDrivers_TableArray.length;
var no_ofMaleDrivers=0;
var no_ofFemaleDrivers=0;
var no_ofActiveDrivers = 0;
var no_ofInActiveDrivers=0;
var no_ofActiveMaleDrivers = 0;
var no_ofActiveFemaleDrivers = 0;
var no_ofInActiveMaleDrivers =0;
var no_ofInActiveFemaleDrivers = 0;


var i;

for (i=0;i<filterdDrivers_TableArray.length;i++)
{

	if ( (filterdDrivers_TableArray[i][GENDER]).toLowerCase() == "male"){
	no_ofMaleDrivers++;
		if ( (filterdDrivers_TableArray[i][STATUS]).toLowerCase() == "1")
		 no_ofActiveMaleDrivers++;
	}
	
	else if ( (filterdDrivers_TableArray[i][GENDER]).toLowerCase() == "female"){
	no_ofFemaleDrivers++;	
	if ( (filterdDrivers_TableArray[i][STATUS]).toLowerCase() == "1")
	no_ofActiveFemaleDrivers++;	
	}
}

no_ofActiveDrivers= no_ofActiveFemaleDrivers+ no_ofActiveMaleDrivers;
no_ofInActiveDrivers = no_ofDrivers - no_ofActiveDrivers;
no_ofInActiveMaleDrivers = no_ofMaleDrivers - no_ofActiveMaleDrivers;
no_ofInActiveFemaleDrivers =no_ofFemaleDrivers - no_ofActiveFemaleDrivers; 


// create the HTML 
	var analysisTableHTML;
	// add the table ID and the header 
	analysisTableHTML= '<table id="DriversSummaryTable"  style=" border: solid 1px black; width:30%;" class="display dataTable" cellspacing="0" ">\
	<thead style="display:none;">\
		<tr>\
			<th> property  </th>\
			<th> value </th>\
			<th> % </th>\
		</tr>\
	</thead>';
	// add the footer 
	
	
	// add the body of the table 
	analysisTableHTML += 
	'<tbody>';
		
	 analysisTableHTML +=		'<tr style="color:#c0c0c0 background-color: black" class="borderRowColumn" ><td class="borderRowColumn">'+  "No. of drivers"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofDrivers) + '</td>';
     analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofDrivers/no_ofDrivers*100) + '%'+ '</td></tr>';
     
	 
	 analysisTableHTML +=		'<tr class="borderRowColumn" ><td class="borderRowColumn">'+  "No. of Active Drivers"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofActiveDrivers) +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofActiveDrivers/no_ofDrivers*100) +'%' + '</td></tr>';
     
	 analysisTableHTML +=		'<tr class="borderRowColumn" ><td class="borderRowColumn">'+  "No. of Inactive Drivers"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofInActiveDrivers) +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofInActiveDrivers/no_ofDrivers*100 )+ '%' + '</td></tr>';
		 
	 analysisTableHTML +=		'<tr class="borderRowColumn"><td class="borderRowColumn">'+  "No. of Male drivers"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofMaleDrivers) +'</td>';
	 	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofMaleDrivers/no_ofDrivers*100) + '%' +'</td></tr>';
	 
	 analysisTableHTML +=		'<tr class="borderRowColumn"><td class="borderRowColumn">'+  "No. of female drivers"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofFemaleDrivers )+'</td>';
	 	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofFemaleDrivers/no_ofDrivers*100) +'%' +'</td></tr>';
		 
	 analysisTableHTML +=		'<tr class="borderRowColumn"><td class="borderRowColumn">'+  "No. of active male drivers"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofActiveMaleDrivers) + '</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofActiveMaleDrivers/no_ofDrivers*100) +'%' + '</td></tr>';
	 
	 analysisTableHTML +=		'<tr class="borderRowColumn"><td class="borderRowColumn">'+  "No. of active female drivers"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofActiveFemaleDrivers )+ '</td>';
	 	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofActiveFemaleDrivers/no_ofDrivers*100)+'%' + '</td></tr>';
	 
	 analysisTableHTML +=		'<tr class="borderRowColumn"><td class="borderRowColumn">'+  "No. of inactive male drivers"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofInActiveMaleDrivers )+ '</td>';
	 	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofInActiveMaleDrivers/no_ofDrivers*100) +'%' + '</td></tr>';
		 
	  analysisTableHTML +=		'<tr class="borderRowColumn"><td class="borderRowColumn">'+  "No. of inactive female drivers"	 +'</td>';
	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofInActiveFemaleDrivers) + '</td>';
	 	 analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(no_ofInActiveFemaleDrivers/no_ofDrivers*100 )+'%' + '</td></tr>';
	 
	 
	 
	 
	 
	 
	 
	analysisTableHTML += '</tbody></table>';
	
	
	// make the pie chart 
	
	
	google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['gender', 'male or female'],
          ['male',     no_ofMaleDrivers],
          ['female',      no_ofFemaleDrivers]
        ]);

        var options = {
          title: 'Drivers based on gender',
          is3D: false,
		   colors:['blue','pink'],
        };

        var chart = new google.visualization.PieChart(document.getElementById('genderPiechart'));
        chart.draw(data, options);
      }
	
	google.charts.setOnLoadCallback(drawChart2);
      function drawChart2() {
        var data = google.visualization.arrayToDataTable([
          ['active', 'active and inactive'],
          ['active',     no_ofActiveDrivers],
          ['inactive',      no_ofInActiveDrivers]
        ]);

        var options = {
          title: 'Drivers based on activity',
          is3D: false,
		  colors:['green','red'],
        };

        var chart = new google.visualization.PieChart(document.getElementById('activePiechart'));
        chart.draw(data, options);
      }
	
	
	console.log(analysisTableHTML);
	return analysisTableHTML;
	
}


function makeAndAttachDriversSumaryTableHTML (){
	
	var sumaryTableHTML = makeDriversSumaryTableHTML( getDriversDataArray() );
	//console.log (AnalysisTableDivHTML);
	$('#DriversSummaryTableDiv').html(sumaryTableHTML); 
}

function initializeDriversAnalysisTable (){
	
	$('#DriversSummaryTable').dataTable({
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

function updateSumaryTableWhenDriverTableChanges (){

var table = $('#drivers').DataTable();
table.on( 'search.dt', function () {

$('#drivers').on( 'draw.dt', function () { 
		
		setTimeout(  function() // when the PoC table changes 
		{
	
	//getRidesDataArray() ;
	
	makeAndAttachDriversSumaryTableHTML () ; 
	
	initializeDriversAnalysisTable ();
	     } 
	, 500);
} );
} );		
		
	
}
function formatNo(no ){
	return (parseFloat(Math.round(no * 100) / 100).toFixed(2));
}


  </script>
  
  
 <script type="text/javascript">
                $(document).ready(function() {
				


					$(".checkbox").change(function() {
			if($(this).prop('checked')==true) {
       var email =  ($(this).parent().siblings('.email')[0]);
		email= (email.innerHTML);
		
		
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              //console.log ("driver status has been changed")
            }
        };
       xmlhttp.open("GET", "activateDriverAdmin.php?email=" + email + "&active=1"  , true);
        xmlhttp.send();
		
		console.log (email);
		
    }
	else {
		
		
		var email =  ($(this).parent().siblings('.email')[0]);
		email= (email.innerHTML);
		
		
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              //console.log ("driver status has been changed")
            }
        };
        xmlhttp.open("GET", "activateDriverAdmin.php?email=" + email + "&active=0"  , true);
        xmlhttp.send();
		
		
		console.log (email);
	}
			
		
});
                 $('#drivers').dataTable(
					{
						 "columnDefs": [
            {
				//$filters = array("passengerName" => 1 , "passenger_gender" => 2 , "driverName" => 3 ,"driver_gender" =>4, "price" => 5,"date" => 6,"time" => 7,"seconds" => 8, "status" => 9);
                "targets": [ 7 ],
                "visible": false
               
            }],
						responsive: true
					}
					).yadcf([
					{column_number: 0, text_data_delimiter: ",", filter_type: "auto_complete",filter_container_id:"nameFilter"},
					{column_number : 1, text_data_delimiter: ",", filter_type: "auto_complete"},
					{column_number : 2, data: ['male','female'], filter_default_label: "Select gender",filter_match_mode : "exact",filter_container_id:"genderFilter"},
					{column_number : 3, text_data_delimiter: ",", filter_type: "auto_complete"},
					{column_number: 4, data: ["1","0"], filter_default_label: "Select active",filter_container_id:"activeFilter"   }]);
					/*
					$('#request_driver').dataTable(
					{
						 "columnDefs": [
            {
				//$filters = array("passengerName" => 1 , "passenger_gender" => 2 , "driverName" => 3 ,"driver_gender" =>4, "price" => 5,"date" => 6,"time" => 7,"seconds" => 8, "status" => 9);
                "targets": [ 6 ],
                "visible": false
               
            }],
						responsive: true
					}
					).yadcf([
					{column_number: 0, text_data_delimiter: ",", filter_type: "auto_complete"},
					{column_number : 1, text_data_delimiter: ",", filter_type: "auto_complete"},
					{column_number : 4, filter_type: "range_date",date_format: "dd/mm/yyyy"},
					{column_number : 2, data: ['male','female'], filter_default_label: "Select gender",filter_match_mode : "exact"},
					{column_number : 3,   filter_type: "range_number_slider"},  
					]);
					*/
					
						
					 makeAndAttachDriversSumaryTableHTML( getDriversDataArray() );
					 initializeDriversAnalysisTable (); 
					// updateSumaryTableWhenDriverTableChanges ();
						 var table = $('#drivers').DataTable();
					$('#drivers tbody').on( 'click', '.delete', function () {
						var email =  ($(this).parents('tr').children('.email')[0]);
		          email= (email.innerHTML);
				  console.log (email);
				  
				  
		var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              //console.log ("driver status has been changed")
            }
        };
        xmlhttp.open("GET", "deleteDriver.php?email=" + email  , true);
        xmlhttp.send();
		
		
    table
        .row( $(this).parents('tr') )
        .remove()
        .draw();
} );
                }
				
				
				
				);
				
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

.hide_column {
	DISPLAY: none
    }
   .borderRowColumn {
	BORDER-TOP: #dddddd 1px solid; BORDER-RIGHT: #dddddd 1px solid; BORDER-BOTTOM: #dddddd 1px solid; BORDER-LEFT: #dddddd 1px solid
    }
    .daterangepicker {
	WIDTH: 620px
    }
.calendar {
	FLOAT: left
    }
.capitalise {
	TEXT-TRANSFORM: capitalize
    }
	
	td {
    text-align: center; /* center checkbox horizontally */
    vertical-align: middle; /* center checkbox vertically */
}
</style>



<body>


<div style="text-align: center;">
<span class = "title"> list of drivers </span>
</div>

<div id="external_filter_container_wrapper">
   &nbsp;  &nbsp;    <span style="color:#f08a00;" class="glyphicon glyphicon-user"></span> <label style="font-size:20px;">choose the name :</label><div id="nameFilter"></div><br>
     &nbsp; &nbsp;    <span style="color:#f08a00;" class="glyphicon glyphicon-user"></span>&nbsp;<label style="font-size:20px;">choose gender  :</label><div id="genderFilter"></div><br>
	&nbsp; &nbsp; 	<span style="color:#f08a00;" class="glyphicon glyphicon-user"></span>&nbsp;<label style="font-size:20px;" >active status  :</label><div id="activeFilter"></div><br>	
</div>



<?php $tableID = 'drivers'; makeDataTableTable ($tableID,$tableHeader,$tableDatabaseColumns,$dataAraay);?>
</br>
<div style="text-align: center;"><span class = "title"> drivers summary </span></div></br>
<div id="DriversSummaryTableDiv" >
</div>
</br>
  <div id="genderPiechart" style="width: 800px; height: 450px;  border-style: solid;border-width: 2px; margin:auto;" ></div>
  </br>
  <div id="activePiechart" style="width: 800px; height: 450px;  border-style: solid;border-width: 2px; margin:auto;" ></div>
  </br> </br> </br></br>
  <script>/*  <div style="text-align: center;"><span class = "title"> drivers performance </span></div></br>*/</script>
 
<?php 
	//makeDataTableTable ("request_driver",$tableHeader2,$tableDatabaseColumns2,$dataAraay2);?>


<div style="text-align: center;">




</html>