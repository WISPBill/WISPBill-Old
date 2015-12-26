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
// start of input error check
    $exitcode = $_SESSION['exitcode'];
    if($exitcode == 'pass match fail'){
        echo'<h1> Passwords did not match</h1>';
    } elseif ($exitcode == 'user empty'){
        echo'<h1> No Username has been Submitted</h1>';
    } elseif ($exitcode == 'password empty'){
        echo'<h1> No Password has been Submitted</h1>';
    } elseif ($exitcode == 'email empty'){
        echo'<h1> No Email has been Submitted</h1>';
    } elseif ($exitcode == 'email match fail'){
        echo'<h1> Emails did not match</h1>';
    } elseif ($exitcode == 'email not valid'){
        echo'<h1> Email was not valid</h1>';
    } elseif ($exitcode == 'email already'){
        echo'<h1> The Email you submitted already exists</h1>';
    } elseif ($exitcode == 'username already'){
        echo'<h1> The Username you submitted already exists</h1>';
    }else{
        // do nothing
    }
	$error = $_SESSION['exitcodev2'];
echo "<h1>$error</h1>";
$_SESSION['exitcodev2'] ='';
// end of input error check

// start of user input feilds
echo'<form action="createadminuser2.php"method="post" enctype="multipart/form-data"
  	    <fieldset>
		  <br><label>First Name</label>
  	            <input type="text" 
  	             name="fname"/></br>
                     
                     <br><label>Last Name</label>
  	            <input type="text" 
  	             name="lname"/></br>
				 
				  <br><label>Home Telephone Number</label>
  	            <input type="tel" 
  	             name="tel"/></br>
				 
				  <br><label>Cell Number</label>
  	            <input type="tel" 
  	             name="ctel"/></br>
				 
  	            <br><label>Username</label>
  	            <input type="text" 
  	             name="username"/></br>
                     
                     <br><label>Email</label>
  	            <input type="text" 
  	             name="email"/></br>
                     
                     <br><label>Confirm Email</label>
  	            <input type="text" 
  	             name="email2"/></br>
                     
                     <br><label>Password</label>
  	            <input type="password" 
  	            name="password"/></br>
                    
                    <br><label>Confirm Password</label>
  	            <input type="password" 
  	            name="password2"/></br>
				
				<br><label>User Image must be 160x160</label>
  	             <input type="file" name="fileToUpload" id="fileToUpload"></br>
                    
  	            <br><input type="submit" value="Submit" name="submit"></br>   
  	    </fieldset>
  	    </form>'
// end of user input feilds 
?>