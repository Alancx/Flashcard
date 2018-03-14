<?php
/**
 * 站点常用配置
 */
return array (
	//平台用户
	'DB_BASE' => array(
		'DB_TYPE'=>'sqlsrv',
		'DB_HOST'=>'localhost',
		'DB_NAME'=>'Hmall',
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
		'DB_NAME'  => 'Hmall',
		'DB_PREFIX'=>'tb_',
		'DB_CHARSET'=> 'utf8',
	),

	//平台仓库
	'DB_WAREHOUSE' => array(
		'DB_TYPE'=>'sqlsrv',
		'DB_HOST'=>'localhost',
		'DB_NAME'=>'HmallWh',
		'DB_PORT'=>'1433',
		'DB_USER'=>'sa',
		'DB_PWD'=>'Cc123',
		'DB_PREFIX'=>'tb_',
		'DB_CHARSET'=>'utf8',
	),

  // 团购使用数据库
	'DB_GROUP' => array(
		'DB_TYPE'=>'sqlsrv',
		'DB_HOST'=>'localhost',
		'DB_NAME'=>'MMshop',
		'DB_PORT'=>'1433',
		'DB_USER'=>'sa',
		'DB_PWD'=>'Cc123',
		'DB_PREFIX'=>'RS_',
		'DB_CHARSET'=>'utf8',
	),

);
