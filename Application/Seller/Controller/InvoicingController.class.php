<?php
/**
 * Created by PhpStorm.
 * User: 李艳波
 * Date: 15-8-6
 * Time: 下午4:30
 * Dec : 进销存库存管理功能组件
 */
namespace Seller\Controller;
use \Think\Controller;
header('content-type:text/html;charset=utf-8');
/**
 * 进销存 查询
 */
class InvoicingController extends CommonController
{
    private $model;
    private $handleId="";     // 操作人id
    private $handleName="";  // 操作人名称
    public $mainWare="";//主仓库名称
    public $apiurl;
    public $key;
    public $merchantNo;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = M();
        $tempLogin=session('userinfo');
        $handleId=$tempLogin["ID"];
        $HandleName=$tempLogin["TrueName"];
        $this->mainWare="wh".substr($this->token,-8,8);
        $this->apiurl='http://api.tongxingpay.com/txpayApi/offLine';
        $this->key='d80efe63192b4b7ebf9a30d78075b8fa';
        $this->merchantNo='TX0001455';
    }

    // + -------------------------------------------------------------
    // + 库存查询 开始
    // + -------------------------------------------------------------

    /**
     * 库存查询
     */
    public function index()
    {
        if (IS_GET) {
            $stime=$this->model->query("SELECT TOP 1 CONVERT(varchar(100), Date, 20) AS Date FROM RS_ProductInventory WHERE StorehouseId='zb' AND Status=1 ORDER BY Date DESC");
            if(count($stime)>0){
                $this->assign("stime",$stime[0]["Date"]);
            }
            else
            {
                $this->assign("stime",date('Y-m-d H:m:s',strtotime(" -30 day")));
            }

            $this->assign("typeList", $this->getProClass())
                ->assign("warehouseList", $this->getWarehouselist())
                ->display();
        }
    }

    /**
     * 查询库存信息
     * @return 库存查询信息
     */
    public function kcselect()
    {
        // ================================================
        // 查询 记录总数:@doCount=1 and @doAll=0
        // 查询 分页数据:@doCount=0 and @doAll=0
        // 查询 全部数据:@doCount=0 and @doAll=1
        // ================================================
        $warehouseCard = isset($_REQUEST["wcard"]) ? trim($_REQUEST["wcard"]) : 'zb';
        $timeStart = isset($_REQUEST["stime"]) ? trim($_REQUEST["stime"]) : date("Y-m-01 H:i:s", time());
        $proType = isset($_REQUEST["type"]) ? trim($_REQUEST["type"]): "";
        $proName = isset($_REQUEST["name"]) ? trim(htmlspecialchars($_REQUEST["name"])) : "";
        $IsCountEqualZero = isset($_REQUEST["iszero"]) ? intval(trim($_REQUEST["iszero"])) : 0;
        $ty=trim($_REQUEST["ty"]);
        $ckname=C('CKname').'.dbo.tb_';
        // 存储过程关联表。。。。 //存储过程新增变量 @Wdb 为仓库表所在数据库以及表前缀。关联两个数据库查询使用
        $procedure = <<<TBL
DECLARE @return_value int
EXEC    @return_value = [dbo].[P_Invoicing]
        @WarehouseTableIndex = N'{$warehouseCard}',
        @timeStart = N'{$timeStart}',
        @proType = N'{$proType}',
        @proName = N'{$proName}',
        @IsCountEqualZero = {$IsCountEqualZero},
        @PageSize = 50,
        @PageIndex = 1,
        @doCount = 0,
        @doAll = 1,
        @Wdb =N'{$ckname}'
TBL;

        $list = $this->model->setIsProc(true)->query($procedure);
        if (IS_POST && $ty=="select") {
            if($list!=false)
            {
                echo "{\"code\":\"0\",\"dataPro\":".json_encode($list)."}";
            }
            else
            {
                echo "{\"code\":\"1\"}";
            }
        }
        else if(IS_GET && $ty=="export"){
            $xlsName="product_".date('ymdHm');
            $xlsCell = array(
                array('RowNumber','序号'),
                array('ClassName' , '商品上级类别'),
                array('ProName' , '商品名称'),
                array('ProIdInputCard' , '规格编码'),
                array('Spec' ,'规格属性'),
                array('BeginCount' , '期初库存（'.$timeStart."）"),
                array('LeijiInCount' , '累计进货'),
                array('InCount' , '进货数'),
                array('YingCount' , '盘盈数'),
                array('KuiCount' , '盘亏数'),
                array('LeijiSalesCount' , '累计销售数'),
                array('SalesCount' , '销售数'),
                array('OutCount' , '调拨数'),
                array('TuiCount' , '退货数【供应商】'),
                array('NowCount' , '当前库存（'.date('Y-m-d H:m:s')."）"),
                array('LimitCount',"库存下限")
            );
            exportExcel($xlsName,$xlsCell,$list);
        }
    }

    /**
     * 库存查询 明细信息
     */
    public function indexinfo()
    {
        if(IS_GET)
        {
            $ty=trim($_GET["ty"]);
            $pid=trim($_GET["pid"]);
            $date=trim($_GET["date"]);
            $wid=trim($_GET["wid"]);

$sql=<<<TAB
DECLARE @return_value int
EXEC    @return_value = [dbo].[P_Invoicing_Info]
        @type = N'{$ty}',
        @WarehouseTableIndex = N'{$wid}',
        @timeStart = N'{$date}',
        @proid = N'{$pid}'
TAB;
            $this->assign("type",intval($ty))->assign("data",$this->model->setIsProc(true)->query($sql))->display();
        }
    }

    // + -------------------------------------------------------------
    // + 库存查询 结束
    // + -------------------------------------------------------------
    // + -------------------------------------------------------------
    // + 商品入库功能区域 开始
    // + -------------------------------------------------------------

    /**
     * 商品入库 管理界面
     */
    public function inwarehouselist()
    {
        $ClassType = $this->getProClass();
        $Warehouse = $this->getWarehouselist();

        $this->assign("datatime",array("dateStart"=>date('Y-m-d', strtotime('-7 day')),"dateEnd"=>date('Y-m-d', strtotime('+1 day'))))
            ->assign("typeList", $ClassType)
            ->assign("warehouseList", $Warehouse)
                ->display();
    }

    public function getinwarehouselist()
    {
        $selectType=trim($_POST["ty"]);
        $warehouse=trim($_POST["w"]);
        $incard=trim($_POST["cid"]);
        $classType=trim($_POST["t"]);
        $pid=trim($_POST["pid"]);
        $intype=trim($_POST["intype"]);

        $page_index=intval(trim($_POST["pindex"]));   // 页索引
        $page_size=intval(trim($_POST["psize"]));     // 页条数
        $page_temp_count=$page_count=intval(trim($_POST["pcount"]));   // 总数

        $stime=trim($_POST["stime"]);
        $etime=trim($_POST["etime"]);

        $incards=$tempsql=$tempincardsql="";
        if(!empty($incard))
        {
            $tempincardsql= " AND ( ( InWarehouseId LIKE ('%".$incard."%') ) OR ( InWarehouseNumber LIKE ('%".$incard."%') ) )";
        }

        $temp1=$temp2=$temp3="";
        if(!empty($classType) || !empty($pid))
        {
            if(!empty($classType))
            {
                $temp1=" AND a.ClassId=('".$classType."') ";
            }

            if(!empty($pid))
            {
                $temp2=" AND (c.ProName LIKE ('%".$pid."%') OR b.ProIdInputCard LIKE ('%".$pid."%')) ";
            }

            $tempsql=" AND EXISTS (SELECT a.InWarehouseId FROM RS_ProductInWarehouseList a LEFT JOIN RS_ProductList b ON a.ProIdCard=b.ProIdCard LEFT JOIN RS_Product c ON a.ProId=c.ProId WHERE a.InWarehouseId=RS_ProductInWarehouse.InWarehouseId ".$temp1.$temp2.")";
        }

        if($intype!="-1")
        {
            $temp3= " AND Type='".$intype."' ";
        }

        // 第一次查找
        if($selectType=="first")
        {
            $tempsqls=<<<TAB
SELECT COUNT(ID) AS rowss FROM RS_ProductInWarehouse WHERE InStorehouseId=('{$warehouse}') AND Date BETWEEN ('{$stime}') AND ('{$etime}') {$tempsql} {$tempincardsql} {$temp3}
TAB;

            // 获取查询条件的主表数量
            $partempCount=$this->model->query($tempsqls);
            $page_count=intval($partempCount[0]['rowss']);
            if($page_count>0)
            {
                $parsql = <<<TBL
DECLARE  @return_value int
EXEC @return_value = [dbo].[P_InWarehouse_ParList]
     @type = N'first',
     @inck= N'{$warehouse}',
     @incard =N'{$incard}',
     @classType =N'{$classType}',
     @intype = N'{$intype}',
     @pid =N'{$pid}',
     @timeStart = N'{$stime}',
     @timeEnd = N'{$etime}',
     @starts = 0,
     @ends = {$page_size}
TBL;
            }
            else
            {
                echo "{\"code\":\"0\",\"pagecount\":\"0\"}";
                exit;
            }
        }

        // 点击更多
        if($selectType=="more")
        {
            $starts= ($page_index-1) * $page_size + 1;
            $ends= ($page_index-1) * $page_size + $page_size;

            // 分页查询
            $parsql = <<<TBL
DECLARE  @return_value int
EXEC @return_value = [dbo].[P_InWarehouse_ParList]
     @type = N'more',
     @inck= N'{$warehouse}',
     @incard =N'{$incard}',
     @classType =N'{$classType}',
     @intype = N'{$intype}',
     @pid =N'{$pid}',
     @timeStart = N'{$stime}',
     @timeEnd = N'{$etime}',
     @starts = {$starts},
     @ends = {$ends}
TBL;
        }

        // 获取主表数据
        $data=$this->model->setIsProc(true)->query($parsql);
        if(count($data))
        {
            foreach ($data as $val) {
                $incards.="'".$val["InWarehouseId"]."',";
            }
            $incards=substr($incards,0,strlen($incards)-1);     // 删除最后一个字符

            $sonsql=<<<TAB
SELECT a.InWarehouseId,c.ClassName,c.ProName,b.ProIdInputCard,
b.ProSpec1 + (CASE WHEN ISNULL(b.ProSpec2,'')='' THEN '' ELSE '/'+b.ProSpec2 END) +
    (CASE WHEN ISNULL(b.ProSpec3,'')='' THEN '' ELSE '/'+b.ProSpec3 END) +
    (CASE WHEN ISNULL(b.ProSpec4,'')='' THEN '' ELSE '/'+b.ProSpec4 END) +
    (CASE WHEN ISNULL(b.ProSpec5,'')='' THEN '' ELSE '/'+b.ProSpec5 END) AS Spec,
a.Count,a.Price,a.Money,a.Supplier
FROM RS_ProductInWarehouseList a
LEFT JOIN RS_ProductList b ON a.ProIdCard=b.ProIdCard
LEFT JOIN RS_Product c ON a.ProId=c.ProId
WHERE a.InWarehouseId IN ({$incards})
{$temp1} {$temp2}
ORDER BY a.InWarehouseId,a.ProIdCard
TAB;

            $datasonList=$this->model->query($sonsql);  // 获取子表数据

            foreach ($data as &$value) {
                $value["datason"]=$this->filterArraySon($value["InWarehouseId"],"InWarehouseId",$datasonList);
            }

            $outdata["code"]="0";
            $outdata["pagecount"] = $page_count; // 总条数

            // 如果是第一次查询
            if(selectType=="first")
            {
                if($page_temp_count>$page_count)
                {
                    $outdata["pageindex"]="1";
                }
                else
                {
                    $outdata["pageindex"]="0";
                }
            }
            else
            {
                $outdata["pageindex"]=$page_index;
            }

            $outdata["datapar"]=$data;
            echo json_encode($outdata);
            exit;
        }
        else
        {
            echo "{\"code\":\"0\",\"pagecount\":\"-1\"}";
        }
    }

    /**
     * 商品入库 添加界面
     */
    public function inwarehouse()
    {
        // 仓库列表
        $Warehouse = $this->getWarehouselist();
        // var_dump($Warehouse);exit();
        $Employee=$this->getEmployee();
        if (isset($_GET["cid"])) {
            $cid = $_GET["cid"];
            $data = $this->model->query("
 SELECT
a.InWarehouseId,a.InWarehouseNumber,CONVERT(varchar(100), a.Date, 20) AS Date,a.InputId,a.Type,a.Remarks,a.InStorehouseId,
b.ProIdCard,d.ClassName,d.ProName,
c.ProIdInputCard,
c.ProSpec1 + (CASE WHEN ISNULL(c.ProSpec2,'')='' THEN '' ELSE '/'+c.ProSpec2 END) +
    (CASE WHEN ISNULL(c.ProSpec3,'')='' THEN '' ELSE '/'+c.ProSpec3 END) +
    (CASE WHEN ISNULL(c.ProSpec4,'')='' THEN '' ELSE '/'+c.ProSpec4 END) +
    (CASE WHEN ISNULL(c.ProSpec5,'')='' THEN '' ELSE '/'+c.ProSpec5 END) AS Spec,
b.Count,b.price,b.Money,b.Supplier
 FROM RS_ProductInWarehouse a
LEFT JOIN RS_ProductInWarehouseList b ON a.InWarehouseId = b.InWarehouseId
LEFT JOIN RS_ProductList c ON b.ProIdCard=c.ProIdCard
LEFT JOIN RS_Product d ON d.ProId=b.ProId
WHERE Status=0 AND a.InWarehouseId='".$cid."'
ORDER BY d.ClassType,b.ProIdCard");

            if(count($data)>0)
            {
                $this->assign("cid",$cid);
            }
            $this->assign("data",$data);
        }
        $this->assign("employee",$Employee)->assign("warehouseList", $Warehouse)->display();
    }

    // /**
    //  * 入库商品查询 弹出层
    //  */
    // public function showInWarehouseProdialog()
    // {
    //     $warehouseindex = trim($_GET["w"]);
    //     $InWarehouseId = trim($_GET["pid"]);
    //     // 类别
    //     $proClass = $this->getProClass();

    //     if ($proClass) {
    //         $this->assign('typeList', $proClass);
    //     }

    //     // 供应商
    //     $supplier = $this->getSupplier("name");
    //     if ($supplier) {
    //         $this->assign('supplierCount', count($supplier))->assign('supplier', json_encode($supplier));
    //     } else {
    //         $this->assign('supplierCount', 0);
    //     }

    //     // 如果商品子表总数量不超过 200个
    //     $procount = $this->model->query("SELECT COUNT(ID) AS rows FROM RS_ProductList");
    //     $count = intval($procount[0]["rows"]);
    //     if ($count <= 200) {
    //         if ($count > 0) {
    //             $this->assign('proInfo', $this->getProListByType($warehouseindex,"In",$InWarehouseId));
    //         }
    //     }
    //     $this->assign("InWarehouseId", $InWarehouseId)->assign('warehouseIndex', $warehouseindex)->assign("proCount", $count)->display();
    // }


    /**
     * 入库商品查询 弹出层
     */
    public function showInWarehouseProdialog()
    {
        $warehouseindex = trim($_GET["w"]);
        $InWarehouseId = trim($_GET["pid"]);
        // 类别
        $proClass = $this->getProClass();

        if ($proClass) {
            $this->assign('typeList', $proClass);
        }

        // 供应商
        $supplier = $this->getSupplier("name");
        if ($supplier) {
            $this->assign('supplierCount', count($supplier))->assign('supplier', json_encode($supplier));
        } else {
            $this->assign('supplierCount', 0);
        }
        // 如果商品子表总数量不超过 200个
        if ($_GET['type']=='4') {
          $pram="token='".$this->token."' and stoken='0'";
        }else{
          $pram="token='".$this->token."' and stoken='".$this->stoken."'";
        }
        $procount = $this->model->query("SELECT COUNT(ID) AS rows FROM RS_ProductList WHERE ".$pram);
        // var_dump($procount);exit();
        $count = intval($procount[0]["rows"]);
        if ($count <= 200) {
            if ($count > 0) {
              // file_put_contents('1.txt',$this->getWarehouseProList($warehouseindex,"in",$_GET['type']));
                // var_dump($this->getWarehouseProList($warehouseindex,"in",$_GET['type']));exit();
                $this->assign('proInfo', $this->getWarehouseProList($warehouseindex,"in",$_GET['type']));
            }
        }
        $this->assign("InWarehouseId", $InWarehouseId)->assign('warehouseIndex', $warehouseindex)->assign("proCount", $count)->display();
    }

    /**
     * 提取入库草稿界面
     */
    public function showInWarehouseDraft()
    {
        if (IS_GET) {
            $p["w"] = trim($_GET["w"]);
            $p["type"] = intval(trim($_GET["type"]));
            $p["dateStart"] = date('Y-m-d', strtotime('-7 day'));
            $p["dateEnd"] = date('Y-m-d', time());
        }

        if (IS_POST) {
            $p["w"] = trim($_POST["warehouse"]);
            $p["type"] = intval(trim($_POST["intype"]));
            $p["dateStart"] = trim($_POST["dateStart"]);
            $p["dateEnd"] = trim($_POST["dateEnd"]);
        }

        $data = $this->model->query("SELECT InWarehouseId,CONVERT(varchar(100), Date, 120) AS Date,InputName,Type,Remarks FROM RS_ProductInWarehouse
 WHERE InStorehouseId=('" . $p["w"] . "') AND Type=('" . $p["type"] . "') AND Status=0 AND Date BETWEEN ('" . $p["dateStart"] . "') AND ('" . $p["dateEnd"] . " 23:59:59') ORDER BY Date");

        $this->assign("p", $p)->assign("data", $data)->display();
    }

    /**
     * 商品入库 添加/更新/删除 保存
     */
    public function saveInwarehouse()
    {
        if (IS_POST) {
            $ty = $_POST["ty"];
            // 继续添加入库商品
            if ($ty == "in_warehouse") {
                $id = trim($_POST["id"]);
                $proid = trim($_POST["proid"]);
                $classid = trim($_POST["classid"]);
                // 后续添加
                $attrs = $_POST["attr"];

                $tempCount=$tempMoney=0;
                foreach ($attrs as $val) {
                    $info["InWarehouseId"] = $id;
                    $info["ProId"] = $proid;
                    $info["ProIdCard"] = $val["card"];
                    $info["ClassId"] = $classid;
                    $info["Price"] = floatval($val["price"]);
                    $info["Count"] = intval($val["innum"]);
                    $tempCount+=$info["Count"];
                    $info["Money"] = $info["Price"] * $info["Count"];
                    $tempMoney+=$info["Money"];
                    $info["Supplier"] = (trim($val["supname"])=="-1"?"":trim($val["supname"]));
                    $info['token']=$this->token;
                    $infos[] = $info;
                }

                if (count($infos) > 0) {
                    $this->model->startTrans();
                    $isSon = true;
                    foreach ($infos as $key => $val) {
                        $isSon = $this->model->table("RS_ProductInWarehouseList")->add($val);
                        if (!$isSon) {
                            break;
                        }
                    }
                    // 更新主表金额和数量
                    $isupMoney=$this->model->execute("UPDATE RS_ProductInWarehouse SET Count=(Count+".$tempCount."),Money=(Money+".$tempMoney."),LastUpdateDate=('".date("Y-m-d H:i:s", time())."') WHERE InWarehouseId=('".$id."')");

                    if ($isSon && $isupMoney!=false) {
                        $this->model->commit();
                        // 提交数据
                        echo "{\"code\":\"0\",\"InWarehouseId\":\"" . $id . "\"}";
                    } else {
                        $this->model->rollback();
                        echo "{\"code\":\"1\"}";
                    }
                } else {
                    echo "{\"code\":\"1\"}";
                }
            } else if ($ty == "new_in_warehouse") {
                // 生成唯一编号
                $id = $this->setOddNumber("RK");

                // 主表数据
                $parData["InWarehouseId"] = $id;
                $parData["InWarehouseNumber"]=trim($_POST["number"]);
                $parData["Status"] = 0;
                $parData["Date"] = trim($_POST["riqi"]);

                $parData["InputId"] = trim($_POST["inpid"]);
                $parData["InputName"] = trim(htmlspecialchars($_POST["inpname"]));

                $parData["Count"]=0;
                $parData["Money"]=0;

                $parData["HandleId"] = $this->handleId;
                $parData["HandleName"] = $this->handleName;

                $parData["Type"] = $_POST["intype"];
                $parData["InStorehouseId"] = $_POST["inid"];
                $parData["InStorehouseName"] = $_POST["inname"];
                $parData['token']=$this->token;
                $parData['stoken']=$this->stoken;
                if ($_POST['intype']=='4') {
                  $parData['Remarks']='入库申请单';
                }
                $proid = trim($_POST["proid"]);
                $classid = trim($_POST["classid"]);
                $attrs = $_POST["attr"];

                foreach ($attrs as $val) {
                    $info["InWarehouseId"] = $id;
                    $info["ProId"] = $proid;
                    $info["ProIdCard"] = $val["card"];
                    $info["ClassId"] = $classid;
                    $info["Price"] = floatval($val["price"]);
                    $info["Count"] = intval($val["innum"]);
                    $info["Money"] = $info["Price"] * $info["Count"];
                    $parData["Count"] += $info["Count"];
                    $parData["Money"] += $info["Money"];
                    $info["Supplier"] = (trim($val["supname"])=="-1"?"":trim($val["supname"]));
                    $info['token']=$this->token;
                    $infos[] = $info;
                }

                // 开始数据存储
                // 开启事务
                $this->model->startTrans();
                $isPar = $isSon = true;
                $isPar = $this->model->table("RS_ProductInWarehouse")->add($parData);

                foreach ($infos as $key => $val) {
                    $isSon = $this->model->table("RS_ProductInWarehouseList")->add($val);
                    if (!$isSon) {
                        break;
                    }
                }

                if ($isPar && $isSon) {
                    $this->model->commit();
                    // 提交数据
                    echo "{\"code\":\"0\",\"InWarehouseId\":\"" . $id . "\"}";
                } else {
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }
            } else if ($ty == "ok_in_warehouse") {
                $wareinfo=$this->model->table('RS_ProductInWarehouse')->where("InWarehouseId='%s'",$_POST['id'])->getField('Type');
                if ($wareinfo==4) {
                    $this->model->table('RS_ProductInWarehouse')->where("InWarehouseId='%s'",$_POST['id'])->setField("Status",'-1');
                    echo "{\"code\":\"2\"}";exit();
                }
                // 提交单据
                $id = trim($_POST["id"]);  // 获取编号
                $wid = trim($_POST["wid"]);// 仓库编号

                $getNumMoney = $this->model->query("SELECT ISNULL(SUM(Count),0) AS count,ISNULL(SUM(Money),0) AS money FROM RS_ProductInWarehouseList
 WHERE InWarehouseId=('" . $id . "')");
                $nowTime = date("Y-m-d H:i:s", time());
                $updataData = array(
                    'InWarehouseNumber'=>trim(htmlspecialchars($_POST["number"])),
                    'Count' => intval($getNumMoney[0]["count"]),
                    'Money' => floatval($getNumMoney[0]["money"]),
                    'Status' => 1,
                    'InputId' => $_POST["inputid"],
                    'InputName' => htmlspecialchars($_POST["inputname"]),
                    'HandleId' => $this->handleId,
                    'HandleName' => $this->handleName,
                    'Type' => $_POST["type"],
                    'Remarks' => trim(htmlspecialchars($_POST["remarks"])),
                    'LastUpdateDate' => $nowTime
                );

                $isPar = $isSon = $isupck = true;
                $ProIdCards=$this->model->table('RS_ProductInWarehouseList')->where("InWarehouseId='%s'",$id)->getField('ProIdCard',true);
                $Counts=$this->model->table('RS_ProductInWarehouseList')->where("InWarehouseId='%s'",$id)->getField('Count',true);
                $this->model->startTrans();
                $this->SH()->startTrans();
                /**
                 * 原联合查询语句 现改为分开查询更新 2015
                 */
                foreach ($ProIdCards as $pk => $pv) {
                  if (!$this->SH()->execute("UPDATE tb_".$wid." SET StockCount=StockCount+".$Counts[$pk].",VirtualCount=VirtualCount+".$Counts[$pk].",InCount=InCount+".$Counts[$pk]." WHERE ProIdCard='".$pv."'")) {
                    // echo $this->SH()->getlastSql();
                    $isupck=false;
                    break;
                  }
                }
                $isSon = $this->model->table("RS_ProductInWarehouseList")->where("InWarehouseId='" . $id . "'")->setField("LastUpdateDate", $nowTime);
//                 $upstockSqls = <<<TBL
//                 UPDATE RS_Warehouse_{$wid} SET StockCount=StockCount+b.Count,VirtualCount=VirtualCount+b.Count,InCount
// =InCount+b.Count FROM RS_ProductInWarehouseList b WHERE b.ProIdCard=RS_Warehouse_{$wid}.ProIdCard AND b.InWarehouseId=('{$id}')
// TBL;
//                 $isupck = $this->model->execute($upstockSqls);
                $isPar = $this->model->table("RS_ProductInWarehouse")->where("InWarehouseId='" . $id . "'")->save($updataData);
                // var_dump($isPar,$isSon,$isupck);exit;
                if ($isPar != false && $isSon != false && $isupck != false) {
                    $this->SH()->commit();
                    $this->model->commit();
                    echo "{\"code\":\"0\"}";
                } else {
                    $this->SH()->rollback();
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }
            } else if ($ty == "del_in_warehouse") {
                // 删除单据
                $id = trim($_POST["id"]);  // 获取编号
                $this->model->startTrans();
                $isSon = $this->model->table("RS_ProductInWarehouseList")->where("InWarehouseId='" . $id . "'")->delete();
                $isPar = $this->model->table("RS_ProductInWarehouse")->where("InWarehouseId='" . $id . "'")->delete();
                if ($isPar != false) {
                    $this->model->commit();
                    echo "{\"code\":\"0\"}";
                } else {
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }
            } else if ($ty == "del_pro_in_warehouse") {
                // 删除商品单据
                $id = trim($_POST["id"]);  // 获取编号
                $this->model->startTrans();
                $attrs = $_POST["attr"];
                foreach ($attrs as $val) {
                    $isSon = $this->model->table("RS_ProductInWarehouseList")->where(array('InWarehouseId' => $id, "ProIdCard" => $val["card"]))->delete();
                    if (!$isSon) {
                        break;
                    }
                }

                $nowtime=date("Y-m-d H:i:s", time());

                // 此处合计数字
                $upParSqls = <<<TBL
UPDATE RS_ProductInWarehouse SET Count=b.Count,Money=b.Money,LastUpdateDate=('{$nowtime}')
FROM (SELECT '{$id}' AS InWarehouseId,ISNULL(SUM(Count),0) AS Count,ISNULL(SUM(Money),0) AS Money
    FROM RS_ProductInWarehouseList WHERE InWarehouseId=('{$id}')) b
WHERE RS_ProductInWarehouse.InWarehouseId=b.InWarehouseId
TBL;
                $isupMoney=$this->model->execute($upParSqls);

                if ($isSon != false && $isupMoney!=false) {
                    $this->model->commit();
                    echo "{\"code\":\"0\"}";
                } else {
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }
            } else {
                echo "{\"code\":\"1\"}";
            }
        } else {
            echo "{\"code\":\"1\"}";
        }

    }

    // + -------------------------------------------------------------
    // + 商品入库功能区域 结束
    // + -------------------------------------------------------------
    // + -------------------------------------------------------------
    // + 商品出库功能区域 开始
    // + -------------------------------------------------------------

    /**
     * 调拨/退货出库
     */
    public function outwarehouse()
    {
        // 仓库列表
        $Warehouse = $this->getWarehouselist();

        $suppliers=$this->getSupplier();

        $Employee=$this->getEmployee();


        if (isset($_GET["cid"])) {
            $cid = $_GET["cid"];
            $data = $this->model->query("
 SELECT
a.OutWarehouseId,a.OutWarehouseNumber,CONVERT(varchar(100), a.Date, 20) AS Date,a.OutputId,a.Type,a.Remarks,a.OutStorehouseId,a.InStorehouseId,
b.ProIdCard,d.ClassName,d.ProName,
c.ProIdInputCard,
c.ProSpec1 + (CASE WHEN ISNULL(c.ProSpec2,'')='' THEN '' ELSE '/'+c.ProSpec2 END) +
    (CASE WHEN ISNULL(c.ProSpec3,'')='' THEN '' ELSE '/'+c.ProSpec3 END) +
    (CASE WHEN ISNULL(c.ProSpec4,'')='' THEN '' ELSE '/'+c.ProSpec4 END) +
    (CASE WHEN ISNULL(c.ProSpec5,'')='' THEN '' ELSE '/'+c.ProSpec5 END) AS Spec,
b.Count,b.price,b.Money,b.Supplier
 FROM RS_ProductOutWarehouse a
LEFT JOIN RS_ProductOutWarehouseList b ON a.OutWarehouseId = b.OutWarehouseId
LEFT JOIN RS_ProductList c ON b.ProIdCard=c.ProIdCard
LEFT JOIN RS_Product d ON d.ProId=b.ProId
WHERE Status=0 AND a.OutWarehouseId='".$cid."'
ORDER BY d.ClassType,b.ProIdCard");

            if(count($data)>0)
            {
                $this->assign("cid",$cid);
            }

            $this->assign("data",$data);
        }
        $this->assign("employee",$Employee)->assign("warehouseList", $Warehouse)->assign("supplierlist",$suppliers)->display();
    }

    /**
     * 出库商品查询 弹出层
     */
    public function showOutWarehouseProdialog()
    {
        $warehouseindex = trim($_GET["w"]);
        $OutWarehouseId = trim($_GET["pid"]);
        // 类别
        $proClass = $this->getProClass();

        if ($proClass) {
            $this->assign('typeList', $proClass);
        }

        // 如果商品子表总数量不超过 100个
        $procount = $this->model->query("SELECT COUNT(ID) AS rows FROM RS_ProductList");
        $count = intval($procount[0]["rows"]);
        if ($count <= 200) {
            if ($count > 0) {
                $this->assign('proInfo', $this->getWarehouseProList($warehouseindex,"out"));
            }
        }
        $this->assign("OutWarehouseId", $OutWarehouseId)->assign('warehouseindex', $warehouseindex)->assign("proCount", $count)->display();
    }

    /**
     * 提取出库草稿界面
     */
    public function showOutWarehouseDraft()
    {
        if (IS_GET) {
            $p["w"] = trim($_GET["w"]);
            $p["type"] = trim($_GET["type"]);
            $p["dateStart"] = date('Y-m-d', strtotime('-7 day'));
            $p["dateEnd"] = date('Y-m-d', time());
        }

        if (IS_POST) {
            $p["w"] = trim($_POST["warehouse"]);
            $p["type"] = trim($_POST["intype"]);
            $p["dateStart"] = trim($_POST["dateStart"]);
            $p["dateEnd"] = trim($_POST["dateEnd"]);
        }

        $data = $this->model->query("SELECT OutWarehouseId,CONVERT(varchar(100), Date, 120) AS Date,OutputName,Type,Remarks FROM RS_ProductOutWarehouse
 WHERE OutStorehouseId=('" . $p["w"] . "') AND Type=('" . $p["type"] . "') AND Status=0 AND Date BETWEEN ('" . $p["dateStart"] . "') AND ('" . $p["dateEnd"] . " 23:59:59') ORDER BY Date");
        $this->assign("p", $p)->assign("data", $data)->display();
    }

    /**
     * 调拨/退货出库管理
     */
    public function outwarehouselist()
    {
        $ClassType = $this->getProClass();
        $Warehouse = $this->getWarehouselist();

        $this->assign("datatime",array("dateStart"=>date('Y-m-d', strtotime('-7 day')),"dateEnd"=>date('Y-m-d', strtotime('+1 day'))))
            ->assign("typeList", $ClassType)
            ->assign("warehouseList", $Warehouse)
                ->display();
    }

    public function getoutwarehouselist()
    {
        $selectType=trim($_POST["ty"]); // 类型 first / more
        $warehouse=trim($_POST["w"]);   // 出库仓库
        $outcard=trim($_POST["cid"]);    // 出库单号
        $classType=trim($_POST["t"]);   // 商品类别
        $pid=trim($_POST["pid"]);       // 商品名称或商品规格编号
        $outtype=trim($_POST["outtype"]);// 出库类别

        $page_index=intval(trim($_POST["pindex"]));   // 页索引
        $page_size=intval(trim($_POST["psize"]));     // 页条数

        $page_temp_count=$page_count=intval(trim($_POST["pcount"]));   // 总数

        $stime=trim($_POST["stime"]);
        $etime=trim($_POST["etime"]);

        $outcards=$tempsql=$tempincardsql="";
        if(!empty($outcard))
        {
            $tempincardsql= " AND OutWarehouseId LIKE ('%".$outcard."%') ";
        }

        $temp1=$temp2=$temp3="";
        if(!empty($classType) || !empty($pid))
        {
            if(!empty($classType))
            {
                $temp1=" AND a.ClassId=('".$classType."') ";
            }

            if(!empty($pid))
            {
                $temp2=" AND (c.ProName LIKE ('%".$pid."%') OR b.ProIdInputCard LIKE ('%".$pid."%')) ";
            }

            $tempsql=" AND EXISTS (SELECT a.OutWarehouseId FROM RS_ProductOutWarehouseList a LEFT JOIN RS_ProductList b ON a.ProIdCard=b.ProIdCard LEFT JOIN RS_Product c ON a.ProId=c.ProId WHERE a.OutWarehouseId=RS_ProductOutWarehouse.OutWarehouseId ".$temp1.$temp2.")";
        }

        if($outtype!="-1")
        {
            $temp3=" AND Type='".$outtype."' ";
        }

        // 第一次查找
        if($selectType=="first")
        {
            $tempsqls=<<<TAB
SELECT COUNT(ID) AS rowss FROM RS_ProductOutWarehouse WHERE OutStorehouseId=('{$warehouse}') AND Date BETWEEN ('{$stime}') AND ('{$etime}') {$tempsql} {$tempincardsql} {$temp3}
TAB;

            // 获取查询条件的主表数量
            $partempCount=$this->model->query($tempsqls);
            $page_count=intval($partempCount[0]['rowss']);
            if($page_count>0)
            {
                $parsql = <<<TBL
DECLARE  @return_value int
EXEC @return_value = [dbo].[P_OutWarehouse_ParList]
     @type = N'first',
     @outck=N'{$warehouse}',
     @outcard =N'{$outcard}',
     @classType =N'{$classType}',
     @outtype =N'{$outtype}',
     @pid =N'{$pid}',
     @timeStart = N'{$stime}',
     @timeEnd = N'{$etime}',
     @starts = 0,
     @ends = {$page_size}
TBL;
            }
            else
            {
                echo "{\"code\":\"0\",\"pagecount\":\"0\"}";
                exit;
            }
        }

        // 点击更多
        if($selectType=="more")
        {
            $starts= ($page_index-1) * $page_size + 1;
            $ends= ($page_index-1) * $page_size + $page_size;

            // 分页查询
            $parsql = <<<TBL
DECLARE  @return_value int
EXEC @return_value = [dbo].[P_OutWarehouse_ParList]
     @type = N'more',
     @outck=N'{$warehouse}',
     @outcard =N'{$outcard}',
     @classType =N'{$classType}',
     @outtype =N'{$outtype}',
     @pid =N'{$pid}',
     @timeStart = N'{$stime}',
     @timeEnd = N'{$etime}',
     @starts = {$starts},
     @ends = {$ends}
TBL;
        }

        // 获取主表数据
        $data=$this->model->setIsProc(true)->query($parsql);
        if(count($data))
        {
            foreach ($data as $val) {
                $outcards.="'".trim($val["OutWarehouseId"])."',";
            }

            $outcards=substr($outcards,0,strlen($outcards)-1);     // 删除最后一个字符

            $sonsql=<<<TAB
SELECT a.OutWarehouseId,c.ClassName,c.ProName,b.ProIdInputCard,
b.ProSpec1 + (CASE WHEN ISNULL(b.ProSpec2,'')='' THEN '' ELSE '/'+b.ProSpec2 END) +
    (CASE WHEN ISNULL(b.ProSpec3,'')='' THEN '' ELSE '/'+b.ProSpec3 END) +
    (CASE WHEN ISNULL(b.ProSpec4,'')='' THEN '' ELSE '/'+b.ProSpec4 END) +
    (CASE WHEN ISNULL(b.ProSpec5,'')='' THEN '' ELSE '/'+b.ProSpec5 END) AS Spec,
a.Count,a.Price,a.Money
FROM RS_ProductOutWarehouseList a
LEFT JOIN RS_ProductList b ON a.ProIdCard=b.ProIdCard
LEFT JOIN RS_Product c ON a.ProId=c.ProId
WHERE a.OutWarehouseId IN ({$outcards})
{$temp1} {$temp2}
ORDER BY a.OutWarehouseId,a.ProIdCard
TAB;
            $datasonList=$this->model->query($sonsql);  // 获取子表数据

            foreach ($data as &$value) {
                $value["datason"]=$this->filterArraySon($value["OutWarehouseId"],"OutWarehouseId",$datasonList);
            }

            $outdata["code"]="0";
            $outdata["pagecount"] = $page_count; // 总条数

            // 如果是第一次查询
            if(selectType=="first")
            {
                if($page_temp_count>$page_count)
                {
                    $outdata["pageindex"]="1";
                }
                else
                {
                    $outdata["pageindex"]="0";
                }
            }
            else
            {
                $outdata["pageindex"]=$page_index;
            }

            $outdata["datapar"]=$data;
            echo json_encode($outdata);
            exit;
        }
        else
        {
            echo "{\"code\":\"0\",\"pagecount\":\"-1\"}";
        }
    }


    /**
     * 商品出库 添加/更新/删除 保存
     */
    public function saveOutwarehouse()
    {
        if (IS_POST) {
            $ty = $_POST["ty"];
            // 继续添加入库商品
            if ($ty == "out_warehouse") {
                $id = trim($_POST["id"]);
                $proid = trim($_POST["proid"]);
                $classid = trim($_POST["classid"]);
                // 后续添加
                $attrs = $_POST["attr"];
                $tempCount=$tempMoney=0;
                foreach ($attrs as $val) {
                    $info["OutWarehouseId"] = $id;
                    $info["ProId"] = $proid;
                    $info["ProIdCard"] = $val["card"];
                    $info["ClassId"] = $classid;
                    $info["Price"] = floatval($val["price"]);
                    $info["Count"] = intval($val["innum"]);
                    $tempCount+=$info["Count"];
                    $info["Money"] = $info["Price"] * $info["Count"];
                    $tempMoney+=$info["Money"];
                    $info["Remarks"]="";
                    $info["Supplier"] = "";
                    $info["OrderId"]="";
                    $info['token']=$this->token;
                    $infos[] = $info;
                }

                if (count($infos) > 0) {
                    $this->model->startTrans();
                    $isSon = true;
                    foreach ($infos as $key => $val) {
                        $isSon = $this->model->table("RS_ProductOutWarehouseList")->add($val);
                        if (!$isSon) {
                            break;
                        }
                    }
                    // 更新主表金额和数量
                    $isupMoney=$this->model->execute("UPDATE RS_ProductOutWarehouse SET Count=(Count+".$tempCount."),Money=(Money+".$tempMoney."),HandleId=('".$this->handleId."'),HandleName=('".$this->handleName."'),LastUpdateDate=('".date("Y-m-d H:i:s", time())."') WHERE OutWarehouseId=('".$id."')");

                    if ($isSon && $isupMoney!=false) {
                        $this->model->commit();
                        // 提交数据
                        echo "{\"code\":\"0\",\"OutWarehouseId\":\"" . $id . "\"}";
                    } else {
                        $this->model->rollback();
                        echo "{\"code\":\"1\"}";
                    }
                } else {
                    echo "{\"code\":\"1\"}";
                }
            } else if ($ty == "new_out_warehouse") {
                $parData["Type"] = trim($_POST["outtype"]);
                // 生成唯一编号
                $id=$this->setOddNumber($parData["Type"]=="0"?"CK":"TH");
                // 主表数据
                $parData["OutWarehouseId"] = $id;
                $parData["OutWarehouseNumber"]=trim($_POST["number"]);
                $parData["Status"] =0;
                $parData["Date"] = trim($_POST["riqi"]);

                $parData["Count"] = 0;
                $parData["Money"] = 0;

                $parData["OutputId"] = trim($_POST["outpid"]);
                $parData["OutputName"] = trim(htmlspecialchars($_POST["outpname"]));

                $parData["HandleId"] = $this->handleId;
                $parData["HandleName"] = $this->handleName;

                $parData["OutStorehouseId"]=trim($_POST["outid"]);
                $parData["OutStorehouseName"]=trim(htmlspecialchars($_POST["outname"]));

                $parData["InStorehouseId"] = $_POST["inid"];
                $parData["InStorehouseName"] = $_POST["inname"];
                $parData["InType"]='';

                $proid = trim($_POST["proid"]);
                $classid = trim($_POST["classid"]);
                $attrs = $_POST["attr"];

                foreach ($attrs as $val) {
                    $info["OutWarehouseId"] = $id;
                    $info["ProId"] = $proid;
                    $info["ProIdCard"] = $val["card"];
                    $info["ClassId"] = $classid;
                    $info["Price"] = floatval($val["price"]);
                    $info["Count"] = intval($val["innum"]);
                    $info["Money"] = $info["Price"] * $info["Count"];
                    $parData["Count"] += $info["Count"];
                    $parData["Money"] += $info["Money"];
                    $info["Remarks"]="";
                    $info["Supplier"] = "";
                    $info["OrderId"]="";
                    $info['token']=$this->token;
                    $infos[] = $info;
                }

                // 开始数据存储

                // 开启事务
                $this->model->startTrans();
                $isPar = $isSon = true;
                $isPar = $this->model->table("RS_ProductOutWarehouse")->add($parData);

                foreach ($infos as $key => $val) {
                    $isSon = $this->model->table("RS_ProductOutWarehouseList")->add($val);
                    if (!$isSon) {
                        break;
                    }
                }
                if ($isPar && $isSon) {
                    $this->model->commit();
                    // 提交数据
                    echo "{\"code\":\"0\",\"OutWarehouseId\":\"" . $id . "\"}";
                } else {
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }
            } else if ($ty == "ok_out_warehouse") {

                // 提交单据
                $id = trim($_POST["id"]);  // 获取编号
                $wid = trim($_POST["wid"]);// 仓库编号
                $getNumMoney = $this->model->query("SELECT ISNULL(SUM(Count),0) AS count,ISNULL(SUM(Money),0) AS money FROM RS_ProductOutWarehouseList
 WHERE OutWarehouseId=('" . $id . "')");
                $nowTime = date("Y-m-d H:i:s", time());
                $type=trim($_POST["type"]);
                $updataData = array(
                    'OutWarehouseNumber'=>trim(htmlspecialchars($_POST["number"])),
                    'Count' => intval($getNumMoney[0]["count"]),
                    'Money' => floatval($getNumMoney[0]["money"]),
                    'Status' => 1,
                    'OutputId' => $_POST["outputid"],
                    'OutputName' => htmlspecialchars($_POST["outputname"]),
                    'HandleId' =>  $this->handleId,
                    'HandleName' => $this->handleName,
                    'Remarks' => trim(htmlspecialchars($_POST["remarks"])),
                    'LastUpdateDate' => $nowTime
                );
                $isPar = $isSon = $isupck = true;
                $sh_name=C('CKname').'.dbo.'.$wid;
                $this->model->startTrans();

                $isSon = $this->model->table("RS_ProductOutWarehouseList")->where("OutWarehouseId='" . $id . "'")->setField("LastUpdateDate", $nowTime);
                // 更新出库仓库的库存数量！

                if($type=="0" || $type=="2")
                {
                    $upstockSqls="UPDATE ".$sh_name." SET StockCount=StockCount-b.Count,VirtualCount=VirtualCount-b.Count,OutCount+b.Count FROM RS_ProductOutWarehouseList b WHERE b.ProIdCard=".$sh_name.".ProIdCard AND b.OutWarehouseId=('".$id."')";
                }
                else
                {
                    $upstockSqls="UPDATE ".$sh_name." SET StockCount=StockCount-b.Count,VirtualCount=VirtualCount-b.Count,TuiCount+b.Count FROM RS_ProductOutWarehouseList b WHERE b.ProIdCard=".$sh_name.".ProIdCard AND b.OutWarehouseId=('".$id."')";
                }

                $upstockSqls = "";  //???
                // $this->LOGS($upstockSqls);
                $isupck = $this->model->execute($upstockSqls);

                $isPar = $this->model->table("RS_ProductOutWarehouse")->where("OutWarehouseId='" . $id . "'")->save($updataData);

                if ($isPar != false && $isSon != false && $isupck != false) {

                    // 新建调拨入库单据
                    if(trim($_POST["type"])=="0")
                    {
                        $addinparSql=<<<TAB
INSERT INTO RS_ProductInWarehouse (InWarehouseId,InWarehouseNumber,
Count,Money,Status,Date,InputId,InputName,HandleId,HandleName,Type,Remarks,InStorehouseId,InStorehouseName)
SELECT TOP 1 'RK'+'{$id}','',({$updataData["Count"]}),({$updataData["Money"]}),0,'{$nowTime}',('{$updataData["OutputId"]}'),('{$updataData["OutputName"]}'),('{$this->handleId}'),('$this->handleName'),
'1',('该单据由'+OutStorehouseName+'调拨过来，货物到达后，请在入库管理中及时入库！'),InStorehouseId,InStorehouseName FROM RS_ProductOutWarehouse WHERE OutWarehouseId='{$id}'
TAB;
                        $isPar=$this->model->execute($addinparSql);
                        $addinsonSql=<<<TAB
INSERT INTO RS_ProductInWarehouseList(InWarehouseId,ProId,ProIdCard,ClassId,Price,Count,Money,Remarks,Supplier) SELECT 'RK'+'{$id}',ProId,ProIdCard,ClassId,Price,Count,Money,'调拨','' FROM RS_ProductOutWarehouseList WHERE OutWarehouseId='{$id}'
TAB;
                        $isSon=$this->model->execute($addinsonSql);
                        // var_dump($isPar,$)
                        if($isPar != false && $isSon != false)
                        {
                            $this->model->commit();
                            echo "{\"code\":\"0\"}";
                        }
                        else {
                            $this->model->rollback();
                            echo "{\"code\":\"1\"}";
                        }
                    }
                    else{
                        $this->model->commit();
                        echo "{\"code\":\"0\"}";
                    }

                } else {
                    $this->model->rollback();
                    echo "{\"code\":\"2\"}";
                }
            } else if ($ty == "del_out_warehouse") {
                // 删除单据
                $id = trim($_POST["id"]);  // 获取编号
                $this->model->startTrans();
                $isSon = $this->model->table("RS_ProductOutWarehouseList")->where("OutWarehouseId='" . $id . "'")->delete();
                $isPar = $this->model->table("RS_ProductOutWarehouse")->where("OutWarehouseId='" . $id . "'")->delete();
                if ($isPar != false) {
                    $this->model->commit();
                    echo "{\"code\":\"0\"}";
                } else {
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }
            } else if ($ty == "del_pro_out_warehouse") {
                // 删除商品单据
                $id = trim($_POST["id"]);  // 获取编号
                $this->model->startTrans();
                $attrs = $_POST["attr"];
                foreach ($attrs as $val) {
                    $isSon = $this->model->table("RS_ProductOutWarehouseList")->where(array('OutWarehouseId' => $id, "ProIdCard" => $val["card"]))->delete();
                    if (!$isSon) {
                        break;
                    }
                }

                $nowtime=date("Y-m-d H:i:s", time());

                // 此处合计数字
                $upParSqls = <<<TBL
UPDATE RS_ProductOutWarehouse SET Count=b.Count,Money=b.Money,LastUpdateDate=('{$nowtime}'),HandleId=('{$this->handleId}'),HandleName=('{$this->handleName}')
FROM (SELECT '{$id}' AS OutWarehouseId,ISNULL(SUM(Count),0) AS Count,ISNULL(SUM(Money),0) AS Money
    FROM RS_ProductOutWarehouseList WHERE OutWarehouseId=('{$id}')) b
WHERE RS_ProductOutWarehouse.OutWarehouseId=b.OutWarehouseId
TBL;
                $isupMoney=$this->model->execute($upParSqls);

                if ($isSon != false && $isupMoney!=false) {
                    $this->model->commit();
                    echo "{\"code\":\"0\"}";
                } else {
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }
            } else {
                echo "{\"code\":\"1\"}";
            }
        } else {
            echo "{\"code\":\"1\"}";
        }
    }

    // + -------------------------------------------------------------
    // + 商品出库功能区域 结束
    // + -------------------------------------------------------------
    // + -------------------------------------------------------------
    // + 商品盘点功能区域 开始
    // + -------------------------------------------------------------

    /**
     * 盘存单录入
    */
    public function inventory()
    {
        if (isset($_GET["cid"])) {
            $cid = trim($_GET["cid"]);   // 单据编号
            $data=$this->model->query("SELECT TOP 1 InventoryId,CONVERT(varchar(100), Date, 20) AS Date,InputID,StorehouseId,Remarks FROM RS_ProductInventory WHERE Status=0 AND InventoryId=('".$cid."')");
            if(count($data)){
                $this->assign("cid",$cid)->assign("data",$data)->assign("dataPro",$this->getInventoryProInfo($cid));
            }
        }
        $Employee=$this->getEmployee();
        $Warehouse = $this->getWarehouselist();
        $this->assign("employee",$Employee)->assign("warehouseList", $Warehouse)->display();
    }

    /**
     * 提取出库草稿界面
     */
    public function showInventoryDraft()
    {
        if (IS_GET) {
            $p["w"] = trim($_GET["w"]);
            $p["dateStart"] = date('Y-m-d', strtotime('-7 day'));
            $p["dateEnd"] = date('Y-m-d', time());
        }

        if (IS_POST) {
            $p["w"] = trim($_POST["warehouse"]);
            $p["dateStart"] = trim($_POST["dateStart"]);
            $p["dateEnd"] = trim($_POST["dateEnd"]);
        }

        $data = $this->model->query("SELECT InventoryId,CONVERT(varchar(100), Date, 120) AS Date,InputName,Remarks FROM RS_ProductInventory
 WHERE StorehouseId=('" . $p["w"] . "') AND Status=0 AND Date BETWEEN ('" . $p["dateStart"] . "') AND ('" . $p["dateEnd"] . "') ORDER BY Date");

        $this->assign("p", $p)->assign("data", $data)->display();
    }

    /**
     * 盘存单管理
     */
    public function inventorylist()
    {
        $ClassType = $this->getProClass();
        $Warehouse = $this->getWarehouselist();
        $datanum=$this->model->query("SELECT ISNULL(COUNT(InventoryId),0) as count FROM RS_ProductInventory");
        $num=intval($datanum[0]["count"]);
        if($num<200)
        {
            $this->assign("num",$num);
            $this->assign("dataiddate",json_encode($this->getInventoryDateByWid("",0)));
        }
        else
        {
            $this->assign("dataiddate",$this->getInventoryDateByWid(trim($Warehouse[0]["WarehouseCard"]),1));
        }

        $this->assign("datatime",array("dateStart"=>date('Y-m-d', strtotime('-30 day')),"dateEnd"=>date('Y-m-d', strtotime('+1 day'))))
            ->assign("typeList", $ClassType)
            ->assign("warehouseList", $Warehouse)
            ->display();
    }

    /*
     * 根据单据编号 获取信息
    */
    public function getInventoryList()
    {
        if (IS_POST) {
            $ty = trim($_POST["ty"]);
            if ($ty == "get_list") {
                $id = trim($_POST["id"]);
                $par = $this->model->query("SELECT TOP 1 '0' AS 'code', InventoryId,CONVERT(varchar(100), Date, 120) AS Date,(CASE WHEN Status=0 THEN '草稿' ELSE '已提交' END) AS Status,Count,InputName,Remarks,StorehouseName FROM RS_ProductInventory WHERE InventoryId='" . $id . "'");
                if ($par != false) {
                    $par["datason"] = $this->getInventoryProInfo($id);
                    echo "{\"code\":\"0\",\"datapar\":" . json_encode($par) . "}";
                } else {
                    echo "{\"code\":\"1\"}";
                }
            } else if ($ty == "get_parlist") {
                echo json_encode($this->getInventoryDateByWid(trim($_POST["wid"]), 1));
            } else {
                echo "{\"code\":\"1\"}";
            }
        }
        else if(IS_GET) {
            $ty=trim($_GET["ty"]);
            if($ty=="export"){
                $id=trim($_GET["id"]);
                $sql="SELECT a.InventoryId,CONVERT(varchar(100), a.Date, 120) AS Date,(CASE WHEN a.Status=0 THEN '草稿' ELSE '已提交' END) AS Status,
                a.Count,a.InputName,a.Remarks,a.StorehouseName,d.ClassName,d.ProName,c.ProIdInputCard,
c.ProSpec1 + (CASE WHEN ISNULL(c.ProSpec2,'')='' THEN '' ELSE '/'+c.ProSpec2 END) +
    (CASE WHEN ISNULL(c.ProSpec3,'')='' THEN '' ELSE '/'+c.ProSpec3 END) +
    (CASE WHEN ISNULL(c.ProSpec4,'')='' THEN '' ELSE '/'+c.ProSpec4 END) +
    (CASE WHEN ISNULL(c.ProSpec5,'')='' THEN '' ELSE '/'+c.ProSpec5 END) AS Spec,
(CASE WHEN d.IsShelves=0 THEN '已下架' ELSE '已上架' END) AS IsShelves,
b.BookCount,b.ActualCount,b.CountPoor
FROM RS_ProductInventory a
LEFT JOIN RS_ProductInventoryList b ON a.InventoryId=b.InventoryId
LEFT JOIN RS_ProductList c ON b.ProIdCard=c.ProIdCard
LEFT JOIN RS_Product d ON d.ProId=b.ProId
WHERE a.InventoryId=('".$id."') ORDER BY d.ClassType,b.ProIdCard";
                $xlsName="inventory_".date('ymdHm');
                $xlsCell = array(
                    array('InventoryId','盘点单号号'),
                    array('Date' , '盘点日期'),
                    array('Status' , '状态'),
                    array('Count' , '盘点商品总数量'),
                    array('InputName' ,'盘点人'),
                    array('Remarks' , '备注说明'),
                    array('StorehouseName' , '盘点仓库'),
                    array('ClassName' , '商品上级类别'),
                    array('ProName' , '商品名称'),
                    array('ProIdInputCard' , '规格编码'),
                    array('Spec' , '规格属性'),
                    array('IsShelves' , '当前是否上架'),
                    array('BookCount' , '账面数'),
                    array('ActualCount' , '实盘数'),
                    array('CountPoor' , '盘存差')
                );
                exportExcel($xlsName,$xlsCell,$this->model->query($sql));
            }
        }
        else {
            echo "{\"code\":\"1\"}";
        }
    }

    /**
     * 公共数据 获取盘点商品信息
     * @param string $cid 仓库编号
     * @param int $type 查询类型
    */
    private function getInventoryDateByWid($wid,$type=0)
    {
        if($type==0)
        {
            return $this->model->query("SELECT InventoryId,CONVERT(varchar(100), Date, 120) AS Date,(CASE WHEN Status=0 THEN '草稿' ELSE '已提交' END) AS Status,StorehouseId
 FROM RS_ProductInventory ORDER BY Date DESC");
        }
        else if($type==1)
        {
            return $this->model->query("SELECT InventoryId,CONVERT(varchar(100), Date, 120) AS Date,(CASE WHEN Status=0 THEN '草稿' ELSE '已提交' END) AS Status FROM RS_ProductInventory WHERE StorehouseId='".$wid."' ORDER BY Date DESC");
        }
    }

    /**
     * 商品盘点 添加/更新/删除 保存
     */
    public function saveInventory()
    {
        if (IS_POST) {
            $ty = $_POST["ty"];
             if ($ty == "new_inventory") {
                // 生成唯一编号
                $id = $this->setOddNumber("PC");
                $riqi=trim($_POST["riqi"]);
                $wid=trim($_POST["wid"]);
                // 主表数据
                $parData["InventoryId"] = $id;
                $parData["InventoryNumber"]='';
                $parData["Status"] = 0;
                $parData["Date"] = $riqi;

                $parData["InputId"] = trim($_POST["inputid"]);
                $parData["InputName"] = trim(htmlspecialchars($_POST["inputname"]));

                $parData["Count"]=0;

                $parData["HandleId"] = $this->handleId;
                $parData["HandleName"] = $this->handleName;

                $parData["Type"] = '0';
                $parData["StorehouseId"] = $wid;
                $parData["StorehouseName"] = trim($_POST["wname"]);
                // 开始数据存储
                // 开启事务
                $this->model->startTrans();
                $isPar = $isSon = true;

                // 插入子表
                $tpdata=$this->SH($wid)->select();
                foreach ($tpdata as $tp) {
                  $tempPidata['InventoryId']=$id;
                  $tempPidata['ProId']=$tp['ProId'];
                  $tempPidata['ProIdCard']=$tp['ProIdCard'];
                  $tempPidata['BookCount']=$tp['StockCount'];
                  $tempPidata['ActualCount']=$tp['StockCount'];
                  $tempPidata['CountPoor']=0;
                  $tempPidata['Date']=$riqi;
                  $tempPidata['IsMark']=0;
                  $tempPidata['Remarks']='';
                  if ($this->model->table('RS_ProductInventoryList')->add($tempPidata)) {
                    $tempPidata=array();
                  }else{
                    $isSon=false;
                    break;
                  }
                }
//                 $sql=<<<TAB
// INSERT INTO RS_ProductInventoryList(InventoryId,ProId,ProIdCard,BookCount,ActualCount,CountPoor,Date,IsMark,Remarks) SELECT ('{$id}'),ProId,ProIdCard,StockCount,StockCount,0,'{$riqi}',0,'' FROM RS_Warehouse_{$wid}
// TAB;
//                 $isSon=$this->model->execute($sql);
                // 插入主表
                $isPar = $this->model->table("RS_ProductInventory")->add($parData);
                // var_dump($isPar,$isSon);
                if ($isPar != false && $isSon != false) {
                    $this->model->commit();
                    echo "{\"code\":\"0\",\"InventoryId\":\"" . $id . "\",\"dataPro\":" . json_encode($this->getInventoryProInfo($id)) . "}";
                } else {
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }
            } else if ($ty == "ok_inventory") {

                // 提交单据
                $id = trim($_POST["id"]);  // 获取编号
                $wid = trim($_POST["wid"]);   // 仓库编号
                $getNumMoney = $this->model->query("SELECT ISNULL(SUM(ActualCount),0) AS count FROM RS_ProductInventoryList
 WHERE InventoryId=('" . $id . "')");

                $nowTime = date("Y-m-d H:i:s", time());
                $updataData = array(
                    'Count' => intval($getNumMoney[0]["count"]),
                    'Status' => 1,
                    'InputId' => $_POST["inputid"],
                    'InputName' => htmlspecialchars($_POST["inputname"]),
                    'HandleId' => $this->handleId,
                    'HandleName' => $this->handleName,
                    'Remarks' => trim(htmlspecialchars($_POST["remarks"])),
                    'LastUpdateDate' => $nowTime
                );
                $isPar = $isSon = $isupck = true;
                $this->model->startTrans();
                $this->SH()->startTrans();  //仓库数据库事务
                // 更新子表最后操作时间
                $isSon = $this->model->table("RS_ProductInventoryList")->where("InventoryId='" . $id . "'")->setField("LastUpdateDate", $nowTime);
                // 根据盘存差，更新库存数量   //查询语句待修改  //已修改

                $ProIdCards=$this->model->table("RS_ProductInventoryList")->where("InventoryId='%s'",$id)->getField('ProIdCard',true);
                $CountPoors=$this->model->table("RS_ProductInventoryList")->where("InventoryId='%s'",$id)->getField('CountPoor',true);
                foreach ($ProIdCards as $pk => $pv) {
                  if (!$this->SH()->execute("UPDATE tb_".$wid." SET StockCount=StockCount-".$CountPoors[$pk].",VirtualCount=VirtualCount-".$CountPoors[$pk]." WHERE ProIdCard='".$pv."'")) {
                    $isupck=false;
                    break;
                  }
                }
//                 $upstockSqls = <<<TBL
//  UPDATE RS_Warehouse_{$wid} SET StockCount=StockCount-b.CountPoor,VirtualCount=VirtualCount-b.CountPoor FROM RS_ProductInventoryList b WHERE b.ProIdCard=RS_Warehouse_{$wid}.ProIdCard AND b.InventoryId=('{$id}')
// TBL;
//                 $isupck = $this->model->execute($upstockSqls);
                // 更新主表数据
                $isPar = $this->model->table("RS_ProductInventory")->where("InventoryId='" . $id . "'")->save($updataData);
                // echo $this->model->getlastsql();
                // exit;
                if ($isPar != false && $isSon != false && $isupck != false) {
                    $this->SH()->commit();
                    $this->model->commit();
                    echo "{\"code\":\"0\"}";
                } else {
                    $this->SH()->rollback();
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }
            } else if ($ty == "del_inventory") {
                // 删除单据
                $id = trim($_POST["id"]);  // 获取编号
                $this->model->startTrans();
                $isSon = $this->model->table("RS_ProductInventoryList")->where("InventoryId='" . $id . "'")->delete();
                $isPar = $this->model->table("RS_ProductInventory")->where("InventoryId='" . $id . "'")->delete();
                if ($isPar != false) {
                    $this->model->commit();
                    echo "{\"code\":\"0\"}";
                } else {
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }
            } else if($ty=="up_pro_inventory"){

                $id = trim($_POST["cid"]);  // 获取编号
                $pid=trim($_POST["pid"]);   // 商品规格编号
                $num=floatval(trim(($_POST["num"])));   // 账面数量
                $anum=floatval(trim($_POST["value"]));   // 实盘数量
                $pnum=$num-$anum;   // 盘存差

                $isSon=$this->model->execute("UPDATE RS_ProductInventoryList SET ActualCount=".$anum.",CountPoor=".$pnum.",Date=GETDATE(),LastUpdateDate=GETDATE() WHERE ProIdCard='".$pid."' AND InventoryId='".$id."'");
                if($isSon!=false){
                    echo "{\"code\":\"0\",\"anum\":".$anum.",\"pnum\":".$pnum.",\"date\":\"".date("Y-m-d H:i:s", time())."\"}";
                }
                else{
                    echo "{\"code\":\"1\"}";
                }
            }
            else {
                echo "{\"code\":\"1\"}";
            }
        } else {
            echo "{\"code\":\"1\"}";
        }
    }

    /**
     * 公共数据 获取盘点商品信息
     * @param string $cid 盘点单号
    */
    private function getInventoryProInfo($cid)
    {
        $sql=<<<TAB
SELECT ROW_NUMBER() OVER (ORDER BY d.ClassType,b.ProIdCard ASC) AS RowNumber,b.ProIdCard,d.ClassName,d.ProName,c.ProIdInputCard,
c.ProSpec1 + (CASE WHEN ISNULL(c.ProSpec2,'')='' THEN '' ELSE '/'+c.ProSpec2 END) +
    (CASE WHEN ISNULL(c.ProSpec3,'')='' THEN '' ELSE '/'+c.ProSpec3 END) +
    (CASE WHEN ISNULL(c.ProSpec4,'')='' THEN '' ELSE '/'+c.ProSpec4 END) +
    (CASE WHEN ISNULL(c.ProSpec5,'')='' THEN '' ELSE '/'+c.ProSpec5 END) AS Spec,
(CASE WHEN d.IsShelves=0 THEN '已下架' ELSE '已上架' END) AS IsShelves,
b.BookCount,b.ActualCount,b.CountPoor,b.Remarks,b.IsMark,CONVERT(varchar(100), b.Date, 20) AS Date
FROM RS_ProductInventoryList b
LEFT JOIN RS_ProductList c ON b.ProIdCard=c.ProIdCard
LEFT JOIN RS_Product d ON d.ProId=b.ProId
WHERE b.InventoryId=('{$cid}') and d.stoken='{$this->stoken}'
ORDER BY d.ClassType,b.ProIdCard
TAB;
// var_dump($sql);
        return $this->model->query($sql);
    }

    // + -------------------------------------------------------------
    // + 商品盘点功能区域 结束
    // + -------------------------------------------------------------
    // + -------------------------------------------------------------
    // + 供货商库存查询 开始
    // + -------------------------------------------------------------

    public function supplierList()
    {
        if (IS_GET) {
            $this->assign('stime',date('Y-m-d',strtotime('-30 day')))->assign("etime",date('Y-m-d',strtotime('-1 day')));
            $this->assign("warehouseList", $this->getWarehouselist())->assign("supplierlist",$this->getSupplier())->display();
        }
    }

    public function get_supplierBypage()
    {
        if(IS_POST)
        {
            $pagesize=25;
            $wid=trim(htmlspecialchars($_POST["wid"]));
            $spname=trim(htmlspecialchars($_POST["supplier"]));
            $start_time=trim(htmlspecialchars($_POST["stime"])) + " 00:00:00";// 开始时间
            $end_time=trim(htmlspecialchars($_POST["etime"])) + " 23:59:59";    // 结束时间
            $proname=trim(htmlspecialchars($_POST["proname"]));    // 结束时间
            $pageindex=intval($_POST["pindex"]); // 页码
            $order='ASC';
            $datacount=$this->model->setIsProc(true)->query($this->getsuppliersqls(0,$spname,$start_time,$end_time,$pagesize,$pageindex,$order,$wid,$proname));
            $count=intval($datacount[0]["rows"]);   // 获取查询条数
            if($count>0)
            {
                $data=$this->model->setIsProc(true)->query($this->getsuppliersqls(1,$spname,$start_time,$end_time,$pagesize,$pageindex,$order,$wid,$proname));
                $totalPages = ceil($count / $pagesize); //总页数
                echo "{\"code\":\"0\",\"pageCount\":".$count.",\"totalPage\":".$totalPages.",\"dataPro\":".json_encode($data)."}";
            }
            else
            {
                echo "{\"code\":\"1\"}";
            }
        }
    }


    // 首页存储调用
    private function getsuppliersqls($getTotal,$spname,$start_time='',$end_time='',$pagesize=25,$pageindex=1,$order='DESC',$wid="zb",$proname=""){
        // 查询 记录总数:@getTotal=0
        // 查询 分页数据：1
        // 查询 导出数据: 2

        $sql=<<<TBL
DECLARE @return_value int
EXEC    @return_value = P_SupplierList
        @start_time = N'{$start_time}',
        @end_time = N'{$end_time}',
        @spname = N'{$spname}',
        @wid = N'{$wid}',
        @proname = N'{$proname}',
        @pagesize = {$pagesize},
        @pageindex = {$pageindex},
        @getTotal = {$getTotal},
        @order = N'{$order}'
TBL;
        return $sql;
    }

    // + -------------------------------------------------------------
    // + 供货商库存查询 结束
    // + -------------------------------------------------------------
    // + -------------------------------------------------------------
    // + 公共数据调取区 开始
    // + -------------------------------------------------------------

    /**
     * 公共数据获取商品
     */
    public function getproInfo()
    {
        $selectType = trim($_POST["c"]);
        $type = trim($_POST["t"]);
        if ($selectType == "get_proname") {

            $proinfo = $this->model->query("SELECT ClassType AS ty,ProName AS na,ProId AS id FROM RS_Product WHERE ClassType=('" . $type . "') ORDER BY ClassType,ProId");
            echo json_encode($proinfo);
            exit;
        } else if ($selectType == "get_prolist") {

            $warehouseindex = C('CKname').'.dbo.tb_'.trim($_POST["w"]);
            $ty=isset($_POST["ty"])?trim($_POST["ty"]):"";
            $tempnum="";
            if($ty=="out")
            {
                $tempnum=" AND  b.StockCount>0 ";
            }
            $proinfo = $this->model->query("
SELECT
    a.ProId AS id,
    a.ProIdCard AS card,
    a.ProIdInputCard AS incard,
    a.ProSpec1 + (CASE WHEN ISNULL(a.ProSpec2,'')='' THEN '' ELSE '/'+a.ProSpec2 END) +
    (CASE WHEN ISNULL(a.ProSpec3,'')='' THEN '' ELSE '/'+a.ProSpec3 END) +
    (CASE WHEN ISNULL(a.ProSpec4,'')='' THEN '' ELSE '/'+a.ProSpec4 END) +
    (CASE WHEN ISNULL(a.ProSpec5,'')='' THEN '' ELSE '/'+a.ProSpec5 END) AS spec,
    b.StockCount AS num
FROM
    RS_ProductList a
    LEFT JOIN  " . $warehouseindex . " b ON a.ProIdCard = b.ProIdCard
    WHERE a.ProId=('" . $type . "') ".$tempnum."
    ORDER BY
    a.ProId ,
    a.ProIdCard");  //查询

        }
        // echo M()->getlastsql();
        echo json_encode($proinfo);
    }

    // 获取将 出入库商品信息
    public function getProListByType($warehouseCard,$type,$ioid="")
    {
        $temp="";

        if($ioid!="")
        {
            $temp=" AND a.ProIdCard NOT IN (SELECT ProIdCard FROM RS_Product".$type."WarehouseList WHERE ".$type."WarehouseId='".$ioid."')";
        }

        if($type=="Out")
        {
            $temp.=" AND b.StockCount>0";
        }

        $sql=<<<TBL
select p.ProName,p.ClassType,a.ProId AS id,
    a.ProIdCard AS card,
    a.ProIdInputCard AS incard,
    a.ProSpec1 + (CASE WHEN ISNULL(a.ProSpec2,'')='' THEN '' ELSE '/'+a.ProSpec2 END) +
    (CASE WHEN ISNULL(a.ProSpec3,'')='' THEN '' ELSE '/'+a.ProSpec3 END) +
    (CASE WHEN ISNULL(a.ProSpec4,'')='' THEN '' ELSE '/'+a.ProSpec4 END) +
    (CASE WHEN ISNULL(a.ProSpec5,'')='' THEN '' ELSE '/'+a.ProSpec5 END) AS spec,
    b.StockCount AS num
    FROM RS_Product p
    left join RS_ProductList a on p.ProId=a.ProId
    LEFT JOIN RS_Warehouse_{$warehouseCard} b ON a.ProIdCard = b.ProIdCard
    WHERE a.IsDelete=0 {$temp}
    ORDER BY p.ClassType,p.ProName,a.ProIdCard
TBL;

        $proinfo=$this->model->query($sql);
        return "{\"prodata\":" . json_encode($proinfo) . "}";
    }

    /**
     * 公共数据 获取 商品和规格信息 只限于 商品规格数量小于200的时候！
     * @param string $warehouseCard 仓库表索引
     * @param string $type 类型 out/in
    */
    public function getWarehouseProList($warehouseCard,$type,$tps)
    {
        // 商品信息
        if ($tps=='4') {
          $mainWare='wh'.substr($this->token, -8,8);
          $pram="token='".$this->token."' and stoken='0'";
          $prodata = $this->model->query("SELECT ClassType AS ty,ProName AS na,ProId AS id FROM RS_Product WHERE ".$pram." ORDER BY ClassType,ProId");
          foreach ($prodata as $p) {
            $pids[]=$p['id'];
          }
          $wareinfos=$this->model->table('RS_Productlist')->where("ProId in ('".implode("','",$pids)."')")->getField('ProIdCard',true);
          // echo M()->getlastsql();
          // var_dump($wareinfos);exit;

          if ($type=='out') {
              $wareinfos=$this->SH($warehouseCard)->where("IsDelete=0 and StockCount>0")->getField('ProIdCard',true);
              $StockCounts=$this->SH($warehouseCard)->where("IsDelete=0 and StockCount>0")->getField('StockCount',true);
          }else{
              $wareinfos=$wareinfos;
              $StockCounts=$this->SH($mainWare)->where("IsDelete=0")->getField('ProIdCard,StockCount');
          }
        }else{
          $pram="token='".$this->token."' and stoken='".$this->stoken."'";
          $prodata = $this->model->query("SELECT ClassType AS ty,ProName AS na,ProId AS id FROM RS_Product WHERE ".$pram." ORDER BY ClassType,ProId");
          // var_dump($prodata);exit();
          if ($type=='out') {
              $wareinfos=$this->SH($warehouseCard)->where("IsDelete=0 and StockCount>0")->getField('ProIdCard',true);
              $StockCounts=$this->SH($warehouseCard)->where("IsDelete=0 and StockCount>0")->getField('StockCount',true);
          }else{
              $wareinfos=$this->SH($warehouseCard)->where("IsDelete=0")->getField('ProIdCard',true);
              $StockCounts=$this->SH($warehouseCard)->where("IsDelete=0")->getField('StockCount',true);
          }
        }
        // var_dump($StockCounts);exit();
        // 商品规格信息
        foreach ($wareinfos as $wk => $wv) {
            $tempinfo=$this->model->query("
SELECT
    ProId AS id,
    ProIdCard AS card,
    ProIdInputCard AS incard,
    ProSpec1 + (CASE WHEN ISNULL(ProSpec2,'')='' THEN '' ELSE '/'+ProSpec2 END) +
    (CASE WHEN ISNULL(ProSpec3,'')='' THEN '' ELSE '/'+ProSpec3 END) +
    (CASE WHEN ISNULL(ProSpec4,'')='' THEN '' ELSE '/'+ProSpec4 END) +
    (CASE WHEN ISNULL(ProSpec5,'')='' THEN '' ELSE '/'+ProSpec5 END) AS spec
FROM
    RS_ProductList WHERE ProIdCard='".$wv."' and IsDelete=0
    ORDER BY
    ProId,
    ProIdCard");
            // echo M()->getlastsql();
            // var_dump($tpinfo);exit();
            $tpinfo=$tempinfo[0];
            $tpinfo['num']=$StockCounts[$wk]?$StockCounts[$wk]:0;
            $proinfo[]=$tpinfo;
        }
        // var_dump($proinfo);exit;

//         $proinfo = $this->model->query("
// SELECT
//     a.ProId AS id,
//     a.ProIdCard AS card,
//     a.ProIdInputCard AS incard,
//     a.ProSpec1 + (CASE WHEN ISNULL(a.ProSpec2,'')='' THEN '' ELSE '/'+a.ProSpec2 END) +
//     (CASE WHEN ISNULL(a.ProSpec3,'')='' THEN '' ELSE '/'+a.ProSpec3 END) +
//     (CASE WHEN ISNULL(a.ProSpec4,'')='' THEN '' ELSE '/'+a.ProSpec4 END) +
//     (CASE WHEN ISNULL(a.ProSpec5,'')='' THEN '' ELSE '/'+a.ProSpec5 END) AS spec,
//     b.StockCount AS num
// FROM
//     RS_ProductList a
//     LEFT JOIN RS_Warehouse_" . $warehouseCard . " b ON a.ProIdCard = b.ProIdCard
//     where a.IsDelete=0
//     ".($type=="out"?" and b.StockCount>0":"")."
//     ORDER BY
//     a.ProId,
//     a.ProIdCard");
        return "{\"prodata\":" . json_encode($prodata) . ",\"data\":" . json_encode($proinfo) . "}";
    }

    /**
     * 公共数据 获取 仓库列表
     */
    public function getWarehouselist()
    {
        $mainWare='wh'.substr($this->token, -8,8);
        $stores=$this->model->table("RS_Store")->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->getField('id',true);
        // var_dump(M()->getlastsql());exit();
        $names=$this->model->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->getField('storename',true);
        foreach ($stores as $sk => $sv) {
            $tmp['WarehouseName']=$names[$sk];
            $tmp['WarehouseCard']=$mainWare.'_'.$sv;
            $Warehouselist[]=$tmp;
        }
        // var_dump($Warehouselist);exit();
        // return $this->model->query("SELECT WarehouseCard,WarehouseName FROM RS_WarehouseList WHERE IsDelete=0 ORDER BY Sort");
        return $Warehouselist;
    }

    /**
     * 公共数据 获取 商品类别
     */
    public function getProClass()
    {
        return $this->model->query("SELECT ClassId, (CASE WHEN ClassGrade=2 THEN '---'+ClassName ELSE ClassName END) AS ClassName,ClassSort FROM RS_ProductClass WHERE token='".$this->token."' AND IsVisible=1 ORDER BY ClassSort");
    }

    /**
     * 公共数据 获取 供应商
     * @param string $type 类型
    */
    public function getSupplier($type="")
    {
        if($type=="name")
        {
            return $this->model->query("SELECT Supplier AS name FROM RS_Supplier WHERE Token='".$this->token."' ORDER BY ID");
        }
        else
        {
            return $this->model->query("SELECT ID ,Supplier AS name FROM RS_Supplier WHERE Token='".$this->token."' ORDER BY ID");
        }
    }

    /**
     * 公共数据 获取 供应商
     * @param string $val 比对数据
     * @param string $key $val的键名
     * @param array  $sondata 比对数据集合
    */
    private function filterArraySon($val,$key,$sondata)
    {
        foreach ($sondata as $value) {
            if ($val==$value[$key]) {
                $returns[]=$value;
            }
        }
        return $returns;
    }

    /**
     * 公共数据 生成 单据编号
     * @param string $type 单据类型
    */
    private function setOddNumber($type){
        $alpha_numeric = 'abcdefghijklmnopqrstuvwxyz01234567890';
        // 生成唯一编号
        return $type . date('Ymd') . substr(str_shuffle($alpha_numeric), 0, 2) . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 5);
    }

    // 获取员工信息
    private function getEmployee(){
        return $this->MSL()->query("SELECT id,TrueName AS Name FROM tb_user WHERE token='".$this->token."' and stoken='".$this->stoken."' order by TrueName,DepartmentName");
        // var_dump(M()->getlastsql());exit();
    }

    // + -------------------------------------------------------------
    // + 公共数据调取区 结束
    // + -------------------------------------------------------------
    /**
     * 自动处理入库申请反馈单
     */
    public function autoinw(){
        // var_dump($_GET);
        $thisware=$this->mainWare.'_'.session('userinfo')['userShop'];
        $InvID=$_GET['Ivid'];  //入库单号
        $InInfo=$this->model->table('RS_ProductInWarehouse')->where("token='%s' and WarehouseId='%s'",array($this->token,$InvID))->find();
        $InSinfo=$this->model->table('RS_ProductInWarehouseList')->where("token='%s' and InWarehouseId='%s'",array($this->token,$InvID))->select();
        if ($InInfo['Status']=='2') {
            $this->error('该单据已经处理，请勿重复操作');
            exit();
        }
        // echo $this->model->getlastsql();
        $this->model->startTrans();
        $this->SH()->startTrans();
        //更新入库单状态
        $inv=$res=true;
        $inv=$this->model->table('RS_ProductInWarehouse')->where("token='%s' and InWarehouseId='%s'",array($this->token,$InvID))->setField('Status','2');
        //更新仓库数据
        // var_dump($InSinfo);exit();
        foreach ($InSinfo as $s) {
            $temDB['ProId']=$s['ProId'];
            $temDB['ProIdCard']=$s['ProIdCard'];
            $temDB['StockCount']=$s['Count'];
            $temDB['LimitCount']=0;
            $temDB['VirtualCount']=$s['Count'];
            $temDB['InCount']=$s['Count'];
            $temDB['SalesCount']=0;
            $temDB['OutCount']=0;
            $temDB['ReturnCount']=0;
            $temDB['IsDelete']=0;
            $temDB['CreateDate']=date('Y-m-d H:i:s',time());
            $temDB['LastUpdateDate']=date('Y-m-d H:i:s',time());
            if ($this->SH($thisware)->where("ProIdCard='%s'",$s['ProIdCard'])->find()) {
                if (false===$this->SH()->execute("UPDATE tb_".$thisware." SET StockCount=StockCount+".$s['Count'].",VirtualCount=VirtualCount+".$s['Count'].",InCount=InCount+".$s['Count']." WHERE ProIdCard='".$s['ProIdCard']."'")) {
                    $res=false;
                    break;
                }
            }
        }
        if ($inv && $res) {
            $this->model->commit();
            $this->SH()->commit();
            $this->success('处理成功');
        }else{
            $this->LOGS('$inv->'.$inv.'  __$res->'.$res);
            $this->model->rollback();
            $this->SH()->rollback();
            $this->error('处理失败');
        }
    }
    /**
     * 获取微信支付二维码
     */
    public function getwxpayqr(){
        // $cashData=session('cashData');
        $cid=$_POST['cid'];
        $wareinfo=$this->model->table('RS_ProductInWarehouse')->where("InWarehouseId='%s'",$cid)->find();
        if ($wareinfo['IsPay']=='1') {
            //防止重复处理
            $msg['status']='error';
            $msg['info']='单据已处理';
        }else{
            $oid='RKPAYING'.date('YmdHis',time()).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
            $money=$wareinfo['Money'];
            $ScanParam=array();
            $ScanParam['service']='getCodeUrl';
            $ScanParam['merchantNo']=$this->merchantNo;
            $ScanParam['version']='V1.0';
            $ScanParam['bgUrl']='https://'.$_SERVER['HTTP_HOST'].'/Seller/Base/Invonotify';
            $ScanParam['payChannelCode']='TX_WXZF';
            $ScanParam['orderNo']=$oid;
            $ScanParam['ext1']=$cid;
            // $ScanParam['orderAmount']=1;
            if (floatval($money)*100<=0) {
                $ScanParam['orderAmount']=1;
            }else{
                $ScanParam['orderAmount']=floatval($money)*100;
            }
            $ScanParam['curCode']='CNY';
            $ScanParam['orderTime']=date('YmdHis',time());
            $sign=$this->lvs_sign($ScanParam);
            $ScanParam['sign']=$sign;
            $this->LOGS('用户扫码支付请求信息--->>>'.json_encode($ScanParam));
            $result=$this->postXmlCurl($ScanParam,$this->apiurl);
            $this->LOGS('用户扫码支付响应信息--->>>'.$result);
            $result=json_decode($result,true);
            if ($result['dealCode']=='10000') {
                //二维码获取成功
                $codeUrl=$result['codeUrl'];//支付二维码
                $msg['status']='success';
                $msg['code']=$codeUrl;
            }else{
                //支付失败
                $msg['status']='error';
                $msg['info']=$result;
            }
        }
        echo json_encode($msg);

    }

    /**
     * 获取二维码
     */
    public function getwxqr(){
        ob_clean();
        vendor('PHPQR.phpqrcode');
        $code=$_GET['code'];
        $level="L";
        // $filename='./Uploads/1.png';
        $size=4;
        // \QRcode::png($url,$filename,$level,$size,'2');
        \QRcode::png($code,false,$level,$size,'2');
    }

    /**
     * 查询支付结果
     */
    public function notify(){
        $cid=$_POST['cid'];
        if (M()->table('RS_ProductInWarehouse')->where("InWarehouseId='%s'",$cid)->getField('IsPay')=='1') {
            $msg['status']='success';
        }else{
            $msg['status']='error';
        }
        echo json_encode($msg);
    }






    /**
     * 签名
     */
    private function lvs_sign($data){
        ksort($data);
        $str=urldecode(http_build_query($data));
        $str=$str.$this->key;
        // var_dump($str);exit();
        // $this->LOGS($str);
        $sign=strtoupper(md5($str));
        return $sign;
    }








    /**
     * 以post方式提交xml到对应的接口url
     * 
     * @param string $xml  需要post的xml数据
     * @param string $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    public function postXmlCurl($xml, $url, $second = 30)
    {       
        // var_dump($xml,$url);exit();
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        
        //如果有配置代理这里就设置代理
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        // var_dump($data);exit();
        if($data){
            curl_close($ch);
            $this->LOGS($data);
            return $data;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            if ($error!='0') {
                $res['status']=false;
                $res['info']="curl出错，错误码:$error";
            }else{
                $res='通讯成功，对方无响应';
            }
            return $res;
        }
    }
}
