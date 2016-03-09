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
$_SESSION['exitcode'] = '';
$_SESSION['exitcodev2'] = '';
$_SESSION['errorcode'] = '';
$_SESSION['id'] = '';
$_SESSION['id2'] = '';
$_SESSION['id3'] = '';
$_SESSION['email'] = '';
$_SESSION['errorcode'] ='';
echo'<br><a href="dashbored.php">Back</a></br>';
echo'<br><a href="createadminuser.php">Create Admin User</a></br>';
echo'<br><a href="setskin.php">Set Skin</a></br>';
echo'<br><a href="deleteadminuser.php">Delete Admin User</a></br>';
echo'<br><a href="logout.php">Log Out</a></br>'; // In Dash

?>