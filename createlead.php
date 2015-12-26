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

$error = $_SESSION['exitcodev2'];
echo "<h1>$error</h1>";
$_SESSION['exitcodev2'] ='';

// start of user input feilds
echo'<form action="createlead2.php"method="post">
  	    <fieldset>
  	            <br><label>First Name</label>
  	            <input type="text" 
  	             name="fname"required/></br>
                     
                     <br><label>Last Name</label>
  	            <input type="text" 
  	             name="lname"required/></br>
                 
                     <br><label>Email</label>
  	            <input type="text" 
  	             name="email"required/></br>
                     
                     <br><label>Confirm Email</label>
  	            <input type="text" 
  	             name="email2"required/></br>
                     
                     <br><label>Telephone Number</label>
  	            <input type="tel" 
  	             name="tel"required/></br>
                     
                     <br><label>Street Address</label>
  	            <input type="text" 
  	             name="add"required/></br>
                     
                     <br><label>City</label>
  	            <input type="text" 
  	            name="city" required/></br>
                    
                    <br><label>Zip Code</label>
  	            <input type="number" name="zip" min="0" required></br>
                    
                    <br><label>State</label>
  	            <input type="text" 
  	             name="state" value=';
                 echo "$state '/></br>
                 
                  <br><label>Source</label>
                  <select name='source'  required>
                <option value='' selected disabled>Please Select an Option</option>
                <option value='tel'>Phone Call</option>
                <option value='friend'>Referred by a Friend</option>
                <option value='d2d'>Door to Door</option>
                <option value='email'>Email</option>
                <option value='booth'>Show Booth</option>
                <option value='other'>Other</option>
                </select> </br>
                
  	            <br><button type='submit'>
  	                Submit Info
  	            </button></br>   
  	    </fieldset>
  	    </form>";
// end of user input feilds 
?>