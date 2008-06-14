#
# Table structure for table 'tx_trackback_tb'
#
CREATE TABLE tx_trackback_tb (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	tpage tinytext NOT NULL,
	url tinytext NOT NULL,
	sref tinytext NOT NULL,
	visits int(11) DEFAULT '0' NOT NULL,
	title tinytext NOT NULL,
	last_visit tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_trackback_ips'
#
CREATE TABLE tx_trackback_ips (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	tpage tinytext NOT NULL,
	sref tinytext NOT NULL,
	ip tinytext NOT NULL,
	time tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);