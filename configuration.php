
<html>



<head>



<script type="text/javascript" language="javascript"  src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="javascript"  src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="jquery.timepicker.js"></script>
  <link rel="stylesheet" type="text/css" href="jquery.timepicker.css" />

  <script type="text/javascript" src="lib/bootstrap-datepicker.js"></script>
  <link rel="stylesheet" type="text/css" href="lib/bootstrap-datepicker.css" />

 
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

<script type="text/javascript" language="javascript"  src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">

 <script type="text/javascript">
                $(document).ready(function() {
					
	function removeMember(id = null) {
    if(id) {
        // click on remove button
        $("#removeBtn").unbind('click').bind('click', function() {
			console.log("i am in delete");
            $.ajax({
                url: 'deletePrice.php',
                type: 'post',
                data: {member_id : id},
                dataType: 'json',
                success:function(response) {
                    if(response.success == true) {                      
                        $(".removeMessages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                             '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
                            '</div>');
 
                        // refresh the table
                        manageMemberTable.ajax.reload(null, false);
 
                        // close the modal
                        $("#removeMemberModal").modal('hide');
 
                    } else {
                        $(".removeMessages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                             '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
                            '</div>');
                    }
                }
            });
        }); // click remove btn
    } else {
        alert('Error: Refresh the page again');
    }
}
					 $('#startTime').timepicker({ 'timeFormat': 'H:i:s' });
					  $('#endTime').timepicker({ 'timeFormat': 'H:i:s' });
                    $('#prices').dataTable({
					"ajax": "getPrices.php",
					"order": []});
					var manageMemberTable;
					manageMemberTable = $("#prices").DataTable();
    $("#addMemberModalBtn").on('click', function() {
		console.log("i am here");
        // reset the form 
        $("#createMemberForm")[0].reset();
        // remove the error 
        $(".form-group").removeClass('has-error').removeClass('has-success');
        $(".text-danger").remove();
        // empty the message div
        $(".messages").html("");
 
        // submit form
        $("#createMemberForm").unbind('submit').bind('submit', function() {
 
            $(".text-danger").remove();
 
            var form = $(this);
 
            // validation
            var startTime = $("#startTime").val();
            var endTime = $("#endTime").val();
            var price = $("#price").val();
           
 
            if(startTime == "") {
                $("#startTime").closest('.form-group').addClass('has-error');
                $("#startTime").after('The Name field is required');
            } else {
                $("#startTime").closest('.form-group').removeClass('has-error');
                $("#startTime").closest('.form-group').addClass('has-success');              
            }
 
            if(endTime == "") {
                $("#endTime").closest('.form-group').addClass('has-error');
                $("#endTime").after('The Address field is required');
            } else {
                $("#endTime").closest('.form-group').removeClass('has-error');
                $("#endTime").closest('.form-group').addClass('has-success');               
            }
 
            if(price == "") {
                $("#price").closest('.form-group').addClass('has-error');
                $("#price").after('The Contact field is required');
            } else {
                $("#price").closest('.form-group').removeClass('has-error');
                $("#price").closest('.form-group').addClass('has-success');               
            }
 
        
 
            if(startTime && endTime  && price) {
                //submi the form to server
                $.ajax({
                    url : form.attr('action'),
                    type : form.attr('method'),
                    data : form.serialize(),
                    dataType : 'json',
                    success:function(response) {
 
                        // remove the error 
                        $(".form-group").removeClass('has-error').removeClass('has-success');
 
                        if(response.success == true) {
                            $(".messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                             '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
                            '</div>');
 
                            // reset the form
                            $("#createMemberForm")[0].reset();      
 
                            // reload the datatables
                            manageMemberTable.ajax.reload(null, false);
                            // this function is built in function of datatables;
                        } else {
                            $(".messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                             '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                             '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
                            '</div>');
                        } // /else
                    } // success 
                }); // ajax subit               
            } /// if
 
 
            return false;
        }); // /submit form for create member
    }); // /add modal

                });
				
				
 </script>

 <title>prices page</title>
</head>


<style>
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




<body >
	 
				
	 </br>  </br>  </br>  </br>  </br> 
	 <div style=" width:40%; margin:auto; text-align:center; ">
	  
	 <button class="btn btn-default pull pull-right" data-toggle="modal" data-target="#addMember" id="addMemberModalBtn">
                    <span class="glyphicon glyphicon-plus-sign"></span> Add Price
                </button>
				
				<div class="modal fade" tabindex="-1" role="dialog" id="addMember">
     <div class="modal-dialog" role="document">
     <div class="modal-content">
     <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
     <h4 class="modal-title"><span class="glyphicon glyphicon-plus-sign"></span>  Add Price </h4>
     </div>
      
     <form class="form-horizontal" action="addPrice.php" method="POST" id="createMemberForm">
 
     <div class="modal-body">
        <div class="messages"></div>
 
       <div class="form-group"> <!--/here teh addclass has-error will appear -->
             <label for="startTime" class="col-sm-2 control-label">start time</label>
             <div class="col-sm-10"> 
             <input type="text" class="form-control time" id="startTime" name="startTime" placeholder="start time">
                <!-- here the text will apper -->
             </div>
             </div>
			 
			 
             <div class="form-group">
             <label for="endTime" class="col-sm-2 control-label">end time</label>
             <div class="col-sm-10">
			  <input id="endTime" type="text" class="time form-control" name="endTime" placeholder="end time" />
             </div>
             </div>
			 
             <div class="form-group">
             <label for="contact" class="col-sm-2 control-label">price per km</label>
             <div class="col-sm-10">
             <input type="text" class="form-control" id="price" name="price" placeholder="Price">
             </div>
             </div>
                            
 
     </div>
     <div class="modal-footer">
     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
     <button type="submit" class="btn btn-primary">Save changes</button>
     </div>
     </form> 
     </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- /add modal -->
	
	
	<div class="modal fade" tabindex="-1" role="dialog" id="removeMemberModal">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title"><span class="glyphicon glyphicon-trash"></span> Remove Price</h4>
  </div>
  <div class="modal-body">
  <p>Do you really want to remove ?</p>
  </div>
  <div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <button type="button" class="btn btn-primary" id="removeBtn">Delete</button>
  </div>
  </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /remove modal -->


	  <table  id="prices" class='display' cellspacing='0' width='100%' >                  
                    <thead>
                        <tr>
							<th>ID</th>
                            <th>start time</th>
                            <th>end time</th>                                                   
                            <th>price</th>
							 <th></th>
							
                        </tr>
                    </thead>
                </table>
				</div>

   </body>





</html>