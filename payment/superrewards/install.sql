CREATE TABLE `jag_accounts`.`store_superrewards_callback` (
`id` INT NOT NULL COMMENT 'a unique identifier for this transaction',
`new` INT NOT NULL COMMENT 'points user earned by completing offer',
`total` INT NOT NULL COMMENT 'total number of points accumulated by this user on your application',
`uid` INT NOT NULL COMMENT 'Id from our User',
`oid` INT NOT NULL COMMENT 'SuperRewards offer identifier',
`sig` VARCHAR( 255 ) NOT NULL COMMENT 'security hash used to verify the authenticity of the postback. md5($_REQUEST[''id''] . '':'' . $_REQUEST[''new''] . '':'' . $_REQUEST[''uid''] . '':'' . $SECRET);',
PRIMARY KEY ( `id` ) ,
INDEX ( `uid` )
);

ALTER TABLE `store_superrewards_callback` ADD `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP 