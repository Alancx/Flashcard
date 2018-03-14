<?php
return array(
	'DEFAULT_THEME'=>'default',
	//'配置项'=>'配置值'
	'SESSION_OPTIONS'=>
	array(
    	'prefix'=>'HOMEUSER',
    	'expire'=>7200,
	),

	'DATA_CACHE_TYPE'=>'File',
	'DATA_CACHE_PREFIX'=>'WEBHOME',
	'DATA_CACHE_TIME'=>7200,
	//数据库配置项
	'DB_TYPE'=>'sqlsrv',
	'DB_HOST'=>'localhost',
	'DB_NAME'=>'FlashCard',
	'DB_PORT'  => '1433',
	'DB_USER'=>'sa',
	'DB_PWD'=>'Cc123',
	'DB_PREFIX'=>'RS_',
	'DB_CHARSET'=>'utf8',
);
