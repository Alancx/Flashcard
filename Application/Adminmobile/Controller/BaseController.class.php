<?php
namespace Adminmobile\Controller;
use Think\Controller;
class BaseController extends Controller{

	//基础数据库读取
	public function BM($tableName)
	{
	 return M($tableName,C('DB_BASE')['DB_PREFIX'],'DB_BASE');
	}

	//用户数据库读取
	public function UM($tableName)
	{
	 return M($tableName,C('DB_USER')['DB_PREFIX'],'DB_USER');
	}

	//仓库数据库读取
	public function WM($tableName)
	{
	 return M($tableName,C('DB_USER')['DB_PREFIX'],'DB_WAREHOUSE');
	}
	//仓库数据库读取没有读取前缀
	public function WNM($tableName)
	{
	 return M($tableName,'','DB_WAREHOUSE');
	}
}
?>
