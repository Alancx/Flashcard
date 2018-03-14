<?php
namespace Home\Controller;
use Think\Controller;
class UMWareHouseController extends BaseController 
{
    public function _initialize()
    {
      parent::_initialize();
    }

    public function Index()
    {
    	

    	$this->display();
    }

    public function inWarehouse()
    {

    	$uinfo=$this->UM('user')->where(array('stoken'=>$this->webParam['stoken'],'token'=>$this->webParam['token']))->select();
    	$cinfo=$this->BM('productclass')->where(array('token'=>$this->webParam['token'],'ClassGrade'=>2))->select();
    	$sinfo=$this->BM('store')->where(array('token'=>$this->webParam['token'],'stoken'=>$this->webParam['stoken']))->select();


    	$this->assign('uinfo',$uinfo);
    	$this->assign('cinfo',$cinfo);
    	$this->assign('sinfo',$sinfo);

    	$this->assign('Title','商品入库单');
    	$this->display();
    }

    public function addInWarehouse()
    {
        $data=$_POST;

        $this->BM()->startTrans();
        $this->WM()->startTrans();

        $nowDate=$this->nowTimeParam['datetime'];

        $dataInWarehouse['InWarehouseId']='RK'.date('ymdHis',time()).time();
        $dataInWarehouse['InWarehouseNumber']=$data['mt']['id'];
        $dataInWarehouse['Count']=0;
        $dataInWarehouse['Money']=0;
        $dataInWarehouse['Status']=1;
        $dataInWarehouse['Date']=$data['mt']['idate'];
        $dataInWarehouse['InputId']=$data['mt']['ipid'];
        $dataInWarehouse['InputName']=$data['mt']['ipname'];
        $dataInWarehouse['HandleId']='';
        $dataInWarehouse['HandleName']='';
        $dataInWarehouse['Type']=$data['mt']['itype'];
        $dataInWarehouse['Remarks']=$data['mt']['remarks'];
        $dataInWarehouse['InStorehouseId']=$data['mt']['whid'];
        $dataInWarehouse['InStorehouseName']=$data['mt']['whname'];
        $dataInWarehouse['CreateDate']=$nowDate;
        $dataInWarehouse['LastUpdateDate']=$nowDate;
        $dataInWarehouse['token']=$this->webParam['token'];


        $res=false;

        foreach ($data['st'] as $key => $value) {
            $dataInWarehouseList['InWarehouseId']=$dataInWarehouse['InWarehouseId'];
            $dataInWarehouseList['ProId']=explode('_', $key)[0];
            $dataInWarehouseList['ProIdCard']=$key;
            $dataInWarehouseList['ClassId']=$value['cid'];
            $dataInWarehouseList['Price']=$value['price'];
            $dataInWarehouseList['Count']=$value['nums'];
            $dataInWarehouseList['Money']=$value['price']*$value['nums'];

            $dataInWarehouse['Count']+=$value['nums'];
            $dataInWarehouse['Money']+=$dataInWarehouseList['Money'];

            $dataInWarehouseList['IsMark']=0;
            $dataInWarehouseList['Remarks']="";
            $dataInWarehouseList['Supplier']="";
            $dataInWarehouseList['CreateDate']=$nowDate;
            $dataInWarehouseList['LastUpdateDate']=$nowDate;
            $dataInWarehouseList['token']=$this->webParam['token'];

            $res=$this->BM('productinwarehouselist')->add($dataInWarehouseList);

            if ($res) 
            {
                
                $res=$this->WM()->query("UPDATE [tb_".$data['mt']['whid']."] SET [StockCount]=StockCount+".$value['nums'].",[VirtualCount]=VirtualCount+".$value['nums']." WHERE [ProIdCard] = '$key'");

                if ($res===false) {
                    $this->BM()->rollback();
                    $this->WM()->rollback();
                    $this->ajaxReturn(array('status'=>false,'info'=>'0'),'JSON');
                }
            }
            else
            {
                $this->BM()->rollback();
                $this->WM()->rollback();
                $this->ajaxReturn(array('status'=>false,'info'=>'1'),'JSON');
            }
        }

        $res=$this->BM('productinwarehouse')->add($dataInWarehouse);

        if ($res) 
        {
            $this->BM()->commit();
            $this->WM()->commit();
            $this->ajaxReturn(array('status'=>true,'info'=>'success'),'JSON');
        }
        else
        {
            $this->BM()->rollback();
            $this->WM()->rollback();
            $this->ajaxReturn(array('status'=>false,'info'=>'2'),'JSON');
        }

        
    }


    public function outWarehouse()
    {

        $uinfo=$this->UM('user')->where(array('stoken'=>$this->webParam['stoken'],'token'=>$this->webParam['token']))->select();
        $cinfo=$this->BM('productclass')->where(array('token'=>$this->webParam['token'],'ClassGrade'=>2))->select();
        $sinfo=$this->BM('store')->where(array('token'=>$this->webParam['token'],'stoken'=>$this->webParam['stoken']))->select();


        $this->assign('uinfo',$uinfo);
        $this->assign('cinfo',$cinfo);
        $this->assign('sinfo',$sinfo);

        $this->assign('Title','商品出库单');
        $this->display();
    }


    public function addOutWarehouse()
    {
        $data=$_POST;

        $this->BM()->startTrans();
        $this->WM()->startTrans();

        $nowDate=$this->nowTimeParam['datetime'];

        $dataOutWarehouse['OutWarehouseId']='RK'.date('ymdHis',time()).time();
        $dataOutWarehouse['OutWarehouseNumber']=$data['mt']['id'];
        $dataOutWarehouse['Count']=0;
        $dataOutWarehouse['Money']=0;
        $dataOutWarehouse['Status']=1;
        $dataOutWarehouse['Date']=$data['mt']['idate'];
        $dataOutWarehouse['OutputId']=$data['mt']['ipid'];
        $dataOutWarehouse['OutputName']=$data['mt']['ipname'];
        $dataOutWarehouse['HandleId']='';
        $dataOutWarehouse['HandleName']='';
        $dataOutWarehouse['Type']=$data['mt']['itype'];
        $dataOutWarehouse['Remarks']=$data['mt']['remarks'];
        $dataOutWarehouse['OutStorehouseId']=$data['mt']['whid'];
        $dataOutWarehouse['OutStorehouseName']=$data['mt']['whname'];
        $dataOutWarehouse['CreateDate']=$nowDate;
        $dataOutWarehouse['LastUpdateDate']=$nowDate;
        $dataOutWarehouse['token']=$this->webParam['token'];


        $res=false;

        foreach ($data['st'] as $key => $value) {
            $dataOutWarehouseList['OutWarehouseId']=$dataOutWarehouse['OutWarehouseId'];
            $dataOutWarehouseList['ProId']=explode('_', $key)[0];
            $dataOutWarehouseList['ProIdCard']=$key;
            $dataOutWarehouseList['ClassId']=$value['cid'];
            $dataOutWarehouseList['Price']=$value['price'];
            $dataOutWarehouseList['Count']=$value['nums'];
            $dataOutWarehouseList['Money']=$value['price']*$value['nums'];

            $dataOutWarehouse['Count']+=$value['nums'];
            $dataOutWarehouse['Money']+=$dataOutWarehouseList['Money'];

            $dataOutWarehouseList['IsMark']=0;
            $dataOutWarehouseList['Remarks']="";
            $dataOutWarehouseList['Supplier']="";
            $dataOutWarehouseList['CreateDate']=$nowDate;
            $dataOutWarehouseList['LastUpdateDate']=$nowDate;
            $dataOutWarehouseList['token']=$this->webParam['token'];

            $res=$this->BM('productoutwarehouselist')->add($dataOutWarehouseList);

            if ($res) 
            {
                
                $res=$this->WM()->query("UPDATE [tb_".$data['mt']['whid']."] SET [StockCount]=StockCount-".$value['nums'].",[VirtualCount]=VirtualCount-".$value['nums']." WHERE [ProIdCard] = '$key'");

                if ($res===false) {
                    $this->BM()->rollback();
                    $this->WM()->rollback();
                    $this->ajaxReturn(array('status'=>false,'info'=>'0'),'JSON');
                }
            }
            else
            {
                $this->BM()->rollback();
                $this->WM()->rollback();
                $this->ajaxReturn(array('status'=>false,'info'=>'1'),'JSON');
            }
        }

        $res=$this->BM('productoutwarehouse')->add($dataOutWarehouse);

        if ($res) 
        {
            $this->BM()->commit();
            $this->WM()->commit();
            $this->ajaxReturn(array('status'=>true,'info'=>'success'),'JSON');
        }
        else
        {
            $this->BM()->rollback();
            $this->WM()->rollback();
            $this->ajaxReturn(array('status'=>false,'info'=>'2'),'JSON');
        }

        
    }

    public function getProduct()
    {
    	$data=$_POST;
    	//$pinfo=$this->BM('product')->where(array('token'=>$this->webParam['token'],'stoken'=>$this->webParam['stoken'],'ClassType'=>$data['cid']))->field('ID,ProName')->select();
    	$pinfo=$this->BM('product')->where(array('token'=>$this->webParam['token'],'ClassType'=>$data['cid']))->field('ProId,ProName')->select();
    	if ($pinfo) {
    		# code...
    	}
    	else
    	{
    		$pinfo='-1';
    	}
    	$this->ajaxReturn(array('status'=>true,'info'=>'success','data'=>$pinfo),'JSON');
    }

    public function getProAttr()
    {
    	$data=$_POST;

    	$pinfo=$this->readGoodInfo($data['pid']);
    	if ($pinfo['attrs']) {
    		$pinfo=$pinfo['attrs'];
    	}
    	else
    	{
    		$pinfo='-1';
    	}

    	$this->ajaxReturn(array('status'=>true,'info'=>'success','data'=>$pinfo),'JSON');
    }



   	public function readGoodInfo($GoodId)
	{
	    $json_string = file_get_contents($this->webParam['realpath'].C('GOODS_INFO_PATH').$GoodId.'.json');//读取json内容
        $goodsInfo=array();
        if ($json_string) {
            $goodsInfo=json_decode($json_string,true);
        }
        else
        {
        	//如果没读取到文件 就读取数据库里的属性
        }
        return $goodsInfo;
	}
}