-- 
-- Editor SQL for DB table prices
-- Created by http://editor.datatables.net/generator
-- 

CREATE TABLE IF NOT EXISTS `prices` (
	`ID` int(10) NOT NULL auto_increment,
	`perkm` numeric(9,2),
	`permin` numeric(9,2),
	`starttime` time,
	`endtime` time,
	PRIMARY KEY( `ID` )
);