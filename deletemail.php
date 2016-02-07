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
if($emailreader == true){
    //Mail in on
}else{
    //Mail is off
    header('Location: index.php');
}
$folder = $_POST['folder'];

             if(isset($_POST['id'])){
            $uid = $_POST['id'];
        } else{
            //no folder we want to view
          header('Location: mailbox.php');
        }
   include_once __DIR__.'/webmail/libraries/afterlogic/api.php';

    if (class_exists('CApi') && CApi::IsValid())
    {
        // data for logging into account
        if(isset($semail)){
            // do nothing 
        }else{
            if ($result = $mysqli->query("SELECT * FROM `admin_users` WHERE `idadmin` = '$adminid'")) {
            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
            $semail = $row["email"];
             }
    
            /* free result set */
            $result->close();
            }
            $iv = $_SESSION['iv'];
            $bytes = $_SESSION['key'];
            $epassword = $_SESSION['emailpass'];
            $sPassword = mcrypt_decrypt (MCRYPT_BLOWFISH,"$bytes", "$epassword","ofb","$iv");
        }
        
        try
        {
            $oApiIntegratorManager = CApi::Manager('integrator');

            $oAccount = $oApiIntegratorManager->LoginToAccount($semail, $sPassword);
            if ($oAccount)
            {
                 $oApiMailManager = CApi::Manager('mail');
                 $oApiMailManager->moveMessage($oAccount,$folder,$delfolder,$uid);
                
			}
			else
			{
				// login error
				echo $oApiIntegratorManager->GetLastErrorMessage();
			}
		}
		catch (Exception $oException)
		{
			// login error
			echo $oException->getMessage();
		}
	}
	else
	{
		echo 'AfterLogic API isn\'t available';
        exit;
	}


header('Location: mailbox.php');
?>