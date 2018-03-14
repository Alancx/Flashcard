<?php
return array(
	'DEFAULT_CONTROLLER'=>'Public',
	'DEFAULT_ACTION'=>'GetTime',
	'URL_MODEL'=>2,
	//'配置项'=>'配置值'
	'SESSION_PREFIX'=>'SellermobileApp',
	'SESSION_OPTIONS' => array(
		'name' =>'selermobileapp',
		'expire' =>3*24*3600,
	),
	//微信参数
	"WXAPPID"=>'wxed2f2ef5e18e5423',
	"WXAPPSECRET"=>'b75711e17f2bcd266923254e85cb4206',

'config_smsparam',                  // 短信参数
	// ////////通知消息//////////////
	// "TGTIMEDIF"=>array(
	// 	'SellermobileApp/Payment/TXNotify',
	// 	'SellermobileApp/Public/UserInfo',
	// ),
	//////方法跳过验证///////////////
	"TGFUNCTION"=>array(
		'gettime',
		'getshopcode',
	),

	'STATICPATH'=>'/HTML/',
	//平台用户
	'DB_BASE' => array(
		'DB_TYPE'=>'sqlsrv',
		'DB_HOST'=>'localhost',
		'DB_NAME'=>'Hmall2',
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
		'DB_NAME'  => 'Hmall2',
		'DB_PREFIX'=>'tb_',
		'DB_CHARSET'=> 'utf8',
	),

	//平台仓库
	'DB_WAREHOUSE' => array(
		'DB_TYPE'=>'sqlsrv',
		'DB_HOST'=>'localhost',
		'DB_NAME'=>'HmallWh2',
		'DB_PORT'=>'1433',
		'DB_USER'=>'sa',
		'DB_PWD'=>'Cc123',
		'DB_PREFIX'=>'tb_',
		'DB_CHARSET'=>'utf8',
	),
);
