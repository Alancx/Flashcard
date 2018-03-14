<?php
namespace Seller\Controller;
use \Think\Controller;
use Org\AliExp\AliExp_Api;
/**
 * 订单管理 查询
 */
class OrderController extends CommonController
{
    public $model;
    public $ckname;
    public function _initialize(){
        parent::_initialize();
        $this->model = M();
        $this->ckname= 'wh'.substr($this->token, -8,8).'_'.session('userinfo')['userShop'];
        // var_dump($this->token);
    }

    // + -------------------------------------------------------------
    // + 订单概况 开始
    // + -------------------------------------------------------------
    public function index(){

        if(IS_POST){
            $start_time=trim($_POST["start_time"]);
            $end_time=trim($_POST["end_time"]);
            // 获取日期直接天数
            $dayscount=round((strtotime($end_time)-strtotime($start_time))/3600/24);
            for ($i=0; $i <= $dayscount ; $i++) {
                $temp=date('Y-m-d',strtotime($start_time." + {$i} day"));
                $outdate[$i]=$temp;
                $downdata[$temp]=0;
                $paydata[$temp]=0;
                $senddata[$temp]=0;
            }
            $data=$this->model->setIsProc(true)->query($this->getindexsqls($start_time." 00:00:00",$end_time." 23:59:59",1));

            foreach ($data as $key => $value) {
                $arr=explode("|", $value["Type"]);
                if($arr[0]=="down"){
                    $downdata[$arr[1]]=$value["Rows"];
                }
                else if($arr[0]=="pay"){
                    $paydata[$arr[1]]=$value["Rows"];
                }
                else if($arr[0]=="send"){
                    $senddata[$arr[1]]=$value["Rows"];
                }
            }
            // 输出数据信息
            echo "{\"code\":\"0\",\"date\":\"".implode(",",$outdate)."\",\"downdata\":\"".implode(",",$downdata)."\",\"paydata\":\"".implode(",",$paydata)."\",\"senddata\":\"".implode(",",$senddata)."\"}";
        }
        if(IS_GET){
            for ($i = 0;$i<7;$i++) {
                $temp=date('Y-m-d',strtotime("-".(7-$i)." day"));
                $outdate[$i]="'".$temp."'";
                $downdata[$temp]=0;
                $paydata[$temp]=0;
                $senddata[$temp]=0;
            }
            $start7_time=date('Y-m-d 00:00:00',strtotime('-7 day'));
            $end_time=date('Y-m-d 23:59:59',strtotime('-1 day'));
            $this->assign("start7_time",$start7_time)->assign("start1_time",date('Y-m-d 00:00:00',strtotime('-1 day')))->assign("end_time",$end_time);
            $data=$this->model->setIsProc(true)->query($this->getindexsqls($start7_time,$end_time));
            foreach ($data as $key => $value) {
                if($key>5){
                    $arr=explode("|", $value["Type"]);
                    if($arr[0]=="down"){
                        $downdata[$arr[1]]=$value["Rows"];
                    }
                    else if($arr[0]=="pay"){
                        $paydata[$arr[1]]=$value["Rows"];
                    }
                    else if($arr[0]=="send"){
                        $senddata[$arr[1]]=$value["Rows"];
                    }
                }
                else{
                    $outdata[$key]=$value;
                }
            }

          //   // 百度统计代码
          //   $this->cookie_abcd9_com=tempnam("", "cookie");  // 设置cookie临时文件
          //   $this->baidu_post(C('BAIDU_TONGJI_URL'),C('BAIDU_TONGJI_PWD'));
          //   $this->baidu_post('http://tongji.baidu.com/web/11258907/ajax/post','indicators=pv_count&reportId=1&method=overview/getTimeTrendRpt&siteId=7493934&queryId=');

          // //  $databaidu=json_decode(,true);
          //   print_r($this->baidu_content); //输出，或进行其他操作
          //   exit;

            // http://tongji.baidu.com/web/7493934/ajax/post
            // 7493934



            $this->assign("outdate",$outdate)->assign("downdata",$downdata)->assign("paydata",$paydata)->assign("senddata",$senddata)->assign("options",$outdata)->display();
        }
    }

    // 首页存储调用
    private function getindexsqls($time_start,$time_end,$type=0){
         $sql=<<<TBL
DECLARE @return_value int
EXEC    @return_value = P_Order_index
        @starttime=N'{$time_start}',
        @endtime=N'{$time_end}',
        @type={$type},
        @token=N'{$this->token}',
        @stoken=N'{$this->stoken}'
TBL;
        return $sql;
    }

    // + -------------------------------------------------------------
    // + 订单概况 结束
    // + -------------------------------------------------------------
    // + -------------------------------------------------------------
    // + 订单查询 开始
    // + -------------------------------------------------------------

    // get请求只在页面加载时调用
    // post请求为筛选，切换状态等调用
    // 获取订单信息数据
    public function allOrder()
    {
        $URL=$this->MSL('merchant')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->getField('userUrl');
        $this->assign('URL',$URL);
        if(IS_POST)
        {
            $state=isset($_POST["state"])?intval(trim($_POST["state"])):0;
            $pageindex=intval($_POST["pindex"]); // 页码
            $order = trim($_POST["order"]);
            $ty=trim($_POST["ty"]); // 类型
            if($ty!="all"){
                $order_no=trim(htmlspecialchars($_POST["order_no"]));    // 订单号
                $user_name=trim(htmlspecialchars($_POST["user_name"]));  // 收货人姓名
                $tel=trim(htmlspecialchars($_POST["tel"]));              // 收货人联系方式
                $start_time=trim(htmlspecialchars($_POST["start_time"]));// 开始时间
                $end_time=trim(htmlspecialchars($_POST["end_time"]));    // 结束时间
                $buy_way=trim($_POST["buy_way"]);    // 付款方式
                $datacount = $this->model->setIsProc(true)->query($this->getProcSqls('order',0,$state,$start_time,$end_time,20,$pageindex,$order,$order_no,$user_name,$tel,$buy_way));
            }
            else
            {
                $datacount = $this->model->setIsProc(true)->query($this->getProcSqls('order',0,$state,'','',20,$pageindex,$order));
            }
        }

        if(IS_GET)
        {
            $selecttype="all";
            $type=isset($_GET["type"])?trim($_GET["type"]):"";

            if($type=="index"){
                $this->assign("geturl","index");
            }
            else if($type=="Users.member"){
                $this->assign("geturl","Users/member");
                $selecttype="select";
            }

            $username=isset($_GET["username"])?trim($_GET["username"]):"";
            $order_no=isset($_GET["oid"])?trim($_GET["oid"]):"";
            if ($order_no!='') {
                $this->assign('order_no',$order_no);
            }
            if($username!="")
            {
                $this->assign("user_name",$username);
            }

            $start_time=isset($_GET["start_time"])?trim(htmlspecialchars($_GET["start_time"])):"";
            $end_time=isset($_GET["end_time"])?trim(htmlspecialchars($_GET["end_time"])):"";
            $state = isset($_GET["state"])?intval(trim($_GET["state"])):0;

            $datacount = $this->model->setIsProc(true)->query($this->getProcSqls('order',0,$state,$start_time,$end_time,20,1,"DESC",$order_no,$username));
            // var_dump($this->getProcSqls('order',0,$state,$start_time,$end_time,20,1,"DESC",'',$username));exit;
            // var_dump($datacount);exit();
            $count=intval($datacount[0]["rows"]);   // 获取查询条数
            $this->assign("start_time",$start_time)->assign("end_time",$end_time)->assign("state",$state);
        }

        $count=intval($datacount[0]["rows"]);   // 获取查询条数

        if($count>0)
        {
            if(IS_GET)
            {
                $data=$this->model->setIsProc(true)->query($this->getProcSqls('order',1,$state,$start_time,$end_time,20,1,"DESC",$order_no,$username));
                // var_dump($data);exit;
            }
            if(IS_POST)
            {
                if($ty!="all")
                {
                    $data=$this->model->setIsProc(true)->query($this->getProcSqls('order',1,$state,$start_time,$end_time,20,$pageindex,$order,$order_no,$user_name,$tel,$buy_way));
                }
                else
                {
                    $data = $this->model->setIsProc(true)->query($this->getProcSqls('order',1,$state,'','',20,$pageindex,$order));
                }
            }

            $orderids='';
            foreach ($data as $val) {
                $orderids.="'".$val["OrderId"]."',";
            }
            $orderids=substr($orderids,0,strlen($orderids)-1);     // 删除最后一个字符
            $datasonList=$this->getOrderList('order',$orderids);           //  获取子单信息
               // 匹配主单和子单信息
            foreach ($data as &$value) {
                $templist=$this->filterArraySon($value["OrderId"],"OrderId",$datasonList);
                $value["sonCount"]=Count($templist);    // 条数
                $value["datason"]=$templist;    // 商品明细
            }
        }

        $totalPages = ceil($count / 20); //总页数
        //var_dump($data);exit;
        if(IS_GET)
        {
            $this->assign("send",$this->getLogistics())->assign("pageCount",$count)->assign('selecttype',$selecttype)->assign("totalPage",$totalPages)->assign("dataOrder",$data)->display();
        }

        if(IS_POST)
        {
            if($count>0){
                echo "{\"code\":\"0\",\"pageCount\":".$count.",\"totalPage\":".$totalPages.",\"dataOrder\":".json_encode($data)."}";
            }
            else
            {
                echo "{\"code\":\"1\"}";
            }
        }
    }

    /**
     * 获取或设置订单信息
    */
    public function getOrderInfoByno()
    {
        if(IS_POST)
        {
            $order_no=trim($_POST["order_no"]);
            $ty=trim($_POST["ty"]);
            $order_type=M()->table('RS_Order')->where("OrderId='%s'",$order_no)->getField('TransactionId');  //获取订单是否为导入订单
            // 订单明细
            if($ty=="get_info"){
                $sqls=
<<<TBL
SELECT TOP 1 OrderId,MemberId,
RecevingPost,
RecevingProvince+' '+RecevingCity+' ' + RecevingArea + ' '+RecevingAddress+', '+RecevingName+', '+RecevingPhone AS Receving,
Price,Freight,PayName,IsEvaluation,
Status,
MessageBySeller,
MessageByBuy,
Logistics,
LogisticsCom,
LogisticsId,
CONVERT(varchar(100), CreateDate, 120) AS CreateDate,
CONVERT(varchar(100), PayDate, 120) AS PayDate,
CONVERT(varchar(100), ShipDate, 120) AS ShipDate,
CONVERT(varchar(100), GetDate, 120) AS GetDate,
CONVERT(varchar(100), BackMoneyDate, 120) AS BackMoneyDate,
CONVERT(varchar(100), BackMoneyOkDate, 120) AS BackMoneyOkDate,
CONVERT(varchar(100), BackProDate, 120) AS BackProDate,
CONVERT(varchar(100), BackProOkDate, 120) AS BackProOkDate,
CONVERT(varchar(100), ValidDate, 120) AS ValidDate
FROM RS_Order
WHERE OrderId=('{$order_no}')
TBL;
                $data=$this->model->query($sqls);
                $data["list"]=$this->getOrderList("order","'".$order_no."'");
                echo json_encode($data);
            }

            else if($ty=="get_sendinfo"){
                // 订单发货
                $sqls=
    <<<TBL
SELECT TOP 1 OrderId,MemberId,RecevingPost,
RecevingProvince+' '+RecevingCity+' '+RecevingArea+' '+RecevingAddress AS Receving,RecevingName,RecevingPhone,PayName,
Price,Freight,MessageByBuy,CONVERT(varchar(100), CreateDate, 120) AS CreateDate,CONVERT(varchar(100), PayDate, 120) AS PayDate
FROM RS_Order WHERE Status=2 AND OrderId=('{$order_no}')
TBL;
                $data=$this->model->query($sqls);
                if ($order_type=='导入') {
                    $list=$this->getOrderList('order',"'".$order_no."'",true,true);
                }else{
                    $list=$this->getOrderList('order',"'".$order_no."'",true);
                }
                $data["list"]=$list;
                echo json_encode($data);
            }
            else if($ty=="update_sendinfo"){
                // 更新发货
                $nowTime = date("Y-m-d H:i:s", time());
                $dt = array(
                    'Logistics' => htmlspecialchars(trim($_POST["sendname"])),
                    'LogisticsCom'=>htmlspecialchars(trim($_POST["sendnumber"])),
                    'LogisticsId'=>htmlspecialchars(trim($_POST["card"])),
                    'ShipDate'=>$nowTime,
                    'LastUpdateDate'=>$nowTime,
                    'Status'=>3
                );
                $ProIdCards=$this->model->table('RS_OrderList')->where("OrderId='%s'",$order_no)->getField('ProIdCard',true);
                $Counts=$this->model->table('RS_OrderList')->where("OrderId='%s'",$order_no)->getField('Count',true);

                $this->model->startTrans();

                $tb_name='tb_'.$this->ckname;
                // var_dump($tb_name);exit();
                $whs=true;
                $this->SH()->startTrans();
                /**
                 * 原联合查询更新语句  现改为分开查询更新 20160417
                 */
                foreach ($ProIdCards as $pk => $pv) {
                    // var_dump();
                    if (!$this->SH()->execute("UPDATE ".$tb_name ." SET StockCount=StockCount-".$Counts[$pk].",VirtualCount=VirtualCount-".$Counts[$pk].",SalesCount=SalesCount+".$Counts[$pk].",LastUpdateDate=GetDate() WHERE ProIdCard='".$pv."'")) {
                          $whs=false;
                          break;
                      }
                }

                // 更新商品销量 和 数量字段
                $uppro=$this->model->execute("UPDATE RS_Product SET LastUpdateDate=GETDATE(),SalesCount=SalesCount+(SELECT SUM(Count) FROM RS_OrderList WHERE OrderId=('".$order_no."') AND a.ProId=RS_OrderList.ProId) FROM RS_Product a, RS_OrderList b WHERE b.ProId=a.ProId or b.ProId=a.Barcode AND b.OrderId=('".$order_no."')");
                // 更新订单表状态
                $upt=$this->model->table("RS_Order")->where(array('OrderId' =>$order_no ,'Status'=>2))->save($dt);
                if($upt!=false && $uppro!=false && $whs!=false){
                    $this->SH()->commit();
                    $this->model->commit();
                    $oinfo=M()->table('RS_Order')->where("OrderId='%s'",$order_no)->find();
                    $mobiles=$this->model->table('RS_Member')->where("OpenId='%s'",$oinfo['OpenId'])->getField("Phone");
                    $data['mobiles']=$mobiles;
                    $data['content']='您的订单：'.$order_no.'已发货，'.$_POST['sendname'].':'.$_POST['card'];
                    $this->SendMessage($data);
                    echo "{\"code\":\"0\"}";
                }
                else {
                    $this->SH()->rollback();
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }
            }
            else if($ty=="get_message"){
                // 卖家备注
                echo json_encode($this->model->query("SELECT MessageBySeller FROM RS_Order WHERE OrderId=('".$order_no."')"));
            }
            else if($ty=="update_message"){
                // 更新卖家备注
                echo "{\"code\":\"".$this->model->table("RS_Order")->where("OrderId='" . $order_no . "'")->setField("MessageBySeller",htmlspecialchars(trim($_POST["msg"])))."\"}";
            }
            else if($ty=="update_back"){
                $type=trim($_POST["back"]);
                $nowTime = date("Y-m-d H:i:s", time());
                $this->model->startTrans();
                // 更新退货
                if($type=="pro"){
                    $dt = array(
                        'BackProOkDate' =>$nowTime ,
                        'Status'=>7,
                        'LastUpdateDate'=>$nowTime
                    );

                    $upt=$this->model->table("RS_Order")->where(array('OrderId' =>$order_no ,'Status'=>6))->save($dt);
                    // 此处退货 暂时不更新退货数

                    // 看看是否后期做不做一个退货入库单的录入
                    // $upck=$this->model->execute("UPDATE RS_Warehouse_zb SET StockCount=StockCount+b.Count,VirtualCount=VirtualCount+b.Count,LastUpdateDate=GETDATE() FROM RS_OrderList b WHERE b.ProIdCard=RS_Warehouse_zb.ProIdCard AND b.OrderId=('".$order_no."')");

                    // 更新 商品表数量字段
                    $upck=$this->model->execute("UPDATE RS_Product SET LastUpdateDate=GETDATE(),Count=Count+(SELECT ISNULL(SUM(Count),0) AS count FROM RS_OrderList WHERE OrderId=('".$order_no."') ) WHERE ProId=(SELECT TOP 1 ProId FROM RS_OrderList WHERE OrderId=('".$order_no."') )");
                }

                // 更新退款
                if($type=="money"){
                    $dt = array(
                        'BackMoneyOkDate' =>$nowTime,
                        'Status'=>8,
                        'LastUpdateDate'=>$nowTime
                    );

                    $upt=$this->model->table("RS_Order")->where(array('OrderId' =>$order_no ,'Status'=>5))->save($dt);
                    $upck=true;
                }

                if($upt!=false && $upck!=false){
                    $this->model->commit();
                    echo "{\"code\":\"0\"}";
                }
                else {
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }

            }
            else{
                echo "";
            }
        }
    }

    /**
     * 根据 订单号获取订单明细信息   //待修改--无法关联库存
     * @param string $orderids 订单号集合
    */
    public function getOrderList($type,$orderids,$isSend=false,$isImport=false)
    {
        if ($type=='order') {
            $order_name="RS_OrderList";
        }
        if ($type=='score') {
            $order_name="RS_ScoreOrderList";
        }
        $wh=C('CKname').'.dbo.tb_'.$this->ckname;
        if($isSend){
            $ziduan=",d.StockCount,d.LimitCount ";
            $left=" LEFT JOIN ".$wh." d ON ps.ProIdCard=d.ProIdCard ";
            if ($isImport) {
        $sql=
<<<TBL
SELECT a.OrderId,a.ProId,a.ProName,a.ProLogoImg,c.PageUrl,(ps.ProSpec1 + ps.ProSpec2 + ps.ProSpec3 + ps.ProSpec4 + ps.ProSpec5) AS Spec,a.Price,a.Count,a.Money {$ziduan}

FROM {$order_name} a
LEFT JOIN RS_Product c ON a.ProId=c.barcode
LEFT JOIN RS_ProductList ps ON ps.ProIdCard=a.ProIdCard
{$left}
WHERE a.IsDelete=0 AND a.OrderId IN ({$orderids}) ORDER BY a.OrderId

TBL;

            }else{
            $left=" LEFT JOIN ".$wh." d ON ps.ProIdCard=d.ProIdCard ";
        $sql=
        <<<TBL
SELECT a.OrderId,a.ProId,a.ProName,a.ProLogoImg,c.PageUrl,(ps.ProSpec1 + ps.ProSpec2 + ps.ProSpec3 + ps.ProSpec4 + ps.ProSpec5) AS Spec,a.Price,a.Count,a.Money {$ziduan}
FROM {$order_name} a
LEFT JOIN RS_Product c ON a.ProId=c.ProId
LEFT JOIN RS_ProductList ps ON a.ProIdCard=ps.ProIdCard {$left}
WHERE a.IsDelete=0 AND a.OrderId IN ({$orderids}) ORDER BY a.OrderId
TBL;
            }
        }else{
          $sql=
          <<<TBL
SELECT a.OrderId,a.ProId,a.ProName,a.ProLogoImg,c.PageUrl,(ps.ProSpec1 + ps.ProSpec2 + ps.ProSpec3 + ps.ProSpec4 + ps.ProSpec5) AS Spec,a.Price,a.Count,a.Money {$ziduan}
FROM {$order_name} a LEFT JOIN RS_Product c ON a.ProId=c.ProId or  a.ProId=c.barcode LEFT JOIN RS_ProductList ps ON a.ProIdCard=ps.ProIdCard
WHERE a.IsDelete=0 AND a.OrderId IN ({$orderids}) ORDER BY a.OrderId
TBL;
        }
        // var_dump($sql);exit();
        return $this->model->query($sql);
    }

    /**
     * 获取存储过程语句
    */
    private function getProcSqls($type,$getTotal,$state,$start_time='',$end_time='',$pagesize=20,$pageindex=1,$order='DESC',$order_no='',$user_name='',$tel='',$buy_way='ALL',$openid='')
    {
        // 查询 记录总数:@getTotal=0
        // 查询 分页数据：1
        // 查询 导出数据: 2
        if ($type=='score') {
            $page_name="[dbo].[P_Score_Pager]";  //选择使用的存储过程
        }
        if ($type=='order') {
            $page_name="[dbo].[P_Order_Pager]";  //选择使用的存储过程
        }
        if ($type=='gift') {
            $page_name="[dbo].[P_Gift_Pager]";
        }
        $sql=
            <<<TBL
DECLARE @return_value int
EXEC    @return_value = $page_name
        @order_no = N'{$order_no}',
        @user_name=N'{$user_name}',
        @tel = N'{$tel}',
        @start_time = N'{$start_time}',
        @end_time = N'{$end_time}',
        @buy_way = N'{$buy_way}',
        @state = {$state},
        @pagesize = {$pagesize},
        @pageindex = {$pageindex},
        @getTotal = {$getTotal},
        @order = N'{$order}',
        @otherOrder = N'{$openid}',
        @token= N'{$this->token}',
        @stoken= N'{$this->stoken}'
TBL;
        return $sql;
        // var_dump($sql);exit;
    }

    // + -------------------------------------------------------------
    // + 订单查询 结束
    // + -------------------------------------------------------------

    /**
     * 订单导出
    */
    public function exportorder(){
        if(IS_GET)
        {
            $ty=trim($_GET["ty"]); // 类型
            $start_time=trim(htmlspecialchars($_GET["start_time"]));// 开始时间
            $end_time=trim(htmlspecialchars($_GET["end_time"]));    // 结束时间
            $buy_way=trim($_GET["buy_way"]);    // 付款方式
            $state=intval(trim($_GET["state"]));
            $user_name=trim(htmlspecialchars($_GET["user_name"]));  // 收货人姓名
            if($ty=="default"){
                // var_dump($this->getProcSqls(2,$state,$start_time,$end_time,20,1,$order,'',$user_name,'',$buy_way));exit();
                $xlsData=$this->model->setIsProc(true)->query($this->getProcSqls('order',2,$state,$start_time,$end_time,20,1,$order,'',$user_name,'',$buy_way));
                // file_put_contents('1.txt', json_encode($xlsData));
                $xlsName="order_".date('ymdHm');
                $xlsCell = array(
                    array('OrderId' , '订单号'),
                    array('Logistics' , '物流公司'),
                    array('LogisticsId' , '物流单号'),
                    array('ShipDate' ,'发货时间'),
                    array('MemberId' , '买家会员账号'),
                    array('Sex' , '买家性别'),
                    array('Province' , '买家省份'),
                    array('City' , '买家城市'),
                    array('PayMoney' , '实际实付金额'),
                    array('Freight' , '应付邮费'),
                    array('Status' , '订单状态'),
                    array('RecevingName' , '收货人姓名'),
                    array('Receving' , '收货人地址'),
                    array('RecevingPost' , '邮政编码'),
                    array('RecevingPhone' , '联系手机'),
                    array('CreateDate' , '订单创建时间'),
                    array('PayDate' , '订单付款时间'),
                    array('PayName' , '支付方式'),
                    array('ProductName' , '商品名称'),
                    array('ProductNumber','商品货号'),
                    array('ProductIdCard','商品规格编码'),
                    array('Barcode','商品条形码'),
                    array('ProductPrice' , '商品价格'),
                    array('ProductCount' , '商品数量'),
                    array('ProductSpec' , '商品规格'),
                    array('MessageByBuy' , '买家留言'),
                    array('IsEvaluation' , '是否已评价'),
                    array('SceneName' ,'推广场景'),
                    array('SceneMember' ,'推广人账号'),
                    array('MessageBySeller' , '订单备注')
                );
              // file_put_contents('1231234.txt', json_encode($xlsData));
                exportExcel($xlsName,$xlsCell,$xlsData);
            }
        }
    }

    /**
     * 导出指定内容。。。
     */
    public function exportxls(){
      if(IS_GET)
      {
          $ty=trim($_GET["ty"]); // 类型
          $start_time=trim(htmlspecialchars($_GET["start_time"]));// 开始时间
          $end_time=trim(htmlspecialchars($_GET["end_time"]));    // 结束时间
          $buy_way=trim($_GET["buy_way"]);    // 付款方式
          $state=intval(2);
          $user_name=trim(htmlspecialchars($_GET["user_name"]));  // 收货人姓名
          if($ty=="newxls"){
              $xlsData=$this->model->setIsProc(true)->query($this->getProcSqls('order',2,$state,$start_time,$end_time,20,1,$order,'',$user_name,'',$buy_way));
              $names=array('OrderId'=>'OrderId','RecevingName'=>'RecevingName','RecevingPhone'=>'RecevingPhone','RecevingProvince'=>'RecevingProvince','RecevingCity'=>'RecevingCity','RecevingArea'=>'RecevingArea','RecevingAddress'=>'RecevingAddress','RecevingPost'=>'RecevingPost','LogisticsId'=>'LogisticsId','Freight'=>'Freight','PayMoney'=>'PayMoney','ProductName'=>'ProductName','ProductNumber'=>'ProductNumber','ProductSpec'=>'ProductSpec','ProductCount'=>'ProductCount','ProductPrice'=>'ProductPrice','Coupon'=>'Coupon','CreateDate'=>'CreateDate','PayDate'=>'PayDate');
              array_unshift($xlsData,$names);
              $xlsName="order_".date('ymdHm');
              $xlsCell = array(
                  array('OrderId' , '订单号'),
                  array('RecevingName' , '收货人姓名'),
                  array('RecevingPhone' , '手机'),
                  array('','电话'),
                  array('RecevingProvince' , '份'),
                  array('RecevingCity' , '市'),
                  array('RecevingArea' , '区'),
                  array('RecevingAddress' , '地址'),
                  array('RecevingPost' , '邮编'),
                  array('LogisticsId' , '快递单号'),
                  array('Freight' , '邮费'),
                  array('PayMoney' , '订单金额'),
                  array('ProductName' , '物品名称'),
                  array('ProductNumber','商品编码'),
                  array('ProductSpec' , '销售属性'),
                  array('' , '货位'),
                  array('ProductPrice' , '单价'),
                  array('ProductCount' , '数量'),
                  array('Coupon' , '优惠'),
                  array('CreateDate' , '下单时间'),
                  array('PayDate' , '付款时间'),
                  array('MessageByBuy' , '买家备注'),
                  array('','代收货款金额'),
                  array('','保价声明价值'),
              );
              exportExcel($xlsName,$xlsCell,$xlsData);
          }
      }
    }






    // 订单商品统计
    public function orderproinfo()
    {
        $pagesize=25;
        $order="DESC";
        if (IS_GET) {
            $type="0";
            $pageindex=1;
            $proname="";
            $start_time=date('Y-m-d 00:00:00',strtotime('-30 day'));
            $end_time=date('Y-m-d 23:59:59',time());
            $this->assign('stime',$start_time)->assign("etime",$end_time);
        }

        if(IS_POST){
            $type=trim($_POST["ty"]);
            $pageindex=intval($_POST["pindex"]);
            $start_time=trim($_POST["stime"]);
            $end_time=trim($_POST["etime"]);
            $proname=trim(htmlspecialchars($_POST["proname"]));
        }

        //2 、表格数据信息
        $tblCount = $this->model->setIsProc(true)->query($this->orderproProcSqls(0,$start_time,$end_time,$pagesize,$pageindex,$order,$proname,$type));
        $count=intval($tblCount[0]["rows"]);   // 获取查询条数

        if($count>0){
            $dataOrder=$this->model->setIsProc(true)->query($this->orderproProcSqls(1,$start_time,$end_time,$pagesize,$pageindex,$order,$proname,$type));
            foreach ($dataOrder as $key => $value) {
                if (!$value['ProName']) {
                    array_splice($dataOrder, array_search($key, $dataOrder),1);
                }
            }
        }
        $count=count($dataOrder);
        if(IS_POST){
            if($count>0){
                echo "{\"code\":\"0\",\"pageCount\":".$count.",\"totalPage\":".ceil($count / $pagesize).",\"dataOrder\":".json_encode($dataOrder)."}";
            }
            else
            {
                echo "{\"code\":\"1\"}";
            }
        }

        if(IS_GET){
            if ($_GET['type']=='import') {
              $start_time=$_GET['stime'];
              $end_time=$_GET['etime'];
              $proname=$_GET['proname'];
              $dataOrder=$this->model->setIsProc(true)->query($this->orderproProcSqls(2,$start_time,$end_time,$pagesize,$pageindex,$order,$proname,$type));
              $xlsName="orderproinfo_".date('ymdHm');
              $xlsCell = array(
                  array('ClassName' , '商品分类'),
                  array('ProName' , '商品名称'),
                  array('ProNumber' , '商品编号'),
                  array('Money','订单销售额'),
                  array('Count' , '订单销量'),
              );
              exportExcel($xlsName,$xlsCell,$xlsData=$dataOrder,$file_title='时间'.$start_time.'--'.$end_time);
            }
            $this->assign("dataOrder",$dataOrder)->assign("pageCount",$count)->assign("totalPage",ceil($count / $pagesize))->display();
        }
    }

    public function orderproinfo_page()
    {
        $pagesize=25;
        $order="DESC";
        if (IS_GET) {
            $type="1";
            $pageindex=1;
            $start_time=trim(htmlspecialchars($_GET["stime"]));
            $end_time=trim(htmlspecialchars($_GET["etime"]));
            $proname=trim(htmlspecialchars($_GET["proname"]));

            $this->assign("stime",$start_time)->assign("etime",$end_time)->assign("proname",$proname);
             //2 、表格数据信息
            $tblCount = $this->model->setIsProc(true)->query($this->orderproProcSqls(0,$start_time,$end_time,$pagesize,1,$order,$proname,$type));
            $count=intval($tblCount[0]["rows"]);   // 获取查询条数
            if($count>0){
                $dataOrder=$this->model->setIsProc(true)->query($this->orderproProcSqls(1,$start_time,$end_time,$pagesize,$pageindex,$order,$proname,$type));
                $this->assign("dataOrder",$dataOrder)->assign("pageCount",$count)->assign("totalPage",ceil($count / $pagesize));
            }
        }
        $this->display();
    }

    private function orderproProcSqls($getTotal,$start_time='',$end_time='',$pagesize=25,$pageindex=1,$order='DESC',$proname,$type="0")
    {
        // 查询 记录总数:@getTotal=0
        // 查询 分页数据：1
        // 查询 导出数据: 2

        $sql=<<<TBL
DECLARE @return_value int
EXEC    @return_value = [dbo].[P_OrderProduct_Info]
        @type = N'$type',
        @start_time = N'{$start_time}',
        @end_time = N'{$end_time}',
        @proname = N'{$proname}',
        @pagesize = {$pagesize},
        @pageindex = {$pageindex},
        @getTotal = {$getTotal},
        @order = N'{$order}',
        @token = N'{$this->token}',
        @stoken=N'{$this->stoken}'
TBL;
        return $sql;
    }

    // + -------------------------------------------------------------
    // + 公共数据 开始
    // + -------------------------------------------------------------


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
     * 获取物流信息
     */
    public function getLogistics()
    {
        return $this->model->query("SELECT Number,Name FROM RS_Logistics ORDER BY IsDefault DESC");
    }

    /**
     * 公共数据 获取 仓库列表
     */
    public function getWarehouselist()
    {
        return $this->model->query("SELECT WarehouseCard,WarehouseName FROM RS_WarehouseList WHERE IsDelete=0 ORDER BY Sort");
    }

    // + -------------------------------------------------------------
    // + 公共数据 结束
    // + -------------------------------------------------------------

    //评价展示
    //评价展示
    public function assess(){
        $count=$this->model->table('RS_ProductEvaluation pe')->join("LEFT JOIN RS_Product p ON pe.ProId=p.ProId")->join("LEFT JOIN RS_Order o ON pe.OrderId=o.OrderId")->where("o.token='{$this->token}' and o.stoken='{$this->stoken}'")->count();
        $page =new \Think\Page($count,15);
        $assess=$this->model->table("RS_ProductEvaluation pe")->join("LEFT JOIN RS_Product p ON pe.ProId=p.ProId")->join("RS_Order o ON o.OrderId=pe.OrderId")->join("LEFT JOIN RS_Member m ON o.OpenId=m.OpenId")->where("o.token='{$this->token}' and o.stoken='{$this->stoken}'")->field("pe.OrderId,pe.Content,pe.ClassScore,pe.ServiceScore,pe.LogisticsScore,CONVERT(varchar(20),Date,120) as ctime,m.MemberName,p.ProName")->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign(array('assess'=>$assess,'page'=>$page->show()));
         define('FPAGE','TONGJI');
        $this->display();
    }

    /**
     * 评价搜索...
     */
    public function saccess(){
        if (IS_POST) {
            $tempData=$_POST;
        }else{
            $tempData=$_GET;
        }
        $where="o.token='".$this->token."' and o.stoken='".$this->stoken."'";
        if ($tempData['ProS']) {
            $where.=' AND pe.ClassScore='.$tempData['ProS'];
            $Pram['ProS']=$tempData['ProS'];
        };
        if ($tempData['SerS']) {
            $where.=' AND pe.ServiceScore='.$tempData['SerS'];
            $Pram['SerS']=$tempData['SerS'];
        };
        if ($tempData['LogS']) {
            $where.=' AND pe.LogisticsScore='.$tempData['LogS'];
            $Pram['LogS']=$tempData['LogS'];
        }
        // if ($tempData['MemberId']) {
        //     $where.=" AND  MemberId='".$tempData['MemberId']."'";
        //     $Pram['MemberId']=$tempData['MemberId'];
        // }
        if ($tempData['stime'] && $tempData['etime']) {
            $where.=" AND pe.Date BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
            $Pram['stime']=$tempData['stime'];
            $Pram['etime']=$tempData['etime'];
        }
        $count=$this->model->table('RS_ProductEvaluation pe')->join("LEFT JOIN RS_Product p ON pe.ProId=p.ProId")->join("RS_Order o ON pe.OrderId=o.OrderId")->where($where)->count();
        $page=new \Think\Page($count,15,$Pram);
        $assess=$this->model->table('RS_ProductEvaluation pe')->join("LEFT JOIN RS_Product p ON pe.ProId=p.ProId")->join("RS_Order o ON pe.OrderId=o.OrderId")->join("LEFT JOIN RS_Member m WHERE o.OpenId=m.OpenId")->where($where)->field("pe.OrderId,pe.Content,pe.ClassScore,pe.ServiceScore,pe.LogisticsScore,CONVERT(varchar(20),Date,120) as ctime,m.MemberName,p.ProName")->limit($page->firstRow.','.$page->listRows)->select();
        if (!$assess) {
            $status=true;
        }
        $this->assign(array('assess'=>$assess,'page'=>$page->show(),'status'=>$status));
        $this->display('assess');
    }

    /**
     * 评价查询内容导出
     */
    public function assessOut(){
        $tempData=$_GET;
        $where="token='".$this->token."'";
        $file_title="查询条件：";
        if ($tempData['ProS']) {
            $where.=' AND ClassScore='.$tempData['ProS'];
            $Pram['ProS']=$tempData['ProS'];
            $file_title.=" 商品评分：".$tempData['ProS'];
        };
        if ($tempData['SerS']) {
            $where.=' AND ServiceScore='.$tempData['SerS'];
            $Pram['SerS']=$tempData['SerS'];
            $file_title.=" 服务评分：".$tempData['SerS'];
        };
        if ($tempData['LogS']) {
            $where.=' AND LogisticsScore='.$tempData['LogS'];
            $Pram['LogS']=$tempData['LogS'];
            $file_title.=" 物流评分：".$tempData['LogS'];
        }
        if ($tempData['MemberId']) {
            $where.=" AND  MemberId='".$tempData['MemberId']."'";
            $Pram['MemberId']=$tempData['MemberId'];
            $file_title.=" 会员ID：".$tempData['MemberId'];
        }
        if ($tempData['stime'] && $tempData['etime']) {
            $where.=" AND Date BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
            $Pram['stime']=$tempData['stime'];
            $Pram['etime']=$tempData['etime'];
            $file_title.=" 评价时间:".$tempData['stime'].'--'.$tempData['etime'];
        }
        $assess=$this->model->table('RS_ProductEvaluation pe')->join("LEFT JOIN RS_Product p ON pe.ProId=p.ProId")->join("RS_Order o ON pe.OrderId=o.OrderId")->join("LEFT JOIN RS_Member m WHERE o.OpenId=m.OpenId")->where($where)->field("pe.OrderId,pe.Content,pe.ClassScore,pe.ServiceScore,pe.LogisticsScore,CONVERT(varchar(20),Date,120) as ctime,m.MemberName,p.ProName")->select();
        $xlsName="Evals_".date('ymdHm');
        $xlsCell = array(
            array('OrderId' , '订单号'),
            array('ProName' , '商品名称'),
            array('MemberName' , '买家昵称'),
            array('ctime' , '评价时间'),
            array('Content' , '评价内容'),
            array('ClassScore' , '商品评分'),
            array('ServiceScore' , '服务评分'),
            array('LogisticsScore','物流评分'),
        );

        exportExcel($xlsName,$xlsCell,$xlsData=$assess,$file_title);
    }

    /**
     * 退款处理
     */
    public function refund(){
        $oid=$_GET['oid'];
        $tb_name='tb_wh'.substr($this->token, -8,8);
        $oinfo=$this->model->table('RS_Order')->where("OrderId='%s'",$oid)->find();
        $orderlistcount=$this->model->table('RS_OrderList')->where("OrderId='%s'",$oid)->field("ProIdCard,Count")->select();
        // var_dump($oinfo);
        $this->model->startTrans();
        $memberScore=$srecord=$myCps=$myCp=$lesscash=$less=$orderres=true;
        $myscore=M()->table('RS_IntegralDetail')->where("token='%s' and MemberId='%s' and Remarks='%s' and Type='%s'",array($this->token,$oinfo['MemberId'],$oid,'cons'))->getField('Integral'); //查询积分信息
        if ($myscore) {
            $memberScore=M()->table('RS_Member')->where("token='%s' and MemberId='%s'",array($this->token,$oinfo['MemberId']))->setDec('Integral',$myscore); //扣减对应积分
            $tempSDB['MemberId']=$oinfo['MemberId'];
            $tempSDB['Integral']=-floatval($myscore);
            $tempSDB['Type']='cons';
            $tempSDB['Remarks']=$oid;
            $tempSDB['token']=$this->token;
            $srecord=M()->table('RS_IntegralDetail')->add($tempSDB);  //插入积分扣减记录
        }
        //使用优惠券/赠送优惠券做对应返还 赠送优惠券已使用不返还
        if ($oinfo['CouponListId']!='0') {
            $myCp=M()->table('RS_MemberCoupon')->where("token='%s' and MemberId='%s' and CouponId='%s'",array($this->token,$oinfo['MemberId'],$oinfo['CouponListId']))->setInc('CouponCount',1); //返还使用的优惠券
            // var_dump(M()->getlastsql());M()->rollback();exit();
        }
        if ($oinfo['getcoupon']) {
            if (M()->table('RS_MemberCoupon')->where("token='%s' and MemberId='%s' and CouponId='%s'",array($this->token,$oinfo['MemberId'],$oinfo['getcoupon']))->getField('CouponCount')>0) {
                $myCps=M()->table('RS_MemberCoupon')->where("token='%s' and MemberId='%s' and CouponId='%s'",array($this->token,$oinfo['MemberId'],$oinfo['getcoupon']))->setDec('CouponCount',1);  
                //扣减赠送的优惠券
            }
        }
        //有对应提成记录返还提成
        $getcash=M()->table('RS_MemberCommission')->where("token='%s' and MemberId='%s' and OrderId='%s'",array($this->token,$oinfo['MemberId'],$oid))->find();
        if ($getcash) {
            if ($getcash['Type']=='U') {
                // $less=M()->table('RS_MemberCommission')->where("token='%s' and MemberId='%s' and OrderId='%s'",array($this->token,$oinfo['MemberId'],$oid))->delete();
                $less=M()->table('RS_MemberCommission')->where("token='%s' and MemberId='%s' and OrderId='%s'",array($this->token,$oinfo['MemberId'],$oid))->setField('Type','TK');
            }elseif ($getcash['Type']=='TQ') {
                // $less=M()->table('RS_MemberCommission')->where("token='%s' and MemberId='%s' and OrderId='%s'",array($this->token,$oinfo['MemberId'],$oid))->delete();
                $less=M()->table('RS_MemberCommission')->where("token='%s' and MemberId='%s' and OrderId='%s'",array($this->token,$oinfo['MemberId'],$oid))->setField('Type','TK');
                $lesscash=M()->table('RS_Member')->where("token='%s' and MemberId='%s'",array($this->token,$getcash['FromMemberId']))->setDec('CutMoney',$getcash['Money']);            
            }else{
                // $less=M()->table('RS_MemberCommission')->where("token='%s' and MemberId='%s' and OrderId='%s'",array($this->token,$oinfo['MemberId'],$oid))->delete();
                $less=M()->table('RS_MemberCommission')->where("token='%s' and MemberId='%s' and OrderId='%s'",array($this->token,$oinfo['MemberId'],$oid))->setField('Type','TK');
            }
        }
        if ($oinfo['Status']!=8) {
            $orderres=$this->model->table('RS_Order')->where("OrderId='%s' and token='%s'",array($oid,$this->token))->setField(array('Status'=>8,'BackMoneyDate'=>date('Y-m-d H:i:s',time())));
        }else{
            $this->model->rollback();
            $this->error('该订单已处理，请勿重复处理');
        }
        if ($memberScore && $srecord && $myCps && $myCp && $lesscash && $less && $orderres) {
            if ($oinfo['PayName']=='ALIPAY')
            {
                // $this->redirect('Alipay/refund',array('price'=>$oinfo['Price'],'trade_no'=>$oinfo['TransactionId']));
            }
            else if ($oinfo['PayName']=='T')
            {
                $Payapi= new PayapiController();
                $res=$Payapi->txrefund($oid);
                if ($res===true) {
                    $this->model->commit();
                    $data['mobiles']=$this->model->table('RS_Member')->where("OpenId='%s'",$oinfo['OpenId'])->getField('Phone');
                    $data['content']='您的订单：'.$oinfo['OrderId'].',已完成退款，请注意查询对应账户金额';
                    $this->SendMessage($data);
                    $this->success('退款成功');
                }else{
                    $this->model->rollback();
                    $this->error($res);
                }
                // header('location:/index.php/Admin/Wxpay/refund.html?price='.$oinfo['Price'].'&out_trade_no='.$oinfo['OrderId']);
                // $this->redirect('index.php/Home/Wxpay/refund.html?price='.$oinfo['Price'].'&out_trade_no='.$oinfo['OrderId']);
            }
            elseif ($oinfo['PayName']=='YE') {
                //余额退款
                if ($oinfo['Status']!=8) {
                    if (empty($oinfo['getcoupon'])) {
                      $res=$this->model->execute("UPDATE RS_Member SET Money=Money+".$oinfo['Price']." WHERE MemberId='".$oinfo['MemberId']."' and token='".$this->token."'");
                      $res1=$this->model->execute("UPDATE RS_Order SET Status=8 WHERE MemberId='".$oinfo['MemberId']."' and OrderId='".$oinfo['OrderId']."' and token='".$this->token."'");
                        if ($res && $res1) {
                            foreach ($orderlistcount as $ol) {
                                $this->SH()->execute("UPDATE ".$tb_name." SET StockCount=StockCount+".$ol['Count'].",VirtualCount=VirtualCount+".$ol['Count'].",SalesCount=SalesCount-".$ol['Count'].",LastUpdateDate=GetDate() WHERE ProIdCard='".$ol['ProIdCard']."'");
                            } //还原库存
                            // var_dump($oinfo['PayName']);exit;
                            $this->model->commit();
                            $this->success('退款成功');
                        }else{
                            if (!$res) {
                                $this->LOGS('余额返还错误');
                            }else{
                                $this->LOGS('订单状态更改错误');
                            }
                          $this->model->rollback();
                          $this->error('退款失败');
                        }
                    }else{
                        $usercoupons=$this->model->table('RS_MemberCoupon')->where("MemberId='%s' and CouponId='%s' and token='%s'",array($oinfo['MemberId'],$oinfo['getcoupon'],$this->token))->find();
                        $res=$res2=true;
                        $res=$this->model->execute("UPDATE RS_Member SET Money=Money+".$oinfo['Price']." WHERE MemberId='".$oinfo['MemberId']."' and token='".$this->token."'");
                        $res1=$this->model->execute("UPDATE RS_Order SET Status=8 WHERE MemberId='".$oinfo['MemberId']."' and OrderId='".$oinfo['OrderId']."' and token='".$this->token."'");
                        // var_dump(M()->getLastSql());exit;
                        if ($usercoupons['CouponCount']>0) { //优惠券数量大于0减掉优惠券
                            $res2=$this->model->execute("UPDATE RS_MemberCoupon SET CouponCount=CouponCount-1 WHERE MemberId='".$oinfo['MemberId']."' and CouponId='".$oinfo['getcoupon']."' and token ='".$this->token."'");  //隐藏问题。。。。如果获赠优惠券已使用怎么办....
                        }
                        if ($res && $res1 && $res2) {
                            foreach ($orderlistcount as $ol) {
                                $this->SH()->execute("UPDATE ".$tb_name." SET StockCount=StockCount+".$ol['Count'].",VirtualCount=VirtualCount+".$ol['Count'].",SalesCount=SalesCount-".$ol['Count'].",LastUpdateDate=GetDate() WHERE ProIdCard='".$ol['ProIdCard']."'");
                            }
                            $this->model->commit();
                            $this->success('退款成功');
                        }else{
                            if (!$res) {
                                $this->LOGS('赠券部分-余额返还失败');
                            }elseif (!$res1) {
                                $this->LOGS('订单状态更改失败');
                            }else{
                                $this->LOGS('优惠券扣减失败');
                            }
                            $this->model->rollback();
                            $this->error('退款失败');
                        }
                    }
                }else{
                    $this->error('该订单已处理,请勿重复操作');
                }
            }
            elseif ($oinfo['PayName']=='JL') {
                //奖励余额退款
                if ($oinfo['Status']!=8) {
                    if (empty($oinfo['getcoupon'])) {
                      $res=$this->model->execute("UPDATE RS_Member SET VirtualMoney=VirtualMoney+".$oinfo['Price']." WHERE MemberId='".$oinfo['MemberId']."' and token='".$this->token."'");
                      $res1=$this->model->execute("UPDATE RS_Order SET Status=8 WHERE MemberId='".$oinfo['MemberId']."' and OrderId='".$oinfo['OrderId']."' and token='".$this->token."'");
                        if ($res && $res1) {
                            foreach ($orderlistcount as $ol) {
                                $this->SH()->execute("UPDATE ".$tb_name." SET StockCount=StockCount+".$ol['Count'].",VirtualCount=VirtualCount+".$ol['Count'].",SalesCount=SalesCount-".$ol['Count'].",LastUpdateDate=GetDate() WHERE ProIdCard='".$ol['ProIdCard']."'");
                            }
                            $this->model->commit();
                            $this->success('退款成功');
                        }else{
                            if (!$res) {
                                $this->LOGS('奖励余额返还失败');
                            }else{
                                $this->LOGS('订单状态更改失败');
                            }
                          $this->model->rollback();
                          $this->error('退款失败');
                        }
                    }else{
                        $usercoupons=$this->model->table('RS_MemberCoupon')->where("MemberId='%s' and CouponId='%s' and token='%s'",array($oinfo['MemberId'],$oinfo['getcoupon'],$this->token))->find();
                        $res=$res2=true;
                        $res=$this->model->execute("UPDATE RS_Member SET VirtualMoney=VirtualMoney+".$oinfo['Price']." WHERE MemberId='".$oinfo['MemberId']."' and token='".$this->token."'");
                        $res1=$this->model->execute("UPDATE RS_Order SET Status=8 WHERE MemberId='".$oinfo['MemberId']."' and OrderId='".$oinfo['OrderId']."' and token='".$this->token."'");
                        if ($usercoupons['CouponCount']>0) { //优惠券数量大于0减掉优惠券
                            $res2=$this->model->execute("UPDATE RS_MemberCoupon SET CouponCount=CouponCount-1 WHERE MemberId='".$oinfo['MemberId']."' and CouponId='".$oinfo['getcoupon']."' and token ='".$this->token."'");  //隐藏问题。。。。如果获赠优惠券已使用怎么办....
                        }
                        if ($res && $res2 && $res1) {
                            foreach ($orderlistcount as $ol) {
                                $this->SH()->execute("UPDATE ".$tb_name." SET StockCount=StockCount+".$ol['Count'].",VirtualCount=VirtualCount+".$ol['Count'].",SalesCount=SalesCount-".$ol['Count'].",LastUpdateDate=GetDate() WHERE ProIdCard='".$ol['ProIdCard']."'");
                            }
                            $this->model->commit();
                            $this->success('退款成功');
                        }else{
                            if (!$res) {
                                $this->LOGS('赠券部分-奖励余额返还失败');
                            }elseif (!$res1) {
                                $this->LOGS('赠券部分-订单处理失败');
                            }else{
                                $this->LOGS('赠券部分-优惠券扣减失败');
                            }
                            $this->model->rollback();
                            $this->error('退款失败');
                        }
                    }
                }else{
                    $this->error('该订单已处理，请勿重复处理');
                }
            }
            else
            {
                $this->error("退款类型未知",U('allOrder'));
            }
        }else{
            $this->LOGS('$memberScore->'.$memberScore.'__$srecord->'.$srecord.'__$myCps->'.$myCps.'__$myCp->'.$myCp.'__$lesscash->'.$lesscash.'__$less->'.$less);
            $this->error('退款处理失败');
            $this->model->rollback();
        }
    }

    /**
     * 20160603新增批量发货
     */
     public function sendX(){
        $count=$this->model->table('RS_Order')->where("Status=2 and CreateDate BETWEEN '".$_GET['stime']."' and '".$_GET['etime']."' and token='".$this->token."' and stoken='".$this->stoken."'")->count();
        $page=new \Think\Page($count,20);
       $data=$this->model->table('RS_Order')->where("Status=2 and CreateDate BETWEEN '".$_GET['stime']."' and '".$_GET['etime']."' and token='".$this->token."' and stoken='".$this->stoken."'")->field('OrderId,MemberId,RecevingProvince,RecevingCity,RecevingArea,RecevingAddress,RecevingName,RecevingPhone,PayName,Price,Freight,MessageByBuy,CONVERT(varchar(100), CreateDate, 120) AS CreateDate,CONVERT(varchar(100), PayDate, 120) AS PayDate,TransactionId')->limit($page->firstRow.','.$page->listRows)->select();
       // file_put_contents('1111.txt', M()->getlastsql());
       foreach ($data as &$order) {
         if ($order['TransactionId']=='导入') {
           $order['list']=$this->getOrderList("order","'".$order['OrderId']."'",true,true);
         }else {
           $order['list']=$this->getOrderList("order","'".$order['OrderId']."'",true);
         }
       }
      //  echo "<pre>";
      //  var_dump($data);
      $Logistics=$this->model->table('RS_Logistics')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->select();
      $this->assign('logistics',$Logistics);
      $this->assign('page',$page->show());
      $this->assign('data',$data);
      $this->display();

     }

     /**
      * 订单积分   ********开店无权限*********
      */
     public function allScoreOrder(){
        $URL=$this->MSL('merchant')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->getField('userUrl');
        $this->assign('URL',$URL);
        if(IS_POST)
        {
            $state=isset($_POST["state"])?intval(trim($_POST["state"])):0;
            $pageindex=intval($_POST["pindex"]); // 页码
            $order = trim($_POST["order"]);
            $ty=trim($_POST["ty"]); // 类型
            if($ty!="all"){
                $order_no=trim(htmlspecialchars($_POST["order_no"]));    // 订单号
                $user_name=trim(htmlspecialchars($_POST["user_name"]));  // 收货人姓名
                $tel=trim(htmlspecialchars($_POST["tel"]));              // 收货人联系方式
                $start_time=trim(htmlspecialchars($_POST["start_time"]));// 开始时间
                $end_time=trim(htmlspecialchars($_POST["end_time"]));    // 结束时间
                $buy_way="ALL";    // 付款方式
                $datacount = $this->model->setIsProc(true)->query($this->getProcSqls('score',0,$state,$start_time,$end_time,20,$pageindex,$order,$order_no,$user_name,$tel,$buy_way));
                // var_dump($this->model->setIsProc(true)->query($this->getProcSqls('score',0,$state,$start_time,$end_time,20,$pageindex,$order,$order_no,$user_name,$tel,$buy_way)));
                        // var_dump($datacount);

            }
            else
            {
                $datacount = $this->model->setIsProc(true)->query($this->getProcSqls('score',0,$state,'','',20,$pageindex,$order));
            }
        }
        // var_dump($datacount);

        if(IS_GET)
        {
            $selecttype="all";
            $type=isset($_GET["type"])?trim($_GET["type"]):"";

            if($type=="index"){
                $this->assign("geturl","index");
            }
            else if($type=="Users.member"){
                $this->assign("geturl","Users/member");
                $selecttype="select";
            }

            $username=isset($_GET["username"])?trim($_GET["username"]):"";
            $order_no=isset($_GET["oid"])?trim($_GET["oid"]):"";
            if ($order_no!='') {
                $this->assign('order_no',$order_no);
            }
            if($username!="")
            {
                $this->assign("user_name",$username);
            }

            $start_time=isset($_GET["start_time"])?trim(htmlspecialchars($_GET["start_time"])):"";
            $end_time=isset($_GET["end_time"])?trim(htmlspecialchars($_GET["end_time"])):"";
            $state = isset($_GET["state"])?intval(trim($_GET["state"])):0;

            $datacount = $this->model->setIsProc(true)->query($this->getProcSqls('score',0,$state,$start_time,$end_time,20,1,"DESC",$order_no,$username));
            // var_dump($this->getProcSqls(0,$state,$start_time,$end_time,20,1,"DESC",'',$username));exit;
            $count=intval($datacount[0]["rows"]);   // 获取查询条数

            $this->assign("start_time",$start_time)->assign("end_time",$end_time)->assign("state",$state);
        }
        // var_dump($datacount);
        $count=intval($datacount[0]["rows"]);   // 获取查询条数

        if($count>0)
        {
            if(IS_GET)
            {
                $data=$this->model->setIsProc(true)->query($this->getProcSqls('score',1,$state,$start_time,$end_time,20,1,"DESC",$order_no,$username));
                // var_dump($data);exit;
            }
            if(IS_POST)
            {
                if($ty!="all")
                {
                    $data=$this->model->setIsProc(true)->query($this->getProcSqls('score',1,$state,$start_time,$end_time,20,$pageindex,$order,$order_no,$user_name,$tel,$buy_way));
                }
                else
                {
                    $data = $this->model->setIsProc(true)->query($this->getProcSqls('score',1,$state,'','',20,$pageindex,$order));
                }
            }
            // var_dump($data);
            $orderids='';
            foreach ($data as $val) {
                $orderids.="'".$val["OrderId"]."',";
            }
            $orderids=substr($orderids,0,strlen($orderids)-1);     // 删除最后一个字符
            $datasonList=$this->getOrderList('score',$orderids);           //  获取子单信息
               // 匹配主单和子单信息
            foreach ($data as &$value) {
                $templist=$this->filterArraySon($value["OrderId"],"OrderId",$datasonList);
                $value["sonCount"]=Count($templist);    // 条数
                $value["datason"]=$templist;    // 商品明细
            }
        }

        $totalPages = ceil($count / 20); //总页数
        // var_dump($data);exit;
        if(IS_GET)
        {
            $this->assign("send",$this->getLogistics())->assign("pageCount",$count)->assign('selecttype',$selecttype)->assign("totalPage",$totalPages)->assign("dataOrder",$data)->display();
        }

        if(IS_POST)
        {
            if($count>0){
                echo "{\"code\":\"0\",\"pageCount\":".$count.",\"totalPage\":".$totalPages.",\"dataOrder\":".json_encode($data)."}";
            }
            else
            {
                echo "{\"code\":\"1\"}";
            }
        }
     }



    /**
     * 获取或设置积分订单信息       ********开店无权限*********
    */
    public function getScoreOrderInfoByno()
    {
        if(IS_POST)
        {
            $order_no=trim($_POST["order_no"]);
            $ty=trim($_POST["ty"]);
            $order_type=M()->table('RS_ScoreOrder')->where("OrderId='%s'",$order_no)->getField('TransactionId');  //获取订单是否为导入订单
            // 订单明细
            if($ty=="get_info"){
                $sqls=
<<<TBL
SELECT TOP 1 OrderId,MemberId,
RecevingPost,
RecevingProvince+' '+RecevingCity+' ' + RecevingArea + ' '+RecevingAddress+', '+RecevingName+', '+RecevingPhone AS Receving,
Price,Freight,PayName,IsEvaluation,
Status,
MessageBySeller,
MessageByBuy,
Logistics,
LogisticsCom,
LogisticsId,
CONVERT(varchar(100), CreateDate, 120) AS CreateDate,
CONVERT(varchar(100), PayDate, 120) AS PayDate,
CONVERT(varchar(100), ShipDate, 120) AS ShipDate,
CONVERT(varchar(100), GetDate, 120) AS GetDate,
CONVERT(varchar(100), BackMoneyDate, 120) AS BackMoneyDate,
CONVERT(varchar(100), BackMoneyOkDate, 120) AS BackMoneyOkDate,
CONVERT(varchar(100), BackProDate, 120) AS BackProDate,
CONVERT(varchar(100), BackProOkDate, 120) AS BackProOkDate,
CONVERT(varchar(100), ValidDate, 120) AS ValidDate
FROM RS_ScoreOrder
WHERE OrderId=('{$order_no}')
TBL;
                $data=$this->model->query($sqls);
                $data["list"]=$this->getOrderList("score","'".$order_no."'");
                echo json_encode($data);
            }

            else if($ty=="get_sendinfo"){
                // 订单发货
                $sqls=
    <<<TBL
SELECT TOP 1 OrderId,MemberId,RecevingPost,
RecevingProvince+' '+RecevingCity+' '+RecevingArea+' '+RecevingAddress AS Receving,RecevingName,RecevingPhone,PayName,
Price,Freight,MessageByBuy,CONVERT(varchar(100), CreateDate, 120) AS CreateDate,CONVERT(varchar(100), PayDate, 120) AS PayDate
FROM RS_ScoreOrder WHERE Status=2 AND OrderId=('{$order_no}')
TBL;
                $data=$this->model->query($sqls);
                if ($order_type=='导入') {
                    $list=$this->getOrderList('score',"'".$order_no."'",true,true);
                }else{
                    $list=$this->getOrderList('score',"'".$order_no."'",true);
                }
                $data["list"]=$list;
                echo json_encode($data);
            }
            else if($ty=="update_sendinfo"){
                // 更新发货
                $nowTime = date("Y-m-d H:i:s", time());
                $dt = array(
                    'Logistics' => htmlspecialchars(trim($_POST["sendname"])),
                    'LogisticsCom'=>htmlspecialchars(trim($_POST["sendnumber"])),
                    'LogisticsId'=>htmlspecialchars(trim($_POST["card"])),
                    'ShipDate'=>$nowTime,
                    'LastUpdateDate'=>$nowTime,
                    'Status'=>3
                );
                $ProIdCards=$this->model->table('RS_ScoreOrderList')->where("OrderId='%s'",$order_no)->getField('ProIdCard',true);
                $Counts=$this->model->table('RS_ScoreOrderList')->where("OrderId='%s'",$order_no)->getField('Count',true);

                $this->model->startTrans();

                $tb_name='tb_'.$this->ckname;
                $whs=true;
                $this->SH()->startTrans();
                /**
                 * 原联合查询更新语句  现改为分开查询更新 20160417
                 */
                foreach ($ProIdCards as $pk => $pv) {
                    // var_dump();
                    if (!$this->SH()->execute("UPDATE ".$tb_name." SET StockCount=StockCount-".$Counts[$pk].",VirtualCount=VirtualCount-".$Counts[$pk].",SalesCount=SalesCount+".$Counts[$pk].",LastUpdateDate=GetDate() WHERE ProIdCard='".$pv."'")) {
                          $whs=false;
                          break;
                      }
                }

                // 更新商品销量 和 数量字段
                $uppro=$this->model->execute("UPDATE RS_Product SET LastUpdateDate=GETDATE(),SalesCount=SalesCount+(SELECT SUM(Count) FROM RS_ScoreOrderList WHERE OrderId=('".$order_no."') AND a.ProId=RS_ScoreOrderList.ProId) FROM RS_Product a, RS_ScoreOrderList b WHERE b.ProId=a.ProId or b.ProId=a.Barcode AND b.OrderId=('".$order_no."')");
                // 更新订单表状态
                $upt=$this->model->table("RS_ScoreOrder")->where(array('OrderId' =>$order_no ,'Status'=>2))->save($dt);
                if($upt!=false && $uppro!=false && $whs!=false){
                    $this->SH()->commit();
                    $this->model->commit();
                    echo "{\"code\":\"0\"}";
                }
                else {
                    $this->SH()->rollback();
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }
            }
            else if($ty=="get_message"){
                // 卖家备注
                echo json_encode($this->model->query("SELECT MessageBySeller FROM RS_ScoreOrder WHERE OrderId=('".$order_no."')"));
            }
            else if($ty=="update_message"){
                // 更新卖家备注
                echo "{\"code\":\"".$this->model->table("RS_ScoreOrder")->where("OrderId='" . $order_no . "'")->setField("MessageBySeller",htmlspecialchars(trim($_POST["msg"])))."\"}";
            }
            else if($ty=="update_back"){
                $type=trim($_POST["back"]);
                $nowTime = date("Y-m-d H:i:s", time());
                $this->model->startTrans();
                // 更新退货
                if($type=="pro"){
                    $dt = array(
                        'BackProOkDate' =>$nowTime ,
                        'Status'=>7,
                        'LastUpdateDate'=>$nowTime
                    );

                    $upt=$this->model->table("RS_ScoreOrder")->where(array('OrderId' =>$order_no ,'Status'=>6))->save($dt);
                    // 此处退货 暂时不更新退货数

                    // 看看是否后期做不做一个退货入库单的录入
                    // $upck=$this->model->execute("UPDATE RS_Warehouse_zb SET StockCount=StockCount+b.Count,VirtualCount=VirtualCount+b.Count,LastUpdateDate=GETDATE() FROM RS_OrderList b WHERE b.ProIdCard=RS_Warehouse_zb.ProIdCard AND b.OrderId=('".$order_no."')");

                    // 更新 商品表数量字段
                    $upck=$this->model->execute("UPDATE RS_Product SET LastUpdateDate=GETDATE(),Count=Count+(SELECT ISNULL(SUM(Count),0) AS count FROM RS_ScoreOrderList WHERE OrderId=('".$order_no."') ) WHERE ProId=(SELECT TOP 1 ProId FROM RS_ScoreOrderList WHERE OrderId=('".$order_no."') )");
                }

                // 更新退款
                if($type=="money"){
                    $dt = array(
                        'BackMoneyOkDate' =>$nowTime,
                        'Status'=>8,
                        'LastUpdateDate'=>$nowTime
                    );

                    $upt=$this->model->table("RS_ScoreOrder")->where(array('OrderId' =>$order_no ,'Status'=>5))->save($dt);
                    $upck=true;
                }

                if($upt!=false && $upck!=false){
                    $this->model->commit();
                    echo "{\"code\":\"0\"}";
                }
                else {
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }

            }
            else{
                echo "";
            }
        }
    }


    /**
     * 订单导出         ********开店无权限*********
    */
    public function exportscoreorder(){
        if(IS_GET)
        {
            $ty=trim($_GET["ty"]); // 类型
            $start_time=trim(htmlspecialchars($_GET["start_time"]));// 开始时间
            $end_time=trim(htmlspecialchars($_GET["end_time"]));    // 结束时间
            $buy_way="ALL";    // 付款方式
            $state=intval(trim($_GET["state"]));
            $user_name=trim(htmlspecialchars($_GET["user_name"]));  // 收货人姓名
            if($ty=="default"){
                // var_dump($this->getProcSqls(2,$state,$start_time,$end_time,20,1,$order,'',$user_name,'',$buy_way));exit();
                $xlsData=$this->model->setIsProc(true)->query($this->getProcSqls('score',2,$state,$start_time,$end_time,20,1,$order,'',$user_name,'',$buy_way));
                // file_put_contents('1.txt', json_encode($xlsData));
                $xlsName="scoreorder_".date('ymdHm');
                $xlsCell = array(
                    array('OrderId' , '订单号'),
                    array('Logistics' , '物流公司'),
                    array('LogisticsId' , '物流单号'),
                    array('ShipDate' ,'发货时间'),
                    array('MemberId' , '买家会员账号'),
                    array('Sex' , '买家性别'),
                    array('Province' , '买家省份'),
                    array('City' , '买家城市'),
                    array('PayMoney' , '实际消耗积分'),
                    array('Freight' , '应付邮费'),
                    array('Status' , '订单状态'),
                    array('RecevingName' , '收货人姓名'),
                    array('RecevingProvince' , '收货人省份'),
                    array('RecevingCity' , '收货人城市'),
                    array('RecevingArea' , '收货人地区'),
                    array('RecevingAddress' , '收货人详细地址'),
                    array('RecevingPost' , '邮政编码'),
                    array('RecevingPhone' , '联系手机'),
                    array('CreateDate' , '订单创建时间'),
                    array('PayDate' , '订单付款时间'),
                    array('PayName' , '支付方式'),
                    array('ProductName' , '商品名称'),
                    array('ProductNumber','商品货号'),
                    array('ProductIdCard','商品规格编码'),
                    array('Barcode','商品条形码'),
                    array('ProductPrice' , '商品积分'),
                    array('ProductCount' , '商品数量'),
                    array('ProductSpec' , '商品规格'),
                    array('MessageByBuy' , '买家留言'),
                    array('IsEvaluation' , '是否已评价'),
                    array('SceneName' ,'推广场景'),
                    array('SceneMember' ,'推广人账号'),
                    array('MessageBySeller' , '订单备注')
                );
              // file_put_contents('1231234.txt', json_encode($xlsData));
                exportExcel($xlsName,$xlsCell,$xlsData);
            }
        }
    }

    /**
     * 导出指定内容。。。         ********开店无权限*********
     */
    public function exportscorexls(){
      if(IS_GET)
      {
          $ty=trim($_GET["ty"]); // 类型
          $start_time=trim(htmlspecialchars($_GET["start_time"]));// 开始时间
          $end_time=trim(htmlspecialchars($_GET["end_time"]));    // 结束时间
          $buy_way=trim($_GET["buy_way"]);    // 付款方式
          $state=intval(2);
          $user_name=trim(htmlspecialchars($_GET["user_name"]));  // 收货人姓名
          if($ty=="newxls"){
              $xlsData=$this->model->setIsProc(true)->query($this->getProcSqls('score',2,$state,$start_time,$end_time,20,1,$order,'',$user_name,'',$buy_way));
              $names=array('OrderId'=>'OrderId','RecevingName'=>'RecevingName','RecevingPhone'=>'RecevingPhone','RecevingProvince'=>'RecevingProvince','RecevingCity'=>'RecevingCity','RecevingArea'=>'RecevingArea','RecevingAddress'=>'RecevingAddress','RecevingPost'=>'RecevingPost','LogisticsId'=>'LogisticsId','Freight'=>'Freight','PayMoney'=>'PayMoney','ProductName'=>'ProductName','ProductNumber'=>'ProductNumber','ProductSpec'=>'ProductSpec','ProductCount'=>'ProductCount','ProductPrice'=>'ProductPrice','Coupon'=>'Coupon','CreateDate'=>'CreateDate','PayDate'=>'PayDate');
              array_unshift($xlsData,$names);
              $xlsName="scoreorder_".date('ymdHm');
              $xlsCell = array(
                  array('OrderId' , '订单号'),
                  array('RecevingName' , '收货人姓名'),
                  array('RecevingPhone' , '手机'),
                  array('','电话'),
                  array('RecevingProvince' , '份'),
                  array('RecevingCity' , '市'),
                  array('RecevingArea' , '区'),
                  array('RecevingAddress' , '地址'),
                  array('RecevingPost' , '邮编'),
                  array('LogisticsId' , '快递单号'),
                  array('Freight' , '邮费'),
                  array('PayMoney' , '订单金额'),
                  array('ProductName' , '物品名称'),
                  array('ProductNumber','商品编码'),
                  array('ProductSpec' , '销售属性'),
                  array('' , '货位'),
                  array('ProductPrice' , '单价'),
                  array('ProductCount' , '数量'),
                  array('Coupon' , '优惠'),
                  array('CreateDate' , '下单时间'),
                  array('PayDate' , '付款时间'),
                  array('MessageByBuy' , '买家备注'),
                  array('','代收货款金额'),
                  array('','保价声明价值'),
              );
              exportExcel($xlsName,$xlsCell,$xlsData);
          }
      }
    }
    /**
     *   ********开店无权限*********
     */
     public function sendXscore(){
        $count=$this->model->table('RS_ScoreOrder')->where("Status=2 and CreateDate BETWEEN '".$_GET['stime']."' and '".$_GET['etime']."'")->count();
        $page=new \Think\Page($count,20);
       $data=$this->model->table('RS_ScoreOrder')->where("Status=2 and CreateDate BETWEEN '".$_GET['stime']."' and '".$_GET['etime']."'")->field('OrderId,MemberId,RecevingProvince,RecevingCity,RecevingArea,RecevingAddress,RecevingName,RecevingPhone,PayName,Price,Freight,MessageByBuy,CONVERT(varchar(100), CreateDate, 120) AS CreateDate,CONVERT(varchar(100), PayDate, 120) AS PayDate,TransactionId')->limit($page->firstRow.','.$page->listRows)->select();
       // file_put_contents('1111.txt', M()->getlastsql());
       foreach ($data as &$order) {
         if ($order['TransactionId']=='导入') {
           $order['list']=$this->getOrderList("score","'".$order['OrderId']."'",true,true);
         }else {
           $order['list']=$this->getOrderList("score","'".$order['OrderId']."'",true);
         }
       }
      //  echo "<pre>";
      //  var_dump($data);
      $Logistics=$this->model->table('RS_Logistics')->where("token='%s'",$this->token)->select();
      $this->assign('logistics',$Logistics);
      $this->assign('page',$page->show());
      $this->assign('data',$data);
      $this->display();

     }
     /**
      * 礼包订单      ********开店无权限*********
      */
     public function allGiftOrder(){
        $URL=$this->MSL('merchant')->where("token='%s'",$this->token)->getField('userUrl');
        $this->assign('URL',$URL);
        if(IS_POST)
        {
            $state=isset($_POST["state"])?intval(trim($_POST["state"])):0;
            $pageindex=intval($_POST["pindex"]); // 页码
            $order = trim($_POST["order"]);
            $ty=trim($_POST["ty"]); // 类型
            if($ty!="all"){
                $order_no=trim(htmlspecialchars($_POST["order_no"]));    // 订单号
                $user_name=trim(htmlspecialchars($_POST["user_name"]));  // 收货人姓名
                $tel=trim(htmlspecialchars($_POST["tel"]));              // 收货人联系方式
                $start_time=trim(htmlspecialchars($_POST["start_time"]));// 开始时间
                $end_time=trim(htmlspecialchars($_POST["end_time"]));    // 结束时间
                $buy_way="ALL";    // 付款方式
                $datacount = $this->model->setIsProc(true)->query($this->getProcSqls('gift',0,$state,$start_time,$end_time,20,$pageindex,$order,$order_no,$user_name,$tel,$buy_way));
                // var_dump($this->model->setIsProc(true)->query($this->getProcSqls('score',0,$state,$start_time,$end_time,20,$pageindex,$order,$order_no,$user_name,$tel,$buy_way)));
                        // var_dump($datacount);

            }
            else
            {
                $datacount = $this->model->setIsProc(true)->query($this->getProcSqls('gift',0,$state,'','',20,$pageindex,$order));
            }
        }
        // var_dump($datacount);

        if(IS_GET)
        {
            $selecttype="all";
            $type=isset($_GET["type"])?trim($_GET["type"]):"";

            if($type=="index"){
                $this->assign("geturl","index");
            }
            else if($type=="Users.member"){
                $this->assign("geturl","Users/member");
                $selecttype="select";
            }

            $username=isset($_GET["username"])?trim($_GET["username"]):"";
            $order_no=isset($_GET["oid"])?trim($_GET["oid"]):"";
            if ($order_no!='') {
                $this->assign('order_no',$order_no);
            }
            if($username!="")
            {
                $this->assign("user_name",$username);
            }

            $start_time=isset($_GET["start_time"])?trim(htmlspecialchars($_GET["start_time"])):"";
            $end_time=isset($_GET["end_time"])?trim(htmlspecialchars($_GET["end_time"])):"";
            $state = isset($_GET["state"])?intval(trim($_GET["state"])):0;

            $datacount = $this->model->setIsProc(true)->query($this->getProcSqls('gift',0,$state,$start_time,$end_time,20,1,"DESC",$order_no,$username));
            // var_dump($this->getProcSqls(0,$state,$start_time,$end_time,20,1,"DESC",'',$username));exit;
            $count=intval($datacount[0]["rows"]);   // 获取查询条数

            $this->assign("start_time",$start_time)->assign("end_time",$end_time)->assign("state",$state);
        }
        // var_dump($datacount);
        $count=intval($datacount[0]["rows"]);   // 获取查询条数

        if($count>0)
        {
            if(IS_GET)
            {
                $data=$this->model->setIsProc(true)->query($this->getProcSqls('gift',1,$state,$start_time,$end_time,20,1,"DESC",$order_no,$username));
                // var_dump($data);exit;
            }
            if(IS_POST)
            {
                if($ty!="all")
                {
                    $data=$this->model->setIsProc(true)->query($this->getProcSqls('gift',1,$state,$start_time,$end_time,20,$pageindex,$order,$order_no,$user_name,$tel,$buy_way));
                }
                else
                {
                    $data = $this->model->setIsProc(true)->query($this->getProcSqls('gift',1,$state,'','',20,$pageindex,$order));
                }
            }
            // var_dump($data);
            $orderids='';
            foreach ($data as $val) {
                $orderids.="'".$val["OrderId"]."',";
            }
            $orderids=substr($orderids,0,strlen($orderids)-1);     // 删除最后一个字符
            $datasonList=$this->getOrderList('score',$orderids);           //  获取子单信息
               // 匹配主单和子单信息
            foreach ($data as &$value) {
                $templist=$this->filterArraySon($value["OrderId"],"OrderId",$datasonList);
                $value["sonCount"]=Count($templist);    // 条数
                $value["datason"]=$templist;    // 商品明细
            }
        }

        $totalPages = ceil($count / 20); //总页数
        // var_dump($data);exit;
        if(IS_GET)
        {
            $this->assign("send",$this->getLogistics())->assign("pageCount",$count)->assign('selecttype',$selecttype)->assign("totalPage",$totalPages)->assign("dataOrder",$data);
            $this->display();
        }

        if(IS_POST)
        {
            if($count>0){
                echo "{\"code\":\"0\",\"pageCount\":".$count.",\"totalPage\":".$totalPages.",\"dataOrder\":".json_encode($data)."}";
            }
            else
            {
                echo "{\"code\":\"1\"}";
            }
        }
     }



    /**
     * 获取或设置积分订单信息       ********开店无权限*********
    */
    public function getGiftOrderInfoByno()
    {
        if(IS_POST)
        {
            $order_no=trim($_POST["order_no"]);
            $ty=trim($_POST["ty"]);
            $order_type=M()->table('RS_ScoreOrder')->where("OrderId='%s'",$order_no)->getField('TransactionId');  //获取订单是否为导入订单
            // 订单明细
            if($ty=="get_info"){
                $sqls=
<<<TBL
SELECT TOP 1 OrderId,MemberId,
RecevingPost,
RecevingProvince+' '+RecevingCity+' ' + RecevingArea + ' '+RecevingAddress+', '+RecevingName+', '+RecevingPhone AS Receving,
Price,Freight,PayName,IsEvaluation,
Status,
MessageBySeller,
MessageByBuy,
Logistics,
LogisticsCom,
LogisticsId,
CONVERT(varchar(100), CreateDate, 120) AS CreateDate,
CONVERT(varchar(100), PayDate, 120) AS PayDate,
CONVERT(varchar(100), ShipDate, 120) AS ShipDate,
CONVERT(varchar(100), GetDate, 120) AS GetDate,
CONVERT(varchar(100), BackMoneyDate, 120) AS BackMoneyDate,
CONVERT(varchar(100), BackMoneyOkDate, 120) AS BackMoneyOkDate,
CONVERT(varchar(100), BackProDate, 120) AS BackProDate,
CONVERT(varchar(100), BackProOkDate, 120) AS BackProOkDate,
CONVERT(varchar(100), ValidDate, 120) AS ValidDate
FROM RS_GiftOrder
WHERE OrderId=('{$order_no}')
TBL;
                $data=$this->model->query($sqls);
                $data["list"]=$this->getOrderList("score","'".$order_no."'");
                echo json_encode($data);
            }

            else if($ty=="get_sendinfo"){
                // 订单发货
                $sqls=
    <<<TBL
SELECT TOP 1 OrderId,MemberId,RecevingPost,
RecevingProvince+' '+RecevingCity+' '+RecevingArea+' '+RecevingAddress AS Receving,RecevingName,RecevingPhone,PayName,
Price,Freight,MessageByBuy,CONVERT(varchar(100), CreateDate, 120) AS CreateDate,CONVERT(varchar(100), PayDate, 120) AS PayDate
FROM RS_GiftOrder WHERE Status=2 AND OrderId=('{$order_no}')
TBL;
                $data=$this->model->query($sqls);
                if ($order_type=='导入') {
                    $list=$this->getOrderList('gift',"'".$order_no."'",true,true);
                }else{
                    $list=$this->getOrderList('gift',"'".$order_no."'",true);
                }
                $data["list"]=$list;
                echo json_encode($data);
            }
            else if($ty=="update_sendinfo"){
                // 更新发货
                $nowTime = date("Y-m-d H:i:s", time());
                $dt = array(
                    'Logistics' => htmlspecialchars(trim($_POST["sendname"])),
                    'LogisticsCom'=>htmlspecialchars(trim($_POST["sendnumber"])),
                    'LogisticsId'=>htmlspecialchars(trim($_POST["card"])),
                    'ShipDate'=>$nowTime,
                    'LastUpdateDate'=>$nowTime,
                    'Status'=>3
                );
                $ProIdCards=$this->model->table('RS_GiftOrderList')->where("OrderId='%s'",$order_no)->getField('ProIdCard',true);
                $Counts=$this->model->table('RS_GiftOrderList')->where("OrderId='%s'",$order_no)->getField('Count',true);

                $this->model->startTrans();

                $tb_name='tb_'.$this->ckname;
                $whs=true;
                $this->SH()->startTrans();
                /**
                 * 原联合查询更新语句  现改为分开查询更新 20160417
                 */
                foreach ($ProIdCards as $pk => $pv) {
                    // var_dump();
                   if (!$this->SH()->execute("UPDATE ".$tb_name." SET StockCount=StockCount-".$Counts[$pk].",VirtualCount=VirtualCount-".$Counts[$pk].",SalesCount=SalesCount+".$Counts[$pk].",LastUpdateDate=GetDate() WHERE ProIdCard='".$pv."'")) {
                          $whs=false;
                          break;
                      }
                }

                // 更新商品销量 和 数量字段
                $uppro=$this->model->execute("UPDATE RS_Product SET LastUpdateDate=GETDATE(),SalesCount=SalesCount+(SELECT SUM(Count) FROM RS_GiftOrderList WHERE OrderId=('".$order_no."') AND a.ProId=RS_GiftOrderList.ProId) FROM RS_Product a, RS_GiftOrderList b WHERE b.ProId=a.ProId or b.ProId=a.Barcode AND b.OrderId=('".$order_no."')");
                // 更新订单表状态
                $upt=$this->model->table("RS_GiftOrder")->where(array('OrderId' =>$order_no ,'Status'=>2))->save($dt);
                if($upt!=false && $uppro!=false && $whs!=false){
                    $this->SH()->commit();
                    $this->model->commit();
                    echo "{\"code\":\"0\"}";
                }
                else {
                    $this->SH()->rollback();
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }
            }
            else if($ty=="get_message"){
                // 卖家备注
                echo json_encode($this->model->query("SELECT MessageBySeller FROM RS_GiftOrder WHERE OrderId=('".$order_no."')"));
            }
            else if($ty=="update_message"){
                // 更新卖家备注
                echo "{\"code\":\"".$this->model->table("RS_GiftOrder")->where("OrderId='" . $order_no . "'")->setField("MessageBySeller",htmlspecialchars(trim($_POST["msg"])))."\"}";
            }
            else if($ty=="update_back"){
                $type=trim($_POST["back"]);
                $nowTime = date("Y-m-d H:i:s", time());
                $this->model->startTrans();
                // 更新退货
                if($type=="pro"){
                    $dt = array(
                        'BackProOkDate' =>$nowTime ,
                        'Status'=>7,
                        'LastUpdateDate'=>$nowTime
                    );

                    $upt=$this->model->table("RS_GiftOrder")->where(array('OrderId' =>$order_no ,'Status'=>6))->save($dt);
                    // 此处退货 暂时不更新退货数

                    // 看看是否后期做不做一个退货入库单的录入
                    // $upck=$this->model->execute("UPDATE RS_Warehouse_zb SET StockCount=StockCount+b.Count,VirtualCount=VirtualCount+b.Count,LastUpdateDate=GETDATE() FROM RS_OrderList b WHERE b.ProIdCard=RS_Warehouse_zb.ProIdCard AND b.OrderId=('".$order_no."')");

                    // 更新 商品表数量字段
                    $upck=$this->model->execute("UPDATE RS_Product SET LastUpdateDate=GETDATE(),Count=Count+(SELECT ISNULL(SUM(Count),0) AS count FROM RS_GiftOrderList WHERE OrderId=('".$order_no."') ) WHERE ProId=(SELECT TOP 1 ProId FROM RS_GiftOrderList WHERE OrderId=('".$order_no."') )");
                }

                // 更新退款
                if($type=="money"){
                    $dt = array(
                        'BackMoneyOkDate' =>$nowTime,
                        'Status'=>8,
                        'LastUpdateDate'=>$nowTime
                    );

                    $upt=$this->model->table("RS_GiftOrder")->where(array('OrderId' =>$order_no ,'Status'=>5))->save($dt);
                    $upck=true;
                }

                if($upt!=false && $upck!=false){
                    $this->model->commit();
                    echo "{\"code\":\"0\"}";
                }
                else {
                    $this->model->rollback();
                    echo "{\"code\":\"1\"}";
                }

            }
            else{
                echo "";
            }
        }
    }


    /**
     * 订单导出      ********开店无权限*********
    */
    public function exportgiftorder(){
        if(IS_GET)
        {
            $ty=trim($_GET["ty"]); // 类型
            $start_time=trim(htmlspecialchars($_GET["start_time"]));// 开始时间
            $end_time=trim(htmlspecialchars($_GET["end_time"]));    // 结束时间
            $buy_way="ALL";    // 付款方式
            $state=intval(trim($_GET["state"]));
            $user_name=trim(htmlspecialchars($_GET["user_name"]));  // 收货人姓名
            if($ty=="default"){
                // var_dump($this->getProcSqls(2,$state,$start_time,$end_time,20,1,$order,'',$user_name,'',$buy_way));exit();
                $xlsData=$this->model->setIsProc(true)->query($this->getProcSqls('gift',2,$state,$start_time,$end_time,20,1,$order,'',$user_name,'',$buy_way));
                // file_put_contents('1.txt', json_encode($xlsData));
                $xlsName="giftorder_".date('ymdHm');
                $xlsCell = array(
                    array('OrderId' , '订单号'),
                    array('Logistics' , '物流公司'),
                    array('LogisticsId' , '物流单号'),
                    array('ShipDate' ,'发货时间'),
                    array('MemberId' , '买家会员账号'),
                    array('Sex' , '买家性别'),
                    array('Province' , '买家省份'),
                    array('City' , '买家城市'),
                    array('PayMoney' , '实际消耗积分'),
                    array('Freight' , '应付邮费'),
                    array('Status' , '订单状态'),
                    array('RecevingName' , '收货人姓名'),
                    array('RecevingProvince' , '收货人省份'),
                    array('RecevingCity' , '收货人城市'),
                    array('RecevingArea' , '收货人地区'),
                    array('RecevingAddress' , '收货人详细地址'),
                    array('RecevingPost' , '邮政编码'),
                    array('RecevingPhone' , '联系手机'),
                    array('CreateDate' , '订单创建时间'),
                    array('PayDate' , '订单付款时间'),
                    array('PayName' , '支付方式'),
                    array('ProductName' , '商品名称'),
                    array('ProductNumber','商品货号'),
                    array('ProductIdCard','商品规格编码'),
                    array('Barcode','商品条形码'),
                    array('ProductPrice' , '商品积分'),
                    array('ProductCount' , '商品数量'),
                    array('ProductSpec' , '商品规格'),
                    array('MessageByBuy' , '买家留言'),
                    array('IsEvaluation' , '是否已评价'),
                    array('SceneName' ,'推广场景'),
                    array('SceneMember' ,'推广人账号'),
                    array('MessageBySeller' , '订单备注')
                );
              // file_put_contents('1231234.txt', json_encode($xlsData));
                exportExcel($xlsName,$xlsCell,$xlsData);
            }
        }
    }

    /**
     * 导出指定内容。。。         ********开店无权限*********
     */
    public function exportgiftxls(){
      if(IS_GET)
      {
          $ty=trim($_GET["ty"]); // 类型
          $start_time=trim(htmlspecialchars($_GET["start_time"]));// 开始时间
          $end_time=trim(htmlspecialchars($_GET["end_time"]));    // 结束时间
          $buy_way=trim($_GET["buy_way"]);    // 付款方式
          $state=intval(2);
          $user_name=trim(htmlspecialchars($_GET["user_name"]));  // 收货人姓名
          if($ty=="newxls"){
              $xlsData=$this->model->setIsProc(true)->query($this->getProcSqls('gift',2,$state,$start_time,$end_time,20,1,$order,'',$user_name,'',$buy_way));
              $names=array('OrderId'=>'OrderId','RecevingName'=>'RecevingName','RecevingPhone'=>'RecevingPhone','RecevingProvince'=>'RecevingProvince','RecevingCity'=>'RecevingCity','RecevingArea'=>'RecevingArea','RecevingAddress'=>'RecevingAddress','RecevingPost'=>'RecevingPost','LogisticsId'=>'LogisticsId','Freight'=>'Freight','PayMoney'=>'PayMoney','ProductName'=>'ProductName','ProductNumber'=>'ProductNumber','ProductSpec'=>'ProductSpec','ProductCount'=>'ProductCount','ProductPrice'=>'ProductPrice','Coupon'=>'Coupon','CreateDate'=>'CreateDate','PayDate'=>'PayDate');
              array_unshift($xlsData,$names);
              $xlsName="giftorder_".date('ymdHm');
              $xlsCell = array(
                  array('OrderId' , '订单号'),
                  array('RecevingName' , '收货人姓名'),
                  array('RecevingPhone' , '手机'),
                  array('','电话'),
                  array('RecevingProvince' , '份'),
                  array('RecevingCity' , '市'),
                  array('RecevingArea' , '区'),
                  array('RecevingAddress' , '地址'),
                  array('RecevingPost' , '邮编'),
                  array('LogisticsId' , '快递单号'),
                  array('Freight' , '邮费'),
                  array('PayMoney' , '订单金额'),
                  array('ProductName' , '物品名称'),
                  array('ProductNumber','商品编码'),
                  array('ProductSpec' , '销售属性'),
                  array('' , '货位'),
                  array('ProductPrice' , '单价'),
                  array('ProductCount' , '数量'),
                  array('Coupon' , '优惠'),
                  array('CreateDate' , '下单时间'),
                  array('PayDate' , '付款时间'),
                  array('MessageByBuy' , '买家备注'),
                  array('','代收货款金额'),
                  array('','保价声明价值'),
              );
              exportExcel($xlsName,$xlsCell,$xlsData);
          }
      }
    }
    /**
     *  ********开店无权限*********
     */
     public function sendXgift(){
        $count=$this->model->table('RS_GiftOrder')->where("Status=2 and CreateDate BETWEEN '".$_GET['stime']."' and '".$_GET['etime']."'")->count();
        $page=new \Think\Page($count,20);
       $data=$this->model->table('RS_GiftOrder')->where("Status=2 and CreateDate BETWEEN '".$_GET['stime']."' and '".$_GET['etime']."'")->field('OrderId,MemberId,RecevingProvince,RecevingCity,RecevingArea,RecevingAddress,RecevingName,RecevingPhone,PayName,Price,Freight,MessageByBuy,CONVERT(varchar(100), CreateDate, 120) AS CreateDate,CONVERT(varchar(100), PayDate, 120) AS PayDate,TransactionId')->limit($page->firstRow.','.$page->listRows)->select();
       // file_put_contents('1111.txt', M()->getlastsql());
       foreach ($data as &$order) {
         if ($order['TransactionId']=='导入') {
           $order['list']=$this->getOrderList("gift","'".$order['OrderId']."'",true,true);
         }else {
           $order['list']=$this->getOrderList("gift","'".$order['OrderId']."'",true);
         }
       }
      //  echo "<pre>";
      //  var_dump($data);
      $Logistics=$this->model->table('RS_Logistics')->where("token='%s'",$this->token)->select();
      $this->assign('logistics',$Logistics);
      $this->assign('page',$page->show());
      $this->assign('data',$data);
      $this->display();

     }


    /**
     * 快递查询
     */
    public function express($expid,$expcom){
        $req = new AliExp_Api(C('EXP_APPKEY'),C('EXP_APPSECRET'),C('EXP_NRURL'));
        $res= $req->addTextPara("com", $expcom)->addTextPara("nu", $expid)->get();
        $res=json_encode($res,true);
        $res=json_decode($res,true);
        if ($res['showapi_res_code']=='0') {
          switch ($res['showapi_res_body']['status']) {
            case '-1':
            $status='待查询';
            break;
            case '0':
            $status='查询异常';
            break;
            case '1':
            $status='暂无记录';
            break;
            case '2':
            $status='在途中';
            break;
            case '3':
            $status='派送中';
            break;
            case '4':
            $status='已签收';
            break;
            case '5':
            $status='用户拒签';
            break;
            case '6':
            $status='疑难件';
            break;
            case '7':
            $status='无效单';
            break;
            case '8':
            $status='超时单';
            break;
            case '9':
            $status='签收失败';
            break;
            case '10':
            $status='退回';
            break;
            default:
            $status='查询异常';
            break;
            }
        } else {
            $status='查询失败';
        }
        $returndata=$res;
        $returndata['st']=$status;
        return $returndata;
    }

     /**
      * 查询快递详情
      */
     public function getloinfo(){
        $oid=$_POST['oid'];
        $oinfo=$this->model->table('RS_Order')->where("token='%s' and OrderId='%s'",array($this->token,$oid))->find();
        $loinfo=$this->express($oinfo['LogisticsId'],$oinfo['LogisticsCom']);

        $res['stname']=$oinfo['Logistics'];
        $res['stid']=$oinfo['LogisticsId'];
        if ($loinfo['showapi_res_code']=='0') {
            $res['st']=$loinfo['st'];
            $res['status']='success';
            $res['data']=$loinfo['showapi_res_body']['data'];
            echo json_encode($res);
        }else{
            $res['status']='error';
            echo json_encode($res);
        }

     }

     public function getpsinfo(){
        $oid=$_POST['oid'];
        $info=$this->model->table('RS_DistributionForOrder do')->join("LEFT JOIN RS_Distribution d ON do.OpenId=d.OpenId")->where("do.OrderId='%s' and do.IsDelete='0'",$oid)->field("CONVERT(varchar(20),do.GetDate,120) as GetDate,CONVERT(varchar(20),do.OverDate,120) as OverDate,do.IsSuccess,do.Status,d.TrueName,d.Phone")->find();
        if ($info) {
            $msg['status']='success';
            $msg['data']=$info;
        }else{
            $msg['status']='error';
        }
        echo json_encode($msg);
     }

     /**
      * 退款申请  2017-05-20
      */
     public function orderRcheck(){
        $oid=$_POST['oid'];
        $type=$_POST['type'];
        if ($type=='pass') {
            if (M()->table('RS_Order')->where("stoken='%s' and OrderId='%s'",array($this->stoken,$oid))->setField('IsRcheck','1')) {
                $msg['status']='success';
                $filename = './Public/message.json';
                $res = json_decode(file_get_contents($filename),true);
                $messageData['mobiles'] = $res['refund'];
                $messageData['content'] = "您有的新的订单退款申请,请及时处理";
                $this->SendMessage($messageData);
            }else{
                $msg['status']='error';
            }
        }elseif ($type=='ref') {
            if (M()->table('RS_Order')->where("stoken='%s' and OrderId='%s'",array($this->stoken,$oid))->setField('IsRcheck','2')) {
                $msg['status']='success';
            }else{
                $msg['status']='error';
            }
        }
        echo json_encode($msg);
     }

     /**
     * showindex
     */
    public function showindex(){
        $where="o.PayDate BETWEEN '{$_GET['start_time']}' AND '{$_GET['end_time']}' and o.Status in(2,3,4,5,10) and o.token='{$this->token}' and stoken='{$this->stoken}'";
        $count=M()->table('RS_Order o')->where($where)->count();
        $page = new \Think\Page($count,10,$_GET);
        $lists= M()->table('RS_Order o')->where($where)->field("o.OrderId,o.Price as orderMoney,o.Freight,o.RecevingProvince,o.RecevingCity,o.RecevingArea, o.RecevingAddress,o.RecevingName,o.RecevingPhone,o.PayName,CONVERT(varchar(20),o.CreateDate,120) as CreateDate,o.Status")->limit($page->firstRow.','.$page->listRows)->order('CreateDate desc')->select();
        $orderids='';
        foreach ($lists as $key => $value) {
            $orderids.="'".$value["OrderId"]."',";
        }
        $orderids=substr($orderids,0,strlen($orderids)-1);     // 删除最后一个字符

        $sons=$this->getOrderList('order',$orderids);
        $allOrder=array();
        foreach ($lists as &$value) {
            $templist=$this->filterArraySon($value["OrderId"],"OrderId",$sons);
            $value["sonCount"]=Count($templist);    // 条数
            $value["sons"]=$templist;    // 商品明细
        }
        $pagedata['page']=$page->show();
        $pagedata['allOrder']=$lists;
        $pagedata['param']=$_GET;
        $this->assign($pagedata);
        $this->display();
    }

    /**
     * updateout
     */
    public function exportshowindex(){
        $lists=M()->query("SELECT o.OrderId,o.Price as OrderPrice,o.Freight,CONVERT(varchar(20),o.CreateDate,120) as CreateDate,CONVERT(varchar(20),o.PayDate,120) as PayDate,(CASE o.Status WHEN '2' THEN '已付款' WHEN '3' THEN '已发货' WHEN '4' THEN '已完成' WHEN '5' THEN '退款中' WHEN '10' THEN '已完成' END) AS Status,(CASE o.PayName WHEN 'T' THEN '微信支付' WHEN 'ALIPAY' THEN '支付宝' WHEN 'XJ' THEN '现金付款' WHEN 'POSXJ' THEN 'POS端现金付款' END) AS PayName,o.RecevingPost,o.RecevingProvince,o.RecevingCity,o.RecevingArea,o.RecevingAddress,o.RecevingPhone,o.RecevingName,ol.Spec,ol.ProName,ol.Price,ol.Count FROM RS_Order o LEFT JOIN RS_OrderList ol ON o.OrderId=ol.OrderId WHERE o.PayDate BETWEEN '{$_GET['start_time']}' and '{$_GET['end_time']}' and o.Status in (2,3,4,5,10) and o.token='{$this->token}' and o.stoken='{$this->stoken}'");
        $xlsName='orderlist'.date('YmdHis',time());
        $xlsCell=array(
            array('OrderId','订单号'),
            array('OrderPrice','订单额'),
            array('Freight','运费'),
            array('CreateDate','下单时间'),
            array('PayDate','付款时间'),
            array('Status','订单状态'),
            array('PayName','支付方式'),
            array('RecevingProvince','收货信息'),
            array('RecevingCity','收货信息'),
            array('RecevingArea','收货信息'),
            array('RecevingAddress','收货信息'),
            array('RecevingPhone','联系方式'),
            array('RecevingName','收货人'),
            array('ProName','商品名称'),
            array('Spec','规格'),
            array('Price','购买价格'),
            array('Count','购买数量')
        );
        exportExcel($xlsName,$xlsCell,$lists);
    }


    /**
     * 2018-01-17
     */
    public function getbuyermk(){
        $oid=$_POST['order_no'];
        $info=M()->table('RS_Order')->where("OrderId='%s'",$oid)->getField("MessageByBuy");
        if ($info) {
            $msg['status']='success';
            $msg['info']=$info;
        }else{
            $msg['status']='error';
            $msg['info']='暂无备注';
        }
        $this->ajaxReturn($msg);
    }

}

?>
