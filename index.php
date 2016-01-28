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
// See if loged in
session_start();
if (isset($_SESSION['logged'])){
	$ses = $_SESSION['logged'];
if($ses=='yes'){
    //user is logged in
    session_regenerate_id();
    header('Location: dashbored.php');
        exit;
} else{
    // user is not logged in so do nothing
}
} else {
	
}

// start of https force 
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
// end of https force
// start of input error check
if (isset($_SESSION['exitcode'])){
	$exitcode = $_SESSION['exitcode'];
    if($exitcode == 'password empty'){
        echo'<h1>A Password was not Submitted.</h1>';
    } elseif ($exitcode == 'user empty'){
        echo'<h1> No Username has been Submitted</h1>';
    }elseif ($exitcode == 'no username'){
        echo'<h1> The Username or Password was Wrong</h1>';
    } elseif ($exitcode == 'no log'){
        echo'<h1> You have to log in to view this page</h1>';
    }else{
        // do nothing
    }
} else {
	
}
 $_SESSION['exitcode'] = '';   
// End of PHP
//Start of HTML
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<title>WISP Billing Admin</title>
		<meta name="description" content="" />
		<meta name="author" content="Home" />

		<meta name="viewport" content="width=device-width; initial-scale=1.0" />
		<link rel="stylesheet" href="AdminLTE2/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="AdminLTE2/dist/css/AdminLTE.min.css">
		<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
		
	</head>

	<body>
		<div align="center">
			<img src="img/2.jpg" 
	style="width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: -5000;">
   
    </div>
	<body class="hold-transition login-page">
<div class="login-box" style="float: right; margin-right: 10%;">
  
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form action="login.php" method="post">
      <div class="form-group has-feedback">
        <input  class="form-control" placeholder="Username or Email" name="user">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="pass">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
	  <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Email Password" name="epass">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4" style="float: right;";>
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <a href="password.php">I forgot my password</a><br>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="AdminLTE2/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="AdminLTE2/bootstrap/js/bootstrap.min.js"></script>

<div style="position: fixed; bottom: 0; width: 98%;">
	<p style="font-size: 8pt; float: right; color: black;">"Cell Tower" by <a href="http://www.flickr.com/photos/ervins_strauhmanis/" style="color: black;">Ervins Strauhmanis</a> available under a 
    	<a href="http://creativecommons.org/licenses/by/2.0/deed.en" style="color: black;"> Creative Commons Attribution 2.0 Generic </a></p>
	</div>	
	</body>
</html>