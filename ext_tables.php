<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
$TCA["tx_trackback_tb"] = array (
	"ctrl" => array (
		'title'     => 'LLL:EXT:trackback/locallang_db.xml:tx_trackback_tb',		
		'label'     => 'url',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => "ORDER BY crdate",	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_trackback_tb.gif',
	),
	"feInterface" => array (
		"fe_admin_fieldList" => "hidden, tpage, url, sref, visits, title, last_visit",
	)
);

$TCA["tx_trackback_ips"] = array (
	"ctrl" => array (
		'title'     => 'LLL:EXT:trackback/locallang_db.xml:tx_trackback_ips',		
		'label'     => 'sref',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => "ORDER BY crdate",	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_trackback_ips.gif',
	),
	"feInterface" => array (
		"fe_admin_fieldList" => "hidden, tpage, sref, ip, time",
	)
);


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key';


t3lib_extMgm::addPlugin(array('LLL:EXT:trackback/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');


t3lib_extMgm::addStaticFile($_EXTKEY,"pi1/static/","Trackback");


if (TYPO3_MODE=="BE")	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_trackback_pi1_wizicon"] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_trackback_pi1_wizicon.php';

t3lib_extMgm::addStaticFile($_EXTKEY,'static/Trackback/', 'Trackback');
?>