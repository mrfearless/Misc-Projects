<?php
/**
 *
 * @package NWO Steam Functions
 * @version $Id: 1.0.0
 * @copyright (c) 2009 -[Nwo]- fearless
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

define('IN_PHPBB', true);


/*
// Steam ID Convertor
// http://forums.alliedmods.net/showthread.php?t=60899&page=19
//
// Example Usage

//Print friendId from steamId
echo(getFriendId('STEAM_0:1:6619100'); //will return 76561197973503929

//Print steamId from friendId
echo(getSteamId('76561197973503929', '0'); //will return STEAM_0:1:6619100

echo(getSteamId('76561197973503929', '1'); //will return STEAM_1:1:6619100

//Alternate way
$steamIds = getSteamIds('76561197973503929');
echo($steamIds[0]); //will return STEAM_0:1:6619100
echo($steamIds[1]); //will return STEAM_1:1:6619100
*/ 

function isSteamIdValid($steamId)
{
    if (substr($steamId, 0, 6) != "STEAM_")
	{
		$steamId = "STEAM_" . $steamId;
	}
	
	$re = '^STEAM_[0-1]:[0-1]:(\d+)^';
    if(preg_match($re, $steamId))
    {
        return(true);
    }
    else
    {
        return(false);
    }
}

function isFriendIdValid($friendId)
{
    if((substr($friendId, 0, 7) == "7656119") && (strlen($friendId) == 17))
    {
        return(true);
    }
    else
    {
        return(false);
    }
}

function getFriendId($steamId)
{
    //Test input steamId for invalid format
    if(!isSteamIdValid($steamId)){return('INVALID');}

    //Example SteamID: "STEAM_X:Y:ZZZZZZZZ"
    $gameType = 0; //This is X.  It's either 0 or 1 depending on which game you are playing (CSS, L4D, TF2, etc)
    $authServer = 0; //This is Y.  Some people have a 0, some people have a 1
    $clientId = ''; //This is ZZZZZZZZ.

    //Remove the "STEAM_"
    $steamId = str_replace('STEAM_', '' ,$steamId);

    //Split steamId into parts
    $parts = explode(':', $steamId);
    $gameType = $parts[0];
    $authServer = $parts[1];
    $clientId = $parts[2];

    //Calculate friendId
    $result = bcadd((bcadd('76561197960265728', $authServer)), (bcmul($clientId, '2')));
    return($result);
}

function getSteamIds($friendId)
{
    //Test input friendId for invalid format
    if(!isFriendIdValid($friendId)){return('INVALID');}

    //If the friendId is even, the authServer is 0
    //If the friendId is odd, the authServer is 1
    $authServer = "1";
    if(bcmod($friendId, "2") == "0")
    {
        $authServer = "0";
    }

    //Calculate clientId
    $clientId = bcdiv(bcsub(bcsub($friendId, '76561197960265728'), $authServer), '2');

    //Return steamId array
    $result = array();
    array_push($result, 'STEAM_0:'.$authServer.':'.$clientId);
    array_push($result, 'STEAM_1:'.$authServer.':'.$clientId);
    return($result);
}

function getSteamId($friendId, $gameType)
{
    //Test input friendId for invalid format
    if(!isFriendIdValid($friendId)){return('INVALID');}

    //If the friendId is even, the authServer is 0
    //If the friendId is odd, the authServer is 1
    $authServer = "1";
    if(bcmod($friendId, "2") == "0")
    {
        $authServer = "0";
    }

    //Calculate clientId
    $clientId = bcdiv(bcsub(bcsub($friendId, '76561197960265728'), $authServer), '2');

    //Return steamId array
    $result = 'STEAM_'.$gameType.':'.$authServer.':'.$clientId;
    return($result);
}

function getVacBannedStatus($steamId)
{
	$friendId = getFriendId($steamId);
	
	if (!isFriendIdValid($friendId)){return('INVALID');}
		
	$xml=simplexml_load_file('http://steamcommunity.com/profiles/'.$friendId.'?xml=1');
	$vacBanned = $xml->vacBanned;
	if ($vacBanned == 0)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function getSteamAvatar($steamId)
{
	$friendId = getFriendId($steamId);
	
	if (!isFriendIdValid($friendId))
	{
		return('INVALID');
	}
	$xml=simplexml_load_file('http://steamcommunity.com/profiles/'.$friendId.'?xml=1');
	$avatarIcon = $xml->avatarIcon;
	return $avatarIcon;
}

function getSteamAvatarIcon($steamId)
{
	$friendId = getFriendId($steamId);
	if (!isFriendIdValid($friendId))
	{
		return '';
	}	
	
	if (file_exists($phpbb_root_path . "images/avatars/steamprofiles/$friendId.jpg"))
	{
		//echo 'checking file';

		$checktime = time();
		$filetime = filemtime($phpbb_root_path . "images/avatars/steamprofiles/$friendId.jpg");
		//echo $checktime . ' ' . $filetime . '<br />';
		if ($checktime > ($filetime  + (360000)))
		{
			$xml=simplexml_load_file('http://steamcommunity.com/profiles/'.$friendId.'?xml=1');
			
			//$xml=loadXML2('steamcommunity.com','/profiles/'.$friendId.'?xml=1');
			if ($xml == false)
			{
				return '';
			}
			else
			{
				$avatarIcon = $xml->avatarIcon;
				$contents = file_get_contents($avatarIcon);
				$file = $phpbb_root_path . "images/avatars/steamprofiles/$friendId.jpg";
				$fp = fopen($file, 'w+b');
				fwrite($fp, $contents);
				fclose($fp);
				chmod($file,0777);
				// Touch the file with a random time
				$xtime = rand(1, 360000);
			
				$newtime = time() + xtime;
			
				touch($file,date('U', filemtime($file)),$newtime);
				//touch($file, $newtime);
			}
		}
	}
	else
	{
		//echo 'getting file';
		$xml=simplexml_load_file('http://steamcommunity.com/profiles/'.$friendId.'?xml=1');
		$avatarIcon = $xml->avatarIcon;
		//echo $avatarIcon;
		$contents = file_get_contents($avatarIcon);
		//$contents = imagecreatefromjpeg($avatarIcon);
		$file = $phpbb_root_path . "images/avatars/steamprofiles/$friendId.jpg";
		$fp = fopen($file, 'w+b');
		fwrite($fp, $contents);
		fclose($fp);
		chmod($file,0777);
		// Touch the file with a random time
		$xtime = rand(1, 360000);
		$newtime = time() + xtime;
		
		//touch($file, $newtime);
		touch($file,date('U', filemtime($file)),$newtime);
		//imagejpeg($contents, $file);
	}
	return "http://www.NewWorldOrder.org.uk/forum/images/avatars/steamprofiles/$friendId.jpg";
}


function getSteamOnlineState($steamId)
{
	$friendId = getFriendId($steamId);
	
	if (!isFriendIdValid($friendId))
	{
		return('INVALID');
	}
	$xml=simplexml_load_file('http://steamcommunity.com/profiles/'.$friendId.'?xml=1');
	$onlinestate = $xml->onlineState;
	return $onlinestate;
}

function getSteamArray($steamId)
{
	$friendId = getFriendId($steamId);
	
	if (!isFriendIdValid($friendId))
	{
		return('INVALID');
	}
	$xml=simplexml_load_file('http://steamcommunity.com/profiles/'.$friendId.'?xml=1');
	
	$steamname = $xml->steamID;
	$vacstatus = $xml->vacBanned;
	$avataricon = $xml->avatarIcon;
	$onlinestate = $xml->onlineState;
	$statemessage = $xml->stateMessage;
	$ingamename = $xml->inGameInfo->gameName;

	$steamarray = array();
	$steamarray = array(
		'SteamId'		=>	$steamId,
		'FriendId'		=>	$friendId,
		'SteamName'		=>	$steamname,
		'VacStatus'		=>	$vacstatus,
		'SteamAvatar'	=>	$avataricon,
		'OnlineStatus'	=>	$onlinestate,
		'StateMessage' 	=>  $statemessage,
		'IngameName'	=>	$ingamename,
	);
	
	return $steamarray;
}

function getSteamImage($steamId)
{
	$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';

	$sHeadFontFile = $phpbb_root_path . 'data/fonts/Verdanab.ttf'; //'DejaVuSans-BoldVerdana_Bold.ttf';
	$fHeadFontSize = 8;
	$sBodyFontFile = $phpbb_root_path . 'data/fonts/Verdana.ttf'; //'Verdana.ttf';
	$fBodyFontSize = 8;
	$bFontAA = true;	
	
	$steamarray = getSteamArray($steamId);
	$steamname = $steamarray['SteamName'];
	$friendid = $steamarray['FriendId'];
	$avataricon = $steamarray['SteamAvatar'];
	$onlinestate = $steamarray['OnlineStatus'];
	$statemessage = $steamarray['StateMessage'];
	$ingamename = $steamarray['IngameName'];

	// get the player's status for text and icon holder color
	switch($onlinestate)
	{
		case 'in-game':
			$sCurrentGame = $ingamename;
		
			$sStatus = 'ingame';
			$sContent = 'In-Game';
			$sContentExtra = $sCurrentGame;
			$aFontColor = array(139, 197, 63);
		break;
		
		case 'online':
			$sStatus = 'online';
			$sContent = 'Online';
			$aFontColor = array(142, 202, 254);
		break;
		
		case 'offline':
			$sStatus = 'offline';
			$sContent = 'Offline';
			$sContentExtra = $statemessage;
			$aFontColor = array(137, 137, 137);
		break;
		
		default:
			//throw new SteamProfileImageException('Unable to determinate player status.');
	}
	
	// some hard-coded variables
	$iTextBaseX = 50;
	$iTextBaseY = 14;
	$iTextPadding = 12;
	$iFontAA = ($bFontAA)? 1 : -1;	
	
	
	// load blank background
	$rImage = imagecreatefrompng($phpbb_root_path . 'images/steamprofilebackground.png');
	// enable alpha blending
	imagealphablending($rImage, true);
	imagesavealpha($rImage, true);
	
	
	$rIconHolderImage = imagecreatefromjpeg($phpbb_root_path . "images/iconholder_$onlinestate.jpg");
	imagecopy($rImage, $rIconHolderImage, 4, 4, 0, 0, 40, 40);
	imagedestroy($rIconHolderImage);
		
	// create avatar icon
	$rAvatarIconImage = imagecreatefromjpeg($avataricon);
	imagecopy($rImage, $rAvatarIconImage, 8, 8, 0, 0, 32, 32);
	imagedestroy($rAvatarIconImage);


	// create text
	$rFontColor = imagecolorallocate($rImage, $aFontColor[0], $aFontColor[1], $aFontColor[2]) * $iFontAA;
	
	imagefttext($rImage, $fHeadFontSize, 0, $iTextBaseX, $iTextBaseY, $rFontColor, $sHeadFontFile, $steamname);
	imagefttext($rImage, $fBodyFontSize, 0, $iTextBaseX, $iTextBaseY + $iTextPadding, $rFontColor, $sBodyFontFile, $sContent);
	
	if(isset($sContentExtra))
		imagettftext($rImage, $fBodyFontSize, 0, $iTextBaseX, $iTextBaseY + $iTextPadding * 2, $rFontColor, $sBodyFontFile, $sContentExtra);


	
	imagepng($rImage, $phpbb_root_path . "images/avatars/steamprofiles/$friendid.png");

	return "$friendid.png";
	
}

function loadXML2($domain, $path, $timeout = 30) {

    /*
        Usage:
       
        $xml = loadXML2("127.0.0.1", "/path/to/xml/server.php?code=do_something");
        if($xml) {
            // xml doc loaded
        } else {
            // failed. show friendly error message.
        }
    */

    $fp = fsockopen($domain, 80, $errno, $errstr, $timeout);
    if($fp) {
        // make request
        $out = "GET $path HTTP/1.1\r\n";
        $out .= "Host: $domain\r\n";
        $out .= "Connection: Close\r\n\r\n";
        fwrite($fp, $out);
       
        // get response
        $resp = "";
        while (!feof($fp)) {
            $resp .= fgets($fp, 128);
        }
        fclose($fp);
		
		
		
        // check status is 200
        $status_regex = "/HTTP\/1\.\d\s(\d+)/";
        if(preg_match($status_regex, $resp, $matches) && $matches[1] == 200) {   
            // load xml as object
            $parts = explode("\r\n\r\n", $resp);   
            $sxml = simplexml_load_string($parts[1]);
			if ($sxml == false)
			{
				
				return '';
			}	
			else	
			{
				return $sxml;
			}
        }
    }
    return false;
   
} 

?>