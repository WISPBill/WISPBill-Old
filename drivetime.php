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

if(empty($_POST["start"])){
    
}elseif(empty($_POST["end"])){
    
}elseif(empty($_POST["startvalue"])){
    
}elseif(empty($_POST["endvalue"])){
    
}else{
$start = $_POST["start"];
$end =   $_POST["end"];
$startvalue = $_POST["startvalue"];
$endvalue = $_POST["endvalue"];

$start = inputcleaner($start,$mysqli);
$end = inputcleaner($end,$mysqli);
$startvalue = inputcleaner($startvalue,$mysqli);
$endvalue = inputcleaner($endvalue,$mysqli);

if($start == 'office'){
        $startlat = $officelat;
        $startlon = $officelon;
        
    }elseif($start == 'site'){
        if ($result = $mysqli->query("SELECT * FROM `location` WHERE `idlocation` = '$startvalue'")) {
    /* fetch associative array */

    while ($row = $result->fetch_assoc()) {
         $startlat = $row["latitude"];
         $startlon= $row["longitude"];

        }
        }
    }elseif($start == 'cus'){
        if ($result = $mysqli->query("SELECT * FROM `customer_info`  WHERE `idcustomer_info` = '$startvalue'")) {
    /* fetch associative array */

    while ($row = $result->fetch_assoc()) {
         $startlat = $row["lat"];
         $startlon= $row["lon"];

        }
        }
    }else{
        echo 'error with post';
    }
    
    if($end == 'office'){
        $endlat = $officelat;
        $endlon = $officelon;
        
    }elseif($end == 'site'){
          if ($result = $mysqli->query("SELECT * FROM `location` WHERE `idlocation` = '$endvalue'")) {
    /* fetch associative array */

    while ($row = $result->fetch_assoc()) {
         $endlat = $row["latitude"];
         $endlon= $row["longitude"];

        }
        }
    }elseif($end == 'cus'){
     if ($result = $mysqli->query("SELECT * FROM `customer_info`  WHERE `idcustomer_info` = '$endvalue'")) {
    /* fetch associative array */

    while ($row = $result->fetch_assoc()) {
         $endlat = $row["lat"];
         $endlon= $row["lon"];

        }
        }
    }else{
        echo 'error with post';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
 <link rel="stylesheet" href="leaflet/leaflet.css"/>
 <link rel="stylesheet" href="leaflet-routing-machine-2.6.2/css/leaflet-routing-machine.css" />
  <script src="leaflet/leaflet.js"></script>
  <script src="leaflet-routing-machine-2.6.2/dist/leaflet-routing-machine.js"></script>
  <style>
    #map{ min-width: inherit; min-height: 550px; }
  </style>
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
  <!-- Theme style -->
  <link rel="stylesheet" href="AdminLTE2/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="AdminLTE2/plugins/datatables/dataTables.bootstrap.css">
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
        Estimate Drive Time
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="dashbored.php"><i class="fa fa-dashboard"></i> Dashbored</a></li>
        <li class="active">Drive Time</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="box box-warning">
            <div class="box-header with-border">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" action="drivetime.php"method="post">
                <!-- text input -->
                         
						<label>What is the Starting Point</label>
					
                    
                    <div class="form-group">
                <select class="form-control" name="start" id="start" required>
                      <option value="" selected disabled>Please Select a Option</option>
				  <option value="office" >Office</option>
                  <option value="site" >Site</option>
                  <option value="cus" >Customer</option>
                </select>
                </div>
             
                    <span id="startload"></span>
                  
                <label>What is the End Point</label>
                <div class="form-group">
                <select class="form-control" name="end" id="end" required>
				  <option value="" selected disabled>Please Select a Option</option>
                   <option value="office" >Office</option>
                  <option value="site" >Site</option>
                  <option value="cus" >Customer</option>
                </select>
                  </div>
           
                 <span id="endload"></span>
                
				<div class="box-footer">
                <button type="submit"><i class="fa fa-repeat"></i> Run Estimate</button>
              </div>
				  
              </form>
              <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

  <script type="text/javascript">
  $("#start").change(function() {
  $("#startload").load("<?php echo"$url"; ?>/getlocation.php?name=startvalue&choice=" + $("#start").val());});
  </script>
  <script type="text/javascript">
  $("#end").change(function() {
  $("#endload").load("<?php echo"$url"; ?>/getlocation.php?name=endvalue&choice=" + $("#end").val());});
  </script>
                 </div>
            </div>
  
  <div id="map"></div>

  <script>

  // initialize the map
  <?php
  echo "
  var map = L.map('map').setView([$mapcenterlat, $mapcenterlon], $mapzoom);";
?>
  // load a tile layer
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      
    }).addTo(map);
  
 <?php
 echo"
    L.Routing.control({
     units: 'imperial',
  waypoints: [
    L.latLng($startlat, $startlon),
    L.latLng($endlat, $endlon)
  ]
}).addTo(map);";
 
?>
  </script>
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
<!-- AdminLTE App -->
<script src="AdminLTE2/dist/js/app.min.js"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
<!-- DataTables -->

</body>
</html>