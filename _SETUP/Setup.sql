Use DOS_MITIGATION;

CREATE TABLE IF NOT EXISTS `block_list` (
  `IP` varchar(255) Not Null,
  `block_ts` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `host` varchar(255) Not Null
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `block_log` (
  `IP` varchar(255) Not Null,
  `Action` varchar(255) Not Null,
  `action_ts` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `host` varchar(255) Not Null
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
