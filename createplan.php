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
    if($exitcode == 'no name'){
        echo'<h1>No Name was Submitted</h1>';
    } elseif ($exitcode == 'no price'){
        echo'<h1> No Price has been Submitted</h1>';
    } elseif ($exitcode == 'no up'){
        echo'<h1> No Upload has been Submitted</h1>';
    } elseif ($exitcode == 'no down'){
        echo'<h1> No Download has been Submitted</h1>';
    } elseif ($exitcode == 'name already'){
        echo'<h1>The Name you submitted already exists</h1>';
    }else{
        // do nothing
    }
// end of input error check

// start of user input feilds
echo'<form action="createplan2.php"method="post">
  	    <fieldset>
  	            <br><label>Plan Name</label>
  	            <input type="text" 
  	             name="name"/></br>
                     
                     <br><label>Price</label>
  	            <input type="number" name="price" min="0">
		    </br>
                     
                     <br><label>Max upload bandwidth in kilobits per second</label>
  	            <input type="number" name="up" min="0"></br>
                     
                     <br><label>Max download bandwidth in kilobits per second</label>
  	            <input type="number" name="down" min="0"></br>

  	            <br><button type="submit">
  	                Create Plan
  	            </button></br>   
  	    </fieldset>
  	    </form>'
// end of user input feilds 
?>