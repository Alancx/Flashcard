<?php
return array(
	'DEFAULT_CONTROLLER'=>'Public',
	'DEFAULT_ACTION'=>'login',
	'URL_MODEL'=>2,
	//'配置项'=>'配置值'
	'SESSION_PREFIX'=>'Sellermobile',
	'SESSION_OPTIONS' => array(
		'name' =>'mobile',
		'expire' =>3*24*3600,
		// 'use_trans_sid'=>1,
		// 'use_only_cookies'=>0,
	),
	//微信参数
	"WXAPPID"=>'wx3dd28eb27ded279b',
	"WXAPPSECRET"=>'9b7c0e147107c71f367ebb92573f18d5',

	////////微信通知消息//////////////
	"TXNOTICE"=>array(
		'Sellermobile/Payment/TXNotify',
	),

	'STATICPATH'=>'/HTML/',
	//平台用户
	'DB_BASE' => array(
		'DB_TYPE'=>'sqlsrv',
		'DB_HOST'=>'localhost',
		'DB_NAME'=>'FlashCard',
		'DB_PORT'=>'1433',
		'DB_USER'=>'sa',
		'DB_PWD'=>'Cc123',
		'DB_PREFIX'=>'RS_',
		'DB_CHARSET'=>'utf8',
	),

	//用户（商户）
	'DB_USER' => array(
		'DB_TYPE'  => 'mysql',
		'DB_HOST'  => 'localhost',
		'DB_PORT'  => '3306',
		'DB_USER'  => 'root',
		'DB_PWD'   => 'root',
		'DB_NAME'  => 'flashcard',
		'DB_PREFIX'=>'tb_',
		'DB_CHARSET'=> 'utf8',
	),

	//平台仓库
	// 'DB_WAREHOUSE' => array(
	// 	'DB_TYPE'=>'sqlsrv',
	// 	'DB_HOST'=>'localhost',
	// 	'DB_NAME'=>'HmallWh',
	// 	'DB_PORT'=>'1433',
	// 	'DB_USER'=>'sa',
	// 	'DB_PWD'=>'Cc123',
	// 	'DB_PREFIX'=>'tb_',
	// 	'DB_CHARSET'=>'utf8',
	// ),
);
