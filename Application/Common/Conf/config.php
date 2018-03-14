<?php
return array(
	//'配置项'=>'配置值'

    /* 默认设定 */
    'APP_GROUP_LIST' => 'Home',
    'DEFAULT_MODULE'        =>  'Home',  // 默认模块
    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'Index', // 默认操作名称
    'URL_MODEL'             =>  1,
    'TMPL_LOAD_DEFAULTTHEME'=>true,      // 差异模板开启
    'GOODS_INFO_PATH'=>'/HTML/',
    'LOAD_EXT_CONFIG'=>array(
        'config_site',                      // 站点常用配置
        'config_database',                  // 数据库参数
		'config_wxparam',                   // 微信参数
		'config_smsparam',                  // 短信参数
    'config_express',                   //  阿里云快递查询
    ),
    'RESOURCE_URL'=>'https://hmallresource.oss-cn-shanghai.aliyuncs.com',
    'TX_merchant'=>'TX0001455',
    'TX_key'=>'d80efe63192b4b7ebf9a30d78075b8fa',
    'WXCUT'=>0.6,
    'NOT_GET_WXMCA'=>array(
      'Home/Payment/WXPayNotify',
    )
);
