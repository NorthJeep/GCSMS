<?php
	if (isset($_GET['row']) && isset($_GET['action'])) {
		include 'config.php';
		$key = $_GET['row'];
        $stat = $_GET['action'];
        $message = "";
        if ($stat == "deactivate") {
            $query = "update r_couns_appointment_type set Appmnt_STAT = 'Inactive' where Appmnt_TYPE = '$key'";
            $result = mysqli_query($db,$query);
            if ($result) {
                $message = "'.$key.' is now deactivated!"; 
            }
        } else {
            $query = "update r_couns_appointment_type set Appmnt_STAT = 'Active' where Appmnt_TYPE = '$key'";
            $result = mysqli_query($db,$query);
            if ($result) {
                $message = "'.$key.' is now activated!"; 
            }
        }
        header("Location: testing.php");
        echo '<script>alert("'.$message.'");</script>';
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />
</head>

    <!-- <a data-toggle="modal" href="#myModal"> Forgot Password?</a> -->
    <div class="table container" style="margin-top: 50px">
    <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered">
    	<thead>
    		<td><b>Visit Type</b></td>
    		<td><b>Visit Description</b></td>
    		<td><b>Status</b></td>
    		<td><b>Action</b></td>
    	</thead>
    	<tbody id="visitlist">
    		<?php

	    		include 'config.php';
	    		$query = 'select * from r_couns_appointment_type';
	    		$result = mysqli_query($db,$query);
	    		if ($result) {
	    			while ($row = $result -> fetch_assoc()) {
		    			echo '
				    		<tr>
				    			<td id="visit-type">'.$row["Appmnt_TYPE"].'</td>
				    			<td>description</td>
				    			<td id="visit-stat">'.$row['Appmnt_STAT'].'</td>
				    			<td>
				    				<a id="delete" href="#deactivateModal" data-toggle="modal" class="btn btn-danger delete"><i class="fa fa-trash-o"></i></a>
				    			</td>
				    		</tr>
		    			';
	    			}
	    		}
    		?>
    	</tbody>
    </table>
	</div>

    <!-- Modal -->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="deactivateModal" class="modal fade">
    	<div class="modal-dialog" style="width: 30%;">
        	<div class="modal-content">
            	<div class="modal-header">
                	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Warning!</h4>
                </div>
                <div class="modal-body">
                	<form id="modalform" method="post" action="">
                		<br>
                		<label>You are about to change a data,</label><br>
                		<label>continue?</label><br><br>
	                	<button data-dismiss="modal" class="btn btn-cancel" type="submit">CANCEL</button>
	                    <button class="btn btn-info" type="submit" name="deactivate">OK</button>
                	</form>
                </div>
            </div>
        </div>
    </div>
    <!-- modal -->

    <!-- Placed js at the end of the document so the pages load faster -->

    <!--Core js-->
    <script src="js/jquery.js"></script>
    <script type="text/javascript">
    	$("#visitlist").on("click","a[id='delete']",function(){
    		$key = $(this).parent().siblings("td[id='visit-type']").text();
            if ($(this).parent().siblings("td[id='visit-stat']").text() == "Active") {
                $("#modalform").attr("action","?row="+$key+"&action=deactivate");
            } else {
                $("#modalform").attr("action","?row="+$key+"&action=activate");
            }
    	});
    </script>
    <script src="bs3/js/bootstrap.min.js"></script>

  </body>
</html>