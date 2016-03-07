<?php
/*
    WISPBill a PHP based ISP billing platform
    Copyright (C) 2015  Turtles2

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published
    by the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

	@turtles2 on ubiquiti community, DSLReports and Netonix 
 */
require_once('./session.php');
require_once('./fileloader.php');
$mysqli = new mysqli("$ip", "$username", "$password", "$db");

$adminid = $_SESSION['adminid'];
$passdata = $_SESSION['troubleshooting'];

if(empty($passdata)){
    header('Location: troubleshooting.php');
}else{
    $cusinfoid = $passdata["id"];
    $issue = $passdata["issue"];
     $thisticketid = $passdata["ticket"];
}

if ($result = $mysqli->query("SELECT * FROM `admin_users` WHERE `idadmin` = $adminid")) {
    /* fetch associative array */
     while ($row = $result->fetch_assoc()) {
    $fname = $row["fname"];
    $lname = $row["lname"];
    $userimage = $row["img"];
}
       /* free result set */
    $result->close();
}// end if

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>WISP Bill</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="AdminLTE2/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="AdminLTE2/plugins/datatables/dataTables.bootstrap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="AdminLTE2/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  
  <link rel="stylesheet" href="AdminLTE2/dist/css/skins/<?php echo"$guiskin";?>.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  
  <!-- Load d3.js and c3.js -->
   <link href="d3chart/c3.css" rel="stylesheet" type="text/css">
<script src="d3chart/d3.min.js" charset="utf-8"></script>
<script src="d3chart/c3.min.js"></script>
  <![endif]-->
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition <?php echo"$guiskin";?> sidebar-mini">
    						<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Ticket Data View</h4>
      </div>
      <div class="modal-body" id="modal-body">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="dashbored.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>WISP</b> Bill</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>WISP</b> Bill</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-<?php echo "$noticode"; ?>"><?php echo "$notitotal"; ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?php echo "$notitotal"; ?> messages</li>
              <li>
                <!-- inner menu: contains the messages -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="viewnotifications.php?id=<?php echo "$notiid"; ?>">
                      <div class="pull-left">
                        <!-- User Image -->
                        <img src="<?php echo "$notiimage"; ?>" class="img-circle" alt="User Image">
                      </div>
                      <!-- Message title and timestamp -->
                      <h4>
                        <?php echo "$notisource"; ?>
                        <small><i class="fa fa-clock-o"></i><?php echo "$notitime"; ?></small>
                      </h4>
                      <!-- The message -->
                      <p><?php echo "$notimesg"; ?></p>
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
                <!-- /.menu -->
              </li>
              <li class="footer"><a href="viewnotifications.php">See All Messages</a></li>
            </ul>
          </li>
          <!-- /.messages-menu -->
 
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="<?php echo "$userimage"; ?>" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo "$fname $lname"; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="<?php echo "$userimage"; ?>" class="img-circle" alt="User Image">
                <p>
                  <?php echo "$fname $lname"; ?> 
                </p>
              </li>
              <!-- Menu Body -->    
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="settings.php" class="btn btn-default btn-flat">Settings</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="settings.php" ><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo "$userimage"; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo "$fname $lname"; ?></p>
          
        </div>
      </div>
<?php
// Get Menu all echo login is in file
 require_once("$menue");
?>
     

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Troubleshooting
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashbored.php"><i class="fa fa-dashboard"></i> Dashbored</a></li>
        <li class="active">Troubleshooting</li>
      </ol>
    </section>

    <!-- Main content -->
	 
	<div class="row">
        <div class="col-xs-12">
    <section class="content">
	<div class="box">
            <div class="box-header">
			 <?php
// get error 
$error = $_SESSION['exitcodev2'];
              
                $errorlabel ='<label class="control-label" for="inputError" style="color: red;"><i class="fa fa-times-circle-o"></i> Input with
    error</label>';
				$_SESSION['exitcodev2'] = '';
?> 
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                 <h4>Customer Tickets</h4>
                <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                 <th>Show Full Info</th>
				  <th>Issue</th> 
                  <th>Assigned To</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
				 <?php
                  /*0 unassigned
  *1 assigned but not solved
  *2 assigned and solved
  *3 assigned and escalation needed
  *4 solved with escalation 
  */
                if ($result = $mysqli->query("SELECT * FROM `ticket` WHERE `customer_info_idcustomer_info` = '$cusinfoid'")) {
      /* fetch associative array */
         
    while ($row = $result->fetch_assoc()) {
      $oldissue	= $row["issue"];
      $oldstatus= $row["status"];
      $ticketid= $row["idticket"];
      
      if ($result2 = $mysqli->query("SELECT * FROM `tasks` WHERE `ticket_idticket` = '$ticketid'")) {
     while ($row2 = $result2->fetch_assoc()) {
     $taskadmin = $row2["admin_users_idadmin"];
   
     }
     }
     if(empty($taskadmin)){
        $fname = 'Unassigned';
        $lname = '';
     }else{
  if ($result3 = $mysqli->query("SELECT * FROM `admin_users` WHERE `idadmin` = '$taskadmin'")) {
    /* fetch associative array */
     while ($row3 = $result3->fetch_assoc()) {
    $fname = $row3["fname"];
    $lname = $row3["lname"];
   
}
       /* free result set */
    $result3->close();
}// end if
     }// end if
    
  if($oldstatus == '0'){
    if($thisticketid == $ticketid){
        $oldstatus = 'Current Ticket';
    }else{
        $oldstatus = 'Unassigned';
    }
  }elseif($oldstatus == '1'){
    $oldstatus = 'Assigned But Not Solved';
  }elseif($oldstatus == '2'){
     $oldstatus = 'Assigned and Solved';
  }elseif($oldstatus == '3'){
        $oldstatus = 'Assigned and Escalation Needed';
  }elseif($oldstatus == '4'){
    $oldstatus = 'Solved with Escalation ';
  }else{
    
  }

  
     echo" <tr>
    
		<td>
		   <button type='button' class='btn btn-block btn-success btn-sm' data-toggle='modal' data-target='#myModal' id='$ticketid' onClick='getdatainfo(this.id)'>Show Ticket Data</button>
		</td>
    <td>$oldissue</td> 
<td>$fname $lname</td>
<td>$oldstatus</td>

  </tr>";
    $ticketid= '';
    $taskadmin = '';}
}

?>
	
                </tbody>
                <tfoot>
                <tr>
                   
									<th>Show Full Info</td>
				  <th>Issue</th> 
			 <th>Assigned To</th>
           <th>Status</th>
                </tr>
                </tfoot>
              </table>
              <br></br>
               <h4>Customer History</h4>
				 <?php
                 echo '
 <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr> 
				  <th>Event</th>
				  <th>Date</th> 
				 <th>Preformed By</th>
                </tr>
                </thead>
                <tbody>';
				
 if ($result = $mysqli->query("SELECT * FROM  `history` WHERE  `customer_info_idcustomer_info` =  '$cusinfoid'
                              ORDER BY  `history`.`date` ASC ")) {
    /* fetch associative array */
    foreach ($result as $row){
		 $note = $row["event"];
        $date = $row["date"];
		 $taid = $row["admin_users_idadmin"];
			 $date = date('n/j/y  g:i A',"$date");
					if ($result3 = $mysqli->query("SELECT * FROM `admin_users` WHERE `idadmin` = $taid")) {
				/* fetch associative array */
				 while ($row3 = $result3->fetch_assoc()) {
				 $afname= $row3["fname"];
                    $alname= $row3["lname"];
					
					 }
  
					$result3->close();
						}else{
						 echo'Something went wrong with the database please contact your webmaster';
							exit;
					}
		// Echo Data
		echo "<tr>
		<td>$note</td>
		<td>$date</td>
		<td>$afname $alname</td>
		</tr>";
	}
  
    $result->close();
}else{
       echo'Something went wrong with the database please contact your webmaster';
       exit;
    }
  

echo '
                </tbody>
                <tfoot>
                <tr>
				   <th>Event</th>
				  <th>Date</th> 
				 <th>Preformed By</th>
                </tr>
                </tfoot>
              </table>';
?>
                    
			
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
            <div class="box box-warning">
            <div class="box-header with-border">
                <h4>Pull Customer Data</h4>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" action="troubleshooting3.php"method="post">
                <!-- text input -->
			<label>Data of Interest</label>
                <div class="form-group">
                <select class="form-control" name="data" required>
				  <option value="" selected disabled>Please Select Data Type</option>
                  <option value="rxrate" >CPE Download Traffic</option>
                  <option value="txrate" >CPE Uplaod Traffic</option>
                  <option value="signallev" >Signal Strength</option>
                  <option value="noise" >Noise Floor</option>
                  <option value="ccq" >Transmit CCQ</option>
                  <option value="latency" >Latency</option>
                </select>
                  </div>
                
                <label>Select Timeframe</label>
                <div class="form-group">
                <select class="form-control" name="time" required>
				  <option value="" selected disabled>Please Select Timeframe</option>
                  <option value="3600" >Hour</option>
                  <option value="86400" >Day</option>
                  <option value="604800" >Week</option>
                  <option value="2592000" >Month</option>
                  <option value="31536000" >Year</option>
                </select>
                  </div>
				<div class="box-footer">
                <button type="submit""><i class="fa fa-repeat"></i> Refresh View</button>
              </div>
				  
              </form>
        
                 </div>
            </div>
            <div class="box box-success">
            <div class="box-header with-border">
                <h3>Data Viewer</h3>
            </div>
  <div id="chart"></div>
            </div>
            
               <div class="box box-success">
            <div class="box-header with-border">
                <h3>Ping Host</h3>
            </div>
  <div id="ping" class="box-body">
       <button type='button' class='btn btn-block btn-success btn-lg' style="width: 25%; float: right; margin-right: 40%;" id='<?php echo "$cusinfoid";?>' onClick='getping(this.id)'>Run Ping Test</button>
    <span id="pingresult"></span>
    
  </div>
               </div>
            
            <div class="box box-success">
            <div class="box-header with-border">
                 <h3>Ticket Actions</h3>
            </div>
  <div id="ping" class="box-body">
        <form role="form" action="troubleshooting4.php"method="post">
            <select class="form-control" name="status" required>
					<option value='' selected disabled>Please Select Ticket Status</option>
                    <option value="0">Send to Ticket Queue</option>
                    <option value="2">Solved</option>
                    <option value="4">Solved with Escalation</option>
                  </select>
                </div>
                 
                 <div class="form-group">
                  <?php
					if($error == 'note'){
						echo "$errorlabel";
					}else{
						echo '<label>What did you do?</label>';
					}
					?>
                  <textarea class="form-control" rows="5" placeholder="What did you do to help the user? Is there anything that tech needs to know?" name="text1" required></textarea>
                </div>
                 
				<div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            
        </form>
    
  </div>
               </div>
            
            
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      <?php echo "$rightfooter";?>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2015 <a href="<?php echo "$companysite";?>"><?php echo "$company";?></a>.</strong> All rights reserved.
  </footer>

<!-- ./wrapper -->
<?php
if(empty($_POST["data"])){
    
}elseif(empty($_POST["time"])){
    
}else{
    $intdata = $_POST["data"];
$timeframe = $_POST["time"];

$time = time();
  $datastarttime = $time-$timeframe;

$intdata = $mysqli->real_escape_string($intdata);
$timeframe= $mysqli->real_escape_string($timeframe);
$stamp = array();
$gdata = array();

if ($result5 = $mysqli->query("SELECT * FROM `customer_info` WHERE `idcustomer_info` = '$cusinfoid'")) {
     while ($row5 = $result5->fetch_assoc()) {
     $cusdevice = $row5["devices_iddevices"];

     }
     }

if ($result3 = $mysqli->query("SELECT * FROM `cpe_data` WHERE `devices_iddevices` = '$cusdevice' and `datetime` >= '$datastarttime'")) {
     while ($row3 = $result3->fetch_assoc()) {
     $time= $row3["datetime"];
     $data= $row3["$intdata"];
     
     if($intdata == 'rxrate'){
       $data = $data / 1000000;
     }elseif($intdata == 'txrate'){
       $data = $data / 1000000;
     }else{
        // nothing
     }
     
     array_push($gdata, $data);
     array_push($stamp, $time);
     }
     }

}// End if
?>
<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.1.4 -->
<script src="AdminLTE2/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="AdminLTE2/bootstrap/js/bootstrap.min.js"></script>
<script src="AdminLTE2/plugins/select2/select2.full.min.js"></script>
<script src="AdminLTE2/plugins/input-mask/jquery.inputmask.js"></script>
<script src="AdminLTE2/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="AdminLTE2/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- AdminLTE App -->
<script src="AdminLTE2/dist/js/app.min.js"></script>
<!-- DataTables -->
<script src="AdminLTE2/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="AdminLTE2/plugins/datatables/dataTables.bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="AdminLTE2/plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="AdminLTE2/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="AdminLTE2/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
		<script type="text/javascript">
 function getdatainfo(clicked_id)
			{
  $("#modal-body").load("<?php echo"$url"; ?>/ticketdataget.php?choice=" + clicked_id);
 }
  </script>
  
  	<script type="text/javascript">
 function getping(clicked_id)
			{
  $("#pingresult").load("<?php echo"$url"; ?>/runping.php?choice=" + clicked_id);
 }
  </script>
    
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true
    });
  });
</script>
 <script>
    var chart = c3.generate({
    bindto: '#chart',
    data: {
      x: 'x',
      columns: [
        ['x',<?php foreach($stamp as $value){
                  $value = $value *1000;
       echo "$value,";
      }
       
      ?> ],
        [<?php echo "'$intdata',";
        foreach($gdata as $value){              
       echo "$value,";
      }
       
      ?>]
      ],
        types: {
            data1: 'area',

            // 'line', 'spline', 'step', 'area', 'area-step' are also available to stack
        },
        groups: [[<?php echo "'$intdata'"; ?>]]
    },
    zoom: {
        enabled: true
    },
    subchart: {
        show: true
    },
     axis: {
        x: {
            type: 'timeseries',
            tick: {
                format: '%I:%M %p %m/%e/%y'
            }
        }
    }
});
  </script>
</body>
</html>