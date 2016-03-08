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
  <link rel="stylesheet" href="AdminLTE2/plugins/daterangepicker/daterangepicker-bs3.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="AdminLTE2/plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->

  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="AdminLTE2/plugins/timepicker/bootstrap-timepicker.min.css">
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
      Schedule your work hours
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashbored.php"><i class="fa fa-dashboard"></i> Dashbored</a></li>
        <li class="active">Schedule Hours</li>
      </ol>
    </section>

    <!-- Main content -->
	 <form role="form" action="sethours2.php"method="post">
	<div class="row">
        <div class="col-xs-12">
    <section class="content">
	<div class="box">
            <div class="box-header">
			 <?php
// get error 
$error = $_SESSION['exitcodev2'];

                
$_SESSION['exitcodev2'] ='';
$errorlabel ='<label class="control-label" for="inputError" style="color: red;"><i class="fa fa-times-circle-o"></i> Input with
    error</label>';
?>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
              <div class="form-group">
                  <?php
					if($error == 'sunday'){
						echo "$errorlabel";
					}else{
						echo '<label>Do you Work on Sunday?</label>';
					}
					?>
                  <select class="form-control" name="sunday" id="sunday" required>
					<option value='' selected disabled>Please Select Yes or No</option>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                  </select>
                </div>
              
              <span id="sundaytime" style="display:none;">
              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Sunday Start Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="sundaystart">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
              
               <div class="bootstrap-timepicker">
                <div class="form-group">
                   <label>Sunday End Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="sundayend">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
                <!-- /.input group -->
            </span>
             
             <div class="form-group">
                  <?php
					if($error == 'monday'){
						echo "$errorlabel";
					}else{
						echo '<label>Do you Work on Monday?</label>';
					}
					?>
                  <select class="form-control" name="monday" id="monday" required>
					<option value='' selected disabled>Please Select Yes or No</option>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                  </select>
                </div>
              
              <span id="mondaytime" style="display:none;">
              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Monday Start Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="mondaystart">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
              
               <div class="bootstrap-timepicker">
                <div class="form-group">
                   <label>Monday End Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="mondayend">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
                <!-- /.input group -->
            </span>
              
              <div class="form-group">
                  <?php
					if($error == 'tuesday'){
						echo "$errorlabel";
					}else{
						echo '<label>Do you Work on Tuesday?</label>';
					}
					?>
                  <select class="form-control" name="tuesday" id="tuesday" required>
					<option value='' selected disabled>Please Select Yes or No</option>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                  </select>
                </div>
              
              <span id="tuesdaytime" style="display:none;">
              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Tuesday Start Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="tuesdaystart">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
              
               <div class="bootstrap-timepicker">
                <div class="form-group">
                   <label>Tuesday End Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="tuesdayend">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
                <!-- /.input group -->
            </span>
              
               <div class="form-group">
                  <?php
					if($error == 'wednesday'){
						echo "$errorlabel";
					}else{
						echo '<label>Do you Work on Wednesday?</label>';
					}
					?>
                  <select class="form-control" name="wednesday" id="wednesday" required>
					<option value='' selected disabled>Please Select Yes or No</option>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                  </select>
                </div>
              
              <span id="wednesdaytime" style="display:none;">
              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Wednesday Start Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="wednesdaystart">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
              
               <div class="bootstrap-timepicker">
                <div class="form-group">
                   <label>Wednesday End Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="wednesdayend">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
                <!-- /.input group -->
            </span>
              
              <div class="form-group">
                  <?php
					if($error == 'thursday'){
						echo "$errorlabel";
					}else{
						echo '<label>Do you Work on Thursday?</label>';
					}
					?>
                  <select class="form-control" name="thursday" id="thursday" required>
					<option value='' selected disabled>Please Select Yes or No</option>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                  </select>
                </div>
              
              <span id="thursdaytime" style="display:none;">
              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Thursday Start Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="thursdaystart">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
              
               <div class="bootstrap-timepicker">
                <div class="form-group">
                   <label>Thursday End Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="thursdayend">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
                <!-- /.input group -->
            </span>
              
              <div class="form-group">
                  <?php
					if($error == 'friday'){
						echo "$errorlabel";
					}else{
						echo '<label>Do you Work on Friday?</label>';
					}
					?>
                  <select class="form-control" name="friday" id="friday" required>
					<option value='' selected disabled>Please Select Yes or No</option>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                  </select>
                </div>
              
              <span id="fridaytime" style="display:none;">
              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Friday Start Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="fridaystart">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
              
               <div class="bootstrap-timepicker">
                <div class="form-group">
                   <label>Friday End Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="fridayend">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
                <!-- /.input group -->
            </span>
              
              <div class="form-group">
                  <?php
					if($error == 'saturday'){
						echo "$errorlabel";
					}else{
						echo '<label>Do you Work on Saturday?</label>';
					}
					?>
                  <select class="form-control" name="saturday" id="saturday" required>
					<option value='' selected disabled>Please Select Yes or No</option>
                    <option value="true">Yes</option>
                    <option value="false">No</option>
                  </select>
                </div>
              
              <span id="saturdaytime" style="display:none;">
              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Saturday Start Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="saturdaystart">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
              
               <div class="bootstrap-timepicker">
                <div class="form-group">
                   <label>Saturday End Time</label>

                  <div class="input-group">
                    <input type="text" class="form-control timepicker" name="saturdayend">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </div>
                <!-- /.input group -->
            </span>
			   
				<div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
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

<script src="AdminLTE2/plugins/iCheck/icheck.min.js"></script>

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

<script>
        $(document).ready(function (){
            $("#sunday").change(function() {
                // foo is the id of the other select box 
                if ($(this).val() != "false") {
                    $("#sundaytime").show();
                }else{
                    $("#sundaytime").hide();
                } 
            });
        });
          $(document).ready(function (){
            $("#monday").change(function() {
                // foo is the id of the other select box 
                if ($(this).val() != "false") {
                    $("#mondaytime").show();
                }else{
                    $("#mondaytime").hide();
                } 
            });
        });
       $(document).ready(function (){
            $("#tuesday").change(function() {
                // foo is the id of the other select box 
                if ($(this).val() != "false") {
                    $("#tuesdaytime").show();
                }else{
                    $("#tuesdaytime").hide();
                } 
            });
        });
       $(document).ready(function (){
            $("#wednesday").change(function() {
                // foo is the id of the other select box 
                if ($(this).val() != "false") {
                    $("#wednesdaytime").show();
                }else{
                    $("#wednesdaytime").hide();
                } 
            });
        });
       $(document).ready(function (){
            $("#thursday").change(function() {
                // foo is the id of the other select box 
                if ($(this).val() != "false") {
                    $("#thursdaytime").show();
                }else{
                    $("#thursdaytime").hide();
                } 
            });
        });
       $(document).ready(function (){
            $("#friday").change(function() {
                // foo is the id of the other select box 
                if ($(this).val() != "false") {
                    $("#fridaytime").show();
                }else{
                    $("#fridaytime").hide();
                } 
            });
        });
       $(document).ready(function (){
            $("#saturday").change(function() {
                // foo is the id of the other select box 
                if ($(this).val() != "false") {
                    $("#saturdaytime").show();
                }else{
                    $("#saturdaytime").hide();
                } 
            });
        });
    </script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();

    //Date range picker
    $('#reservation').daterangepicker();
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 5, format: 'MM/DD/YYYY h:mm A'});
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  });
</script>
</body>
</html>