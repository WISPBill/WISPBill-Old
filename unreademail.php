<?php
$mysqli = new mysqli("$ip", "$username", "$password", "$db");

if(isset($_SESSION['adminid'])){
$adminid = $_SESSION['adminid'];
if(isset($_SESSION['emailtime'])){
	$oldtime = $_SESSION['emailtime'];
	$newtime = time();
	$timesince = $newtime - $oldtime;
	if($timesince > 600){
		// Run
		if ($result = $mysqli->query("SELECT * FROM `admin_users` WHERE `idadmin` = '$adminid'")) {
      /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        $semail = $row["email"];
    }
    
    /* free result set */
    $result->close();
}
session_regenerate_id();
$iv = $_SESSION['iv'];
$bytes = $_SESSION['key'];
$epassword = $_SESSION['emailpass'];
$_SESSION['emailtime'] = time();
include_once __DIR__.'/webmail/libraries/afterlogic/api.php';
	if (class_exists('CApi') && CApi::IsValid())
	{
		$sFolder = 'INBOX';
        $sPassword = mcrypt_decrypt (MCRYPT_BLOWFISH,"$bytes", "$epassword","ofb","$iv");
		try
		{
			$oApiIntegratorManager = CApi::Manager('integrator');
			$oAccount = $oApiIntegratorManager->LoginToAccount($semail, $sPassword);
			if ($oAccount)
			{
				$oApiMailManager = CApi::Manager('mail');
				$aData = $oApiMailManager->getFolderInformation($oAccount, $sFolder);

				if (is_array($aData) && 4 === count($aData))
				{					
					$mailevents = $aData[1];
					$_SESSION['mailevents'] = $mailevents;
				}
			}
			else
			{
				echo $oApiIntegratorManager->GetLastErrorMessage();
			}
		}
		catch (Exception $oException)
		{
			echo $oException->getMessage();
		}
	}
	else
	{
		echo 'WebMail API isn\'t available';
	}
	} else {
		// Dont run
		$mailevents = $_SESSION['mailevents'];
	}
}else{
if ($result = $mysqli->query("SELECT * FROM `admin_users` WHERE `idadmin` = '$adminid'")) {
      /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        $semail = $row["email"];
    }
    
    /* free result set */
    $result->close();
}
session_regenerate_id();
$iv = $_SESSION['iv'];
$bytes = $_SESSION['key'];
$epassword = $_SESSION['emailpass'];
$_SESSION['emailtime'] = time();
include_once __DIR__.'/webmail/libraries/afterlogic/api.php';
	if (class_exists('CApi') && CApi::IsValid())
	{
		$sFolder = 'INBOX';
        $sPassword = mcrypt_decrypt (MCRYPT_BLOWFISH,"$bytes", "$epassword","ofb","$iv");
		try
		{
			$oApiIntegratorManager = CApi::Manager('integrator');
			$oAccount = $oApiIntegratorManager->LoginToAccount($semail, $sPassword);
			if ($oAccount)
			{
				$oApiMailManager = CApi::Manager('mail');
				$aData = $oApiMailManager->getFolderInformation($oAccount, $sFolder);

				if (is_array($aData) && 4 === count($aData))
				{					
					$mailevents = $aData[1];
					$_SESSION['mailevents'] = $mailevents;
				}
			}
			else
			{
				echo $oApiIntegratorManager->GetLastErrorMessage();
			}
		}
		catch (Exception $oException)
		{
			echo $oException->getMessage();
		}
	}
	else
	{
		echo 'WebMail API isn\'t available';
	}
}
}else {
    //nothing
}
?>