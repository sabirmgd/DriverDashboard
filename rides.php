<?php
//date_default_timezone_set("Asia/Kuala_Lumpur");
//Africa/Khartoum
date_default_timezone_set("Africa/Khartoum");
include("config.php");
//include('session.php');

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
			<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
			<script type="text/javascript">
				$(document).ready(function() {
				// column constants 
				var ID =0; 
				//var S
				var table =    $('#requests').DataTable({
				retrieve: true,
				"processing": true,
				"ajax": 'getRides.php',

				"columnDefs": [
				{
				//$filters = array("passengerName" => 1 , "passenger_gender" => 2 , "driverName" => 3 ,"driver_gender" =>4, "price" => 5,"date" => 6,"time" => 7,"seconds" => 8, "status" => 9);
				"targets": [ 8 ],
				"visible": false 


				}], // end of columnDefs
				
				responsive: true,
				"pageLength": 50,
				"aaSorting" : [[0, "desc"]],
				// here is where you change the colors
				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
				{
				if ( aData[10] == "pending" )
				{
				$('td', nRow).css('background-color', 'Orange');
				}
				else if ( aData[10] == "accepted" )
				{
				$('td', nRow).css('background-color', '#8D95DC');
				}
				else if ( aData[10] == "driver on the way" )
				{
				$('td', nRow).css('background-color', '#2CF51E');
				}
				else if ( aData[10] == "passenger on board" )
				{
				$('td', nRow).css('background-color', '#EA1DF9');
				}
				else if ( aData[10] == "canceled" )
				{
				$('td', nRow).css('background-color', '#F91D58');
				}
					
				}// end of colors
				});// end of dataTables initialization

				yadcf.init(table , [

				{column_number :1, text_data_delimiter: ",", filter_type: "auto_complete"},
				{column_number : 3, text_data_delimiter: ",", filter_type: "auto_complete"},
				{column_number: 6 , filter_type: "range_date",  date_format: "dd/mm/yyyy",filter_container_id: "dateFilter"},
				{column_number : 10, data: ["noDriver", "completed","canceled","pending","accepted"], filter_default_label: "Status",filter_container_id:"statusFilter"},
				{column_number : 2, data: ['male','female'], filter_default_label: "Select gender",filter_match_mode : "exact"},
				{column_number : 4, data: ['male','female'], filter_default_label: "Select gender",filter_match_mode : "exact"},

				{     column_number: 5,        filter_type: "range_number_slider" },
				]); // end of filters

				/*.yadcf([
				{column_number: 8,filter_container_id:"timeFilter", filter_type: "range_number_slider", data: [0,24]}
				*/
				//table.api({"ajax": "getRides.php"});
				
				setInterval( function () {
				table.ajax.reload();
				}, 10000 );
				

				var d = new Date();
				var 				n = d.getHours();

				var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth()+1; //January is 0!
				var yyyy = today.getFullYear();

				if(dd<10) {
						dd='0'+dd
						} 

				if(mm<10) {
					mm='0'+mm
					} 

						today = dd+'/'+mm+'/'+yyyy;

								/*
								yadcf.exFilterColumn(table, [
								[8, {
								from: n,
								to: 24
								}],
								[
								6, {
								from:  today

								}

								]
								]);
								*/
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

								var MONEY =5;
								var STATUS = 10;



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
									//	console.log ((filteredPoC_TableArray[i][STATUS]).toLowerCase());
										// calculate no_ofApprovedPoC, no_ofPendingPoC,no_ofRejectedPoC
										if ( (filteredPoC_TableArray[i][STATUS]).toLowerCase() == "accepted"){

										no_ofAccepted++;}
										else if ( (filteredPoC_TableArray[i][STATUS]).toLowerCase() == "nodriver")
										no_ofNoDriver++;
										else if ( (filteredPoC_TableArray[i][STATUS]).toLowerCase() == "completed"){
										no_ofCompleted++;
										money += filteredPoC_TableArray[i][MONEY] ;
										}

										else if ( (filteredPoC_TableArray[i][STATUS]).toLowerCase() == "canceled")
										no_ofCanceled++;
										else if ( (filteredPoC_TableArray[i][STATUS]).toLowerCase() == "pending")
										no_ofPending++;


										}

										// create the HTML 
										var analysisTableHTML;
										// add the table ID and the header 
										analysisTableHTML= '<table id="summaryTable"  style=" border: solid 1px black; width:30%;" class="display dataTable" cellspacing="0" ">\
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
													analysisTableHTML +=		'<td class="borderRowColumn">' + no_ofAccepted  +'</td></tr>';

												analysisTableHTML +=		'<tr class="borderRowColumn"><td class="borderRowColumn">'+  "cash collected in SDG"	 +'</td>';
													analysisTableHTML +=		'<td class="borderRowColumn">' + formatNo(money)  +'</td></tr>';

												analysisTableHTML += '</tbody></table>';



										google.charts.load("current", {packages:["corechart"]});
										google.charts.setOnLoadCallback(drawChart);
										function drawChart() {
										var data = google.visualization.arrayToDataTable([
										['status', 'rides'],
										['completed',     no_ofCompleted],
										['canceled',      no_ofCanceled],
										['no driver',      no_ofNoDriver],
										['accepted',      no_ofPending],
										['pending',      no_ofAccepted]
										]);

										var options = {
										title: 'rides based on status',
										is3D: false,
										colors:['green','orange','red','yellow','blue'],
										};
									//	console.log("am here");
										var chart = new google.visualization.PieChart(document.getElementById('ridesPiechart'));
										chart.draw(data, options);
										}



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
										function formatNo(no ){
										return (parseFloat(Math.round(no * 100) / 100).toFixed(2));
										}		

									</script>

								
								<style>
									.title {

									font-family:Georgia,serif;
									color:#4E443C;
									font-variant: small-caps; text-transform: none; font-weight: 100; margin-bottom: 0;
									margin:auto;
									font-size:30px;
									text-align: center;
									}
									
	

								</head>

								
								<body>
									<!-- the rides-->
									<?php include('welcome.php'); ?>
									<div id="requestsDiv" style="width:100%; margin:auto;">

										<div style="text-align: center;">



											<span class = "title"> list of rides </span><br><br>
												</div>

												<!-- placeholder for filters-->
												<div id="external_filter_container_wrapper">
													&nbsp;  &nbsp;    <span style="color:#f08a00;" class="glyphicon glyphicon-calendar"></span> <label style="font-size:20px;">choose the date :</label><div id="dateFilter"></div><br>
														&nbsp; &nbsp;    <span style="color:#f08a00;" class="glyphicon glyphicon-time"></span>&nbsp;<label style="font-size:20px;">choose the time  :</label><div id="timeFilter"></div><br>
															&nbsp; &nbsp; 	<span style="color:#f08a00;" class="glyphicon glyphicon-user"></span>&nbsp;<label style="font-size:20px;" >the status  :</label><div id="statusFilter"></div><br>	
															</div>




															<table  id="requests" class='display' cellspacing='0' width='100%' >                  
																<thead>
																	<tr>
																		<th>ID</th>
																		<th>Passenger Name</th>
																		<th>Passenger Gender</th>                                                   
																		<th>Driver Name</th>
																		<th>Driver Gender</th>
																		<th>price</th>
																		<th>day</th>
																		<th>time</th>
																		<th>time in seconds</th>
																		<th>notes</th>
																		<th>status</th>
																		<th>acception time</th>
																		<th>driver on the way</th>
																		<th>passenger on board time</th>
																		<th>completion time</th>
																	</tr>
																</thead>
															</table>

														</div>



														<div style="text-align: center;">
															<span class = "title"> summary </span><br><br>
																</div>

																<div id="summaryTableDiv" style="width:70%; margin:auto;">
																</div>
															</br>
															<div id="ridesPiechart" style="width: 800px; height: 450px;  border-style: solid;border-width: 2px; margin:auto;" ></div>
														</body>



													</html>