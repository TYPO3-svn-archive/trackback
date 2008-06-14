<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Sebastian Gebhard <sg@webagentur-gebhard.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'Trackback' for the 'trackback' extension.
 *
 * @author	Sebastian Gebhard <sg@webagentur-gebhard.de>
 * @package	TYPO3
 * @subpackage	tx_trackback
 */
class tx_trackback_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_trackback_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_trackback_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'trackback';	// The extension key.
	var $pi_checkCHash = true;
	var $excludevars;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->fix_storage();
		
        $GLOBALS['TSFE']->additionalHeaderData[$this->prefixId] .=
			'<link href="typo3conf/ext/trackback/pi1/style.css" type="text/css" rel="stylesheet" />';
		// Referer
		$ref = $_SERVER['HTTP_REFERER'];
		// This Script Adress
		$this->passvars = array(
			'tx_ttnews' => array('tt_news')
		);
		$ad = $this->make_ad();
		// Checks if referer is valid
		if($this->is_ref($ref)){
			$ip = $_SERVER['REMOTE_ADDR'];
			$this->handle_ref($ad, $ref, $ip);
		}
		$tb = $this->get_tb($ad);
		$eo = 'odd';
		foreach($tb as $t){
			if($eo=='odd'){$eo='even';}
			else{$eo='odd';}
			$t['url'] = htmlentities($t['url']);
			if($t['title']){$title=$t['title'];}
			else{$title=$t['url'];}
			$timestring1 = $this->timestring($t["crdate"]);
			$timestring2 = $this->timestring($t["last_visit"]);
			if($t['visits']>1){
			    $times = $this->pi_getLL('tb_item_time1').' <span class="timestring">'.$timestring1.'</span> '.$this->pi_getLL('tb_item_time2').' <span class="timestring">'.$timestring2.'</span> '.$this->pi_getLL('tb_item_time3');
			}else{
			    $times = '<span class="timestring">'.$timestring1.'</span>';
			}
			$content .= '
<div class="tb-item '.$eo.'" onclick="location.href=\''.$t['url'].'\';">
	<a href="'.$t['url'].'">'.$this->shorten($title,65,20).'</a>
	<div class="tb-item-visits">('.$t['visits'].' Besucher)</div>
	<div class="tb-item-meta">
		<span class="tb-item-time">'.$times.'</span>
	</div>
</div>
';
		}
		$GLOBALS['TSFE']->additionalHeaderData[$this->prefixId] .= '<style type="text/css">
    .tx-trackback-pi1{
        height: '.((count($tb))*15).'px;
	}
</style>';
		
		return $this->pi_wrapInBaseClass($content);
	}
	
	function fix_storage(){
	    $update = array('pid' => $this->conf['storagepid']);
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_trackback_tb', '', $update);
		$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_trackback_ips', '', $update);
	}

	function get_tb($ad){
		$array = array();
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_trackback_tb', 'tpage = \''.$ad.'\' AND hidden = 0 AND deleted = 0 AND visits >= '.$this->conf['minvisits'], '', 'visits DESC, last_visit DESC', $this->conf['limit']);
		while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)){
			$array[] = $row;
		}
		return $array;
	}
	
	function handle_ref($ad, $ref, $ip){
		$sref = $this->simplify($ref);
		$sql = 'SELECT uid FROM tx_trackback_ips WHERE
			ip = \''.$ip.'\'
			AND deleted = 0
			AND hidden = 0
			AND time < \''.(time()+2*24*60*60).'\'
			AND tpage = \''.$ad.'\'
			AND sref = \''.$sref.'\'';
		$res = mysql_query($sql);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		if(!$row['uid']){
			// IP was not counted yet.. so let's go!
			
			// Insert  IP
			$insert = array('tpage' => $ad, 'ip' => $ip, 'time' => time(), 'sref' => $sref);
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_trackback_ips', $insert);
			
			// Check if tpage<->url is already exists
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('uid, visits', 'tx_trackback_tb','tpage = \''.$ad.'\' AND sref = \''.$sref.'\' AND deleted = 0 AND hidden = 0');
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			if($row['uid']){
				$update = array('visits' => ($row['visits']+1), 'last_visit' => time());
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_trackback_tb', 'uid='.$row['uid'], $update);
			}else{
				// Get Remote Title
				$handle = fopen($ref, "r");
				$contents = fread($handle, 2048);
				fclose($handle);
				if($contents){
					if (eregi ("<title>(.*)</title>", $contents, $treffer)) {
						$title = $treffer[1];
					}
				}
				$insert = array('crdate' => time(), 'tstamp' => time(), 'tpage' => $ad, 'url' => $ref, 'sref' => $sref, 'visits' => 1, 'last_visit' => time(), 'title' => $title);
				$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_trackback_tb', $insert);
			}
		}
	}
	
	function make_ad(){
	    $g = $_GET;
	    $params = array();
	    foreach($this->passvars as $ext => $vars){
	        if(isset($g[$ext])){
				foreach($vars as $var){
					if(isset($g[$ext][$var])){
						$params[] = $ext.'['.$var.']='.$g[$ext][$var];
					}
				}
			}
		}
		$params = join('&',$params);
		return 'index.php?id='.$GLOBALS['TSFE']->id.'&'.$params;
	}
	
	function is_ref($ref){
		if(!trim($ref)){return false;}
		if($this->conf['excludese']){
			$ses = array('google.de', 'google.com', 'live.com', 'yahoo.com');
			foreach($ses as $s){
				if(!(stripos($ref,$s)===FALSE)){
					return false;
				}
			}
		}
		$ref = $this->simplify($ref);
		$host = $this->simplify($_SERVER['HTTP_HOST']);
		if(substr($ref,0,strlen($host))==$host){
			return false;
		}
		return true;
	}
	
	function shorten($string, $max_lenght, $end){
		$string = trim($string);
		if(strlen($string)>$max_lenght){
		    $string = substr($string,0,($max_lenght-($end+3)))
						.'...'.
						substr($string,(-($end)),$end);
		}
		return $string;
	}
	
	function simplify($url){
		$expr = array('http://', 'https://', 'www.');
		foreach($expr as $e){
			$url = str_replace($e, '', $url);
		}
		$url = explode('?', $url);
		return $url[0];
	}
	
	function timestring($stamp){
	    $time = date("H:i:s",$stamp);
		switch(date('D', $stamp)){
		    case 'Mon': $day = 'Montag';break;
			case 'Tue': $day = 'Dienstag';break;
			case 'Wed': $day = 'Mittwoch';break;
			case 'Thu': $day = 'Donnerstag';break;
			case 'Fri': $day = 'Freitag';break;
			case 'Sat': $day = 'Samstag';break;
			case 'Sun': $day = 'Sonntag';break;
		}
		// Mitternacht
		$m1 = gmmktime(0,0, 0, date('n'), date('d'), date('Y'));
		$m2 = $m1 - 60*60*24;
		$m3 = $m1 - 60*60*24*7;
		if($stamp>$m2){$day = 'Gestern';}
		if($stamp>$m1){$day = 'Heute';}
		if($stamp<$m3){$day = date('j.n.y', $stamp);}
		return "$day, $time";
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/trackback/pi1/class.tx_trackback_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/trackback/pi1/class.tx_trackback_pi1.php']);
}

?>