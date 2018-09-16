#
# $Id: mysql_41_schema.sql -[Nwo]- fearless
#

ALTER TABLE phpbb_users
	ADD user_afkstatus TINYINT(1) NOT NULL default '0',
	ADD user_afkdate DATETIME,
	ADD user_afkpmmsg TEXT,
	ADD user_afktopicid MEDIUMINT(8) NOT NULL default '0',
	ADD user_afkreason VARCHAR(128) NOT NULL,
	ADD user_afkreasoncat TINYINT(2) NOT NULL default '0';


