<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA["tx_trackback_tb"] = array (
	"ctrl" => $TCA["tx_trackback_tb"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "hidden,tpage,url,sref,visits,title,last_visit"
	),
	"feInterface" => $TCA["tx_trackback_tb"]["feInterface"],
	"columns" => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"tpage" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:trackback/locallang_db.xml:tx_trackback_tb.tpage",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"url" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:trackback/locallang_db.xml:tx_trackback_tb.url",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"sref" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:trackback/locallang_db.xml:tx_trackback_tb.sref",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"visits" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:trackback/locallang_db.xml:tx_trackback_tb.visits",		
			"config" => Array (
				"type"     => "input",
				"size"     => "4",
				"max"      => "4",
				"eval"     => "int",
				"checkbox" => "0",
				"range"    => Array (
					"upper" => "1000",
					"lower" => "10"
				),
				"default" => 0
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:trackback/locallang_db.xml:tx_trackback_tb.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"last_visit" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:trackback/locallang_db.xml:tx_trackback_tb.last_visit",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "hidden;;1;;1-1-1, tpage, url, sref, visits, title;;;;2-2-2, last_visit;;;;3-3-3")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);



$TCA["tx_trackback_ips"] = array (
	"ctrl" => $TCA["tx_trackback_ips"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "hidden,tpage,sref,ip,time"
	),
	"feInterface" => $TCA["tx_trackback_ips"]["feInterface"],
	"columns" => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"tpage" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:trackback/locallang_db.xml:tx_trackback_ips.tpage",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"sref" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:trackback/locallang_db.xml:tx_trackback_ips.sref",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"ip" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:trackback/locallang_db.xml:tx_trackback_ips.ip",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"time" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:trackback/locallang_db.xml:tx_trackback_ips.time",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "hidden;;1;;1-1-1, tpage, sref, ip, time")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);
?>