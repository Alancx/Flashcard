<?php
/**
 * User: 李艳波
 * Date: 15-8-28
 * Time: 下午4:30
 * Dec : 数据统计中心
 */
namespace Seller\Controller;
use \Think\Controller;

/**
 * 订单管理 查询
 */
class StatcenterController extends CommonController
{
    public $model;
    public function _initialize(){
        parent::_initialize();
        $this->model = M();
    }

    // + -------------------------------------------------------------
    // + 场景统计分析 sceneInfo 开始
    // + -------------------------------------------------------------
    public function sceneInfo(){
        $allScene=$this->model->table('RS_Scene')->where("token='%s'",$this->token)->select();
        $this->assign('allScene',$allScene);
        $pagesize=25;
        if (IS_GET) {
            // $datapro=$this->getPro();
            // $this->assign("datapro",$datapro);
            //1 、 图表信息
            $tempdata=$this->getmemberorderScene();
            $dataCount=count($tempdata);
            $this->assign("dataCount",$dataCount);
            if(count($dataCount)>0){
                foreach ($tempdata as $key=>$value) {
                    $outSceneName[$key]="'".$value["Name"]."'";
                    $outSceneCount[$key]=$value["orderCount"];
                    $outSceneMember[$key]=$value["memberCount"];
                }
                $this->assign("outSceneName",$outSceneName)->assign("outSceneCount",$outSceneCount)->assign("outSceneMember",$outSceneMember);
            }
            $pageindex=1;
            $order="DESC";
        }
        if(IS_POST){
            $pageindex=intval($_POST["pindex"]);
        }
        $start_time=isset($_POST["stime"])?trim($_POST["stime"]):"";
        $end_time=isset($_POST["etime"])?trim($_POST["etime"]):"";
        $sid=isset($_POST["sid"])?trim($_POST["sid"]):"";
        //2 、表格数据信息
        // var_dump($sid,$order);
        // var_dump($this->setSceneProcSQL(0,$pagesize,$pageindex,$start_time,$end_time,$sid,$order));
        $tblCount = $this->model->setIsProc(true)->query($this->setSceneProcSQL(0,$pagesize,$pageindex,$start_time,$end_time,$sid,$order));
        // var_dump($tblCount);
        // var_dump($this->setSceneProcSQL(0,$pagesize,$pageindex,$start_time,$end_time,$sid,$order));
        $count=$count=intval($tblCount[0]["num"]);   // 获取查询条数
        if($count>0){
            $dataMember=$this->model->setIsProc(true)->query($this->setSceneProcSQL(1,$pagesize,$pageindex,$start_time,$end_time,$sid,$order));
        }
        if(IS_POST){
            if($count>0){
                echo "{\"code\":\"0\",\"pageCount\":".$count.",\"totalPage\":".ceil($count / $pagesize).",\"dataMember\":".json_encode($dataMember)."}";
            }
            else
            {
                echo "{\"code\":\"1\"}";
            }
        }
        if(IS_GET){
            $this->assign("dataMember",$dataMember)->assign("pageCount",$count)->assign("totalPage",ceil($count / $pagesize))->display();
        }
    }

    public function sceneExport(){
        if(IS_GET){
            $start_time=isset($_GET["start_time"])?trim($_GET["start_time"]):"";
            $end_time=isset($_GET["end_time"])?trim($_GET["end_time"]):"";
            $sid='';
            $xlsName="scene_".date('ymdHm');
            $xlsCell = array(
                array('SceneName' , '场景名称'),
                array('MemberId' , '会员账号'),
                array('Sex' , '性别'),
                array('Birthday' ,'出生日期'),
                array('Province' , '会员省份'),
                array('City' , '会员城市'),
                array('Address' , '会员详细地址'),
                array('OrderMoney' , '消费订单额'),
                array('SceneMember' , '推广人账号'),
                array('RegDate' , '注册日期')
            );
            exportExcel($xlsName,$xlsCell,$this->model->setIsProc(true)->query($this->setSceneProcSQL(2,50,1,$start_time,$end_time,$sid,"DESC")));
        }
    }

    /**
     * 获取存储过程语句
    */
    private function setSceneProcSQL($getTotal,$pagesize=50,$pageindex=1,$start_time='',$end_time='',$sid='',$order='DESC')
    {
        // 查询 记录总数:@getTotal=0 导出=2 查询分页=1
        return <<<TBL
DECLARE @return_value int
EXEC    @return_value = [dbo].[P_MemberByScene_Pager]
        @start_time = N'{$start_time}',
        @end_time = N'{$end_time}',
        @sid = N'{$sid}',
        @pagesize = {$pagesize},
        @pageindex = {$pageindex},
        @getTotal = {$getTotal},
        @order = N'{$order}',
        @token = N'{$this->token}'
TBL;
    }

    /**
     * 获取场景订单关联数
    */
    public function getorderScene(){
        return $this->model->query("SELECT COUNT(b.OrderId) AS num,a.SceneName AS Name FROM RS_Scene a LEFT JOIN RS_Order b ON a.ID=b.SceneId GROUP BY a.SceneName HAVING Count(b.OrderId)>0 WHERE a.token='".$this->token."' ORDER BY num ");
    }

    /**
     * 获取会员场景关联数
    */
    public function getmemberScene(){
        return $this->model->query("SELECT COUNT(b.MemberId) AS num ,a.SceneName AS Name FROM RS_Scene a LEFT JOIN RS_Member b ON a.ID=b.SceneId GROUP BY a.SceneName HAVING Count(b.MemberId)>0 WHERE a.token='".$this->token."' ORDER BY num");
    }

    /**
     * 获取订单 会员 与场景管理数
    */
    public function getmemberorderScene(){
        return $this->model->query("SELECT ISNULL(a.num,0) AS memberCount,ISNULL(b.num,0) AS orderCount,a.Name FROM (SELECT COUNT(b.MemberId) AS num ,a.SceneName AS Name FROM RS_Scene a LEFT JOIN RS_Member b ON a.ID=b.SceneId GROUP BY a.SceneName HAVING Count(MemberId)>0) a LEFT JOIN (SELECT COUNT(b.OrderId) AS num,a.SceneName AS Name FROM RS_Scene a LEFT JOIN RS_Order b ON a.ID=b.SceneId  WHERE a.token='".$this->token."' GROUP BY a.SceneName HAVING Count(OrderId)>0) b
ON a.Name=b.Name");
    }

    /**
     * "获取场景信息
    */

    public function getScene(){
        return $this->model->query("SELECT ID AS Id,SceneName AS Name FROM RS_Scene  WHERE token='".$this->token."' ORDER BY Sort");
    }
    // + -------------------------------------------------------------
    // + 场景统计分析 结束
    // + -------------------------------------------------------------
    // + -------------------------------------------------------------
    // +  开始
    // + -------------------------------------------------------------


    // + -------------------------------------------------------------
    // +  结束
    // + -------------------------------------------------------------

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
        return $this->model->query("SELECT Name FROM RS_Logistics ORDER BY IsDefault DESC");
    }

    /**
     * 获取商品信息
    */
    public function getPro(){
        return $this->model->query("SELECT ProId,ProName FROM RS_Product WHERE IsShelves=1 ORDER BY ProName");
    }

    // + -------------------------------------------------------------
    // + 公共数据 结束
    // + -------------------------------------------------------------



    /**
     * leaves 2015-11-05 11:53增加数据
     * 运费统计
     */
    public function yunfei(){
        $pageCount=20;//每页条数
        $logistics=M()->table('RS_Logistics')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->select();
        $now=date('Y-m-d H:i:s',time());
        $stime=date('Y-m-01 00:00:00',time());
        $logs=M()->query("SELECT OrderId,CONVERT(varchar(100), ShipDate, 120) as ShipDate,Logistics,TrueFreight,LogisticsId FROM dbo.RS_Order WHERE Status in(3,4,6,7,10) and LogisticsId<>'' and token='".$this->token."' and stoken='".$this->stoken."' and ShipDate BETWEEN '".$stime."' and '".$now."' ORDER BY ShipDate desc");
        $zs=M()->query("SELECT SUM(truefreight)as zs FROM dbo.RS_Order WHERE Status in(3,4,6,7,10) and token='".$this->token."' and stoken='".$this->stoken."' and ShipDate BETWEEN '".$stime."' and '".$now."'");
        $count=count($logs);
        if ($count%$pageCount) {
            $i=1;
        }else{
            $i=0;
        }
        $page=floor($count/$pageCount)+$i;
        for ($i=0; $i < $pageCount; $i++) {
            if ($logs[$i]) {
                $mps[]=$logs[$i];
            }
        }
        $this->assign(array('members'=>json_encode($logs),'count'=>$count,'page'=>$page,'pageCount'=>$pageCount,'logistics'=>$logistics,'logs'=>$mps,'all'=>$zs,'statu'=>'no'));
        $this->display();
    }
    /**
     * 运费筛选
     */
    public function search(){
        $pageCount=20;//每页条数
        $logistics=M()->table('RS_Logistics')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->select();
        $now=$_POST['endtime'];
        $stime=$_POST['strtime'];
        $LogisticsCom=trim($_POST['logistics']);
        if ($LogisticsCom) {
            $sql="SELECT OrderId,CONVERT(varchar(100), ShipDate, 120) as ShipDate,Logistics,TrueFreight,LogisticsId FROM dbo.RS_Order WHERE Status in(3,4,6,7,10) and LogisticsId<>'' and token='".$this->token."' and stoken='".$this->stoken."' and LogisticsCom='".$LogisticsCom."' and ShipDate BETWEEN '".$stime."' and '".$now."'";
            $zs=M()->query("SELECT SUM(truefreight)as zs FROM dbo.RS_Order WHERE Status in(3,4,6,7,10) and token='".$this->token."' and stoken='".$this->stoken."' and LogisticsCom='".$LogisticsCom."' and ShipDate BETWEEN '".$stime."' and '".$now."'");
        }else{
            $sql="SELECT OrderId,CONVERT(varchar(100), ShipDate, 120) as ShipDate,Logistics,TrueFreight,LogisticsId FROM dbo.RS_Order WHERE Status in(3,4,6,7,10) and LogisticsId<>'' and token='".$this->token."' and stoken='".$this->stoken."' and  ShipDate BETWEEN '".$stime."' and '".$now."'";
            $zs=M()->query("SELECT SUM(truefreight)as zs FROM dbo.RS_Order WHERE Status in(3,4,6,7,10) and token='".$this->token."' and stoken='".$this->stoken."' and ShipDate BETWEEN '".$stime."' and '".$now."'");
        }

        $logs=M()->query($sql);
        if (!$logs) {
            $statu='ok';
        }else{
            $statu='no';
        }
        // var_dump($logs);exit();
        $count=count($logs);
        if ($count%$pageCount) {
            $i=1;
        }else{
            $i=0;
        }
        $page=floor($count/$pageCount)+$i;
        for ($i=0; $i < $pageCount; $i++) {
            if ($logs[$i]) {
                $mps[]=$logs[$i];
            }
        }
        $this->assign(array('members'=>json_encode($logs),'count'=>$count,'page'=>$page,'pageCount'=>$pageCount,'logistics'=>$logistics,'logs'=>$mps,'all'=>$zs,'statu'=>$statu));
        $this->display('yunfei');
    }

    /**
     * 运费查询数据导出
     */
    public function yunfeiOut(){
        $now=$_GET['etime'];
        $stime=$_GET['stime'];
        $LogisticsCom=trim($_GET['logistics']);
        if ($LogisticsCom) {
            $sql="SELECT OrderId,CONVERT(varchar(100), ShipDate, 120) as ShipDate,Logistics,TrueFreight,LogisticsId FROM dbo.RS_Order WHERE Status in(3,4,6,7,10) and LogisticsId<>'' and token='".$this->token."' and LogisticsCom='".$LogisticsCom."' and ShipDate BETWEEN '".$stime."' and '".$now."'";
            $zs=M()->query("SELECT SUM(truefreight)as zs FROM dbo.RS_Order WHERE Status in(3,4,6,7,10) and token='".$this->token."' and LogisticsCom='".$LogisticsCom."' and ShipDate BETWEEN '".$stime."' and '".$now."'");
        }else{
            $sql="SELECT OrderId,CONVERT(varchar(100), ShipDate, 120) as ShipDate,Logistics,TrueFreight,LogisticsId FROM dbo.RS_Order WHERE Status in(3,4,6,7,10) and LogisticsId<>'' and token='".$this->token."' and  ShipDate BETWEEN '".$stime."' and '".$now."'";
            $zs=M()->query("SELECT SUM(truefreight)as zs FROM dbo.RS_Order WHERE Status in(3,4,6,7,10) and token='".$this->token."' and ShipDate BETWEEN '".$stime."' and '".$now."'");
        }
        // var_dump($sql);exit();
        $logs=M()->query($sql);
        $xlsName=date('Y-m-d').'——'.'yunfei_list';
        $xlsCell  = array(
            array('OrderId','订单号'),
            array('Logistics','快递方式'),
            array('LogisticsId','快递单号'),
            array('TrueFreight','运费'),
            array('ShipDate','发货时间'),
            );
            // $tempData=M()->query("SELECT * FROM")
        $LogisticsCom=$LogisticsCom?$LogisticsCom:'无';
        $file_title='总运费：'.$zs[0]['zs']."  查询条件：快递名称：".$LogisticsCom."  查询时间：".$stime.'——'.$now;
        // var_dump($file_title);exit();
            // var_dump(M()->getlastsql());exit();
        exportExcel($xlsName,$xlsCell,$xlsData=$logs,$file_title);
    }




    /**
     * 支付核销
     */
    public function Cancels(){
        $week="[";
        $money="[";
        for ($i=7; $i > 0; $i--) { 
            $week.="'".date('Y-m-d',strtotime('-'.$i.' days'))."',";
            $money.="'".M()->table('RS_Order')->where("token='%s' and stoken='%s' and XJscantime BETWEEN '%s' and '%s'",array($this->token,$this->stoken,date('Y-m-d 00:00:00',strtotime('-'.$i.' days')),date('Y-m-d 23:59:59',strtotime('-'.$i.' days'))))->sum('Price')."',";
        }
        $week=substr($week, 0,strlen($week)-1);
        $money=substr($money, 0,strlen($money)-1);
        $week.="]";
        $money.="]";
        $pagedata['week']=$week;
        $pagedata['money']=$money;
       $count=count(M()->query("SELECT XJname FROM RS_Order WHERE XJscantime BETWEEN '".date('Y-m-01 00:00:00',time())."' AND '".date('Y-m-d H:i:s',time())."' AND token='".$this->token."' AND stoken='".$this->stoken."' GROUP BY XJname"));
         // var_dump(M()->getlastsql());exit();
      // var_dump($count);
        $pagesize=15;//分页大小
         $page=new \Think\Page($count,$pagesize);
         $userlists=M()->query("SELECT TOP ".$pagesize." s.storename,c.openid,c.id,c.username,CONVERT(float(53),SUM(o.Price),120) as price,COUNT(o.ID) as count FROM RS_Cancel AS c LEFT JOIN RS_Order as o ON c.openid=o.XJname LEFT JOIN RS_Store s on c.storeid=s.id WHERE c.id not in(select top ".$page->firstRow." id from RS_Cancel where stoken='".$this->stoken."') AND o.XJscantime BETWEEN '".date('Y-m-01 00:00:00',time())."' AND '".date('Y-m-d H:i:s',time())."' AND o.token='".$this->token."' AND o.stoken='".$this->stoken."' GROUP BY c.username,c.id,c.openid,s.storename ORDER BY c.id");
         // var_dump(M()->getlastsql());exit();
         $pagedata['CanType']='';
         $pagedata['title']='支付核销员管理';
         $pagedata['Ctype']='pay';
         $pagedata['userlists']=$userlists;
         $pagedata['page']=$page->show();
         $pagedata['stime']=date('Y-m-01 00:00:00',time());
         $pagedata['etime']=date('Y-m-d H:i:s',time());
         $pagedata['estime']=date('Y-m-d 23:59:59',strtotime('-1 days'));
         $this->assign($pagedata);
         $this->display();
     }

    /**
     * 提货核销
     */
    public function getcancels(){
        $week="[";
        $money="[";
        for ($i=7; $i > 0; $i--) { 
            $week.="'".date('Y-m-d',strtotime('-'.$i.' days'))."',";
            $money.="'".M()->table('RS_Order')->where("token='%s' and stoken='%s' and Cantime BETWEEN '%s' and '%s'",array($this->token,$this->stoken,date('Y-m-d 00:00:00',strtotime('-'.$i.' days')),date('Y-m-d 23:59:59',strtotime('-'.$i.' days'))))->sum('Price')."',";
        }
        $week=substr($week, 0,strlen($week)-1);
        $money=substr($money, 0,strlen($money)-1);
        $week.="]";
        $money.="]";
        $pagedata['week']=$week;
        $pagedata['money']=$money;
     $count=count(M()->query("SELECT ZTname FROM RS_Order WHERE Cantime BETWEEN '".date('Y-m-01 00:00:00',time())."' AND '".date('Y-m-d H:i:s',time())."' AND  token='".$this->token."' AND stoken='".$this->stoken."' GROUP BY ZTname"));
     // var_dump($count);
         $pagesize=15;//分页大小
         $page=new \Think\Page($count,$pagesize);
         $userlists=M()->query("SELECT TOP ".$pagesize." s.storename,c.openid,c.id,c.username,SUM(o.Price) as price,COUNT(o.ID) as count FROM RS_Cancel AS c LEFT JOIN RS_Order as o ON c.openid=o.ZTname LEFT JOIN RS_Store s on c.storeid=s.id WHERE c.id not in(select top ".$page->firstRow." id from RS_Cancel where stoken='".$this->stoken."') AND o.Cantime BETWEEN '".date('Y-m-01 00:00:00',time())."' AND '".date('Y-m-d H:i:s',time())."' AND o.token='".$this->token."' AND o.stoken='".$this->stoken."' GROUP BY c.username,c.id,c.openid,s.storename ORDER BY c.id");
        //  var_dump(M()->getlastsql());
         $pagedata['CanType']='';
         $pagedata['title']='提货核销员管理';
         $pagedata['Ctype']='get';
         $pagedata['userlists']=$userlists;
         $pagedata['page']=$page->show();
         $pagedata['estime']=date('Y-m-d 23:59:59',strtotime('-1 days'));
         $pagedata['stime']=date('Y-m-01 00:00:00',time());
         $pagedata['etime']=date('Y-m-d H:i:s',time());
         $this->assign($pagedata);
         $this->display('Cancels');
     }



         /**
          * 核销查询
          */
         public function searchcan(){
            if (IS_POST) {
                $tempData=$_POST;
            }else{
                $tempData=$_GET;
            }
            $whereStr="o.token='".$this->token."' and o.stoken='".$this->stoken."'";
            if ($tempData['CanType']=='pay') {
                $TypeName='XJname';
                $TypeTime='XJscantime';
            }else{
                $TypeName='XJname';
                $TypeTime='XJscantime';
            }
            $where['CanType']=$tempData['CanType'];
            if ($tempData['strtime']) {
                $whereStr.=" AND o.".$TypeTime." BETWEEN '".$tempData['strtime']."' and '".$tempData['endtime']."'";
                $where['strtime']=$tempData['strtime'];
                $where['endtime']=$tempData['endtime'];
            }
            if ($tempData['username']) {
                $whereStr.=" AND c.username like '%".$tempData['username']."%'";
                $where['username']=$tempData['username'];
            }
            if ($tempData['username']) {
                $count=M()->table('RS_Cancel')->where("token='%s' and stoken='%s' and username like '%%s%'",array($this->token,$this->stoken,$tempData['username']))->count();
            }else{
                $count=count(M()->query("SELECT s.storename,c.openid,c.id,c.username,SUM(o.Price) as price,COUNT(o.ID) as count FROM RS_Cancel AS c LEFT JOIN RS_Order as o ON c.openid=o.".$TypeName." LEFT JOIN RS_Store s on c.storeid=s.id WHERE  ".$whereStr." GROUP BY c.username,c.id,c.openid,s.storename ORDER BY c.id"));
            }
            $pagesize=15;//分页大小
            $page=new \Think\Page($count,$pagesize,$where);
            $userlists=M()->query("SELECT TOP ".$pagesize." s.storename,c.openid,c.id,c.username,SUM(o.Price) as price,COUNT(o.ID) as count FROM RS_Cancel AS c LEFT JOIN RS_Order as o ON c.openid=o.".$TypeName." LEFT JOIN RS_Store s on c.storeid=s.id WHERE c.id not in(select top ".$page->firstRow." id from RS_Cancel where stoken='".$this->token."') AND ".$whereStr." GROUP BY c.username,c.id,c.openid,s.storename ORDER BY c.id");
                // var_dump($userlists);
            if (!$userlists) {
                $pagedata['errmsg']='您查询的信息不存在!';
            }
            if ($CanType=='pay') {
                $pagedata['CanType']='';
                $pagedata['Ctype']='pay';
                $pagedata['title']='支付核销员管理';
            }else{
                $pagedata['CanType']='';
                $pagedata['Ctype']='get';
                $pagedata['title']='提货核销员管理';
            }
            $pagedata['stime']=$tempData['strtime'];
            $pagedata['etime']=$tempData['endtime'];
            $pagedata['userlists']=$userlists;
            $pagedata['page']=$page->show();
            $this->assign($pagedata);
            $this->display('Cancels');
        }

    /**
     * 核销数据导出
     */
    public function cancelsOut(){
        if (IS_POST) {
            $tempData=$_POST;
        }else{
            $tempData=$_GET;
        }
        $whereStr="o.token='".$this->token."' and o.stoken='".$this->stoken."'";
            // $where['token']=$this->token;
        if ($tempData['CanType']=='pay') {
            $TypeName='XJname';
            $TypeTime='XJscantime';
            $Cname="支付核销";
        }else{
            $TypeName='XJname';
            $TypeTime='XJscantime';
            $Cname="提货核销";
        }
        $where['CanType']=$tempData['CanType'];
        if ($tempData['strtime']) {
            $whereStr.=" AND o.".$TypeTime." BETWEEN '".$tempData['strtime']."' and '".$tempData['endtime']."'";
            $where['strtime']=$tempData['strtime'];
            $where['endtime']=$tempData['endtime'];
        }
        if ($tempData['username']) {
            $whereStr.=" AND c.username like '%".$tempData['username']."%'";
            $where['username']=$tempData['username'];
        }
        $userlists=M()->query("SELECT s.storename,c.openid,c.id,c.username,SUM(o.Price) as price,COUNT(o.ID) as count FROM RS_Cancel AS c LEFT JOIN RS_Order as o ON c.openid=o.".$TypeName." LEFT JOIN RS_Store s on c.storeid=s.id WHERE ".$whereStr." GROUP BY c.username,c.id,c.openid,s.storename ORDER BY c.id");
        // var_dump(M()->getlastsql());exit();
        $xlsName=date('Y-m-d').'——'.'Cancels_'.$tempData['CanType'];
        $xlsCell  = array(
            array('id','#'),
            array('storename','门店名称'),
            array('openid','核销员ID'),
            array('username','核销员姓名'),
            array('price','核销总额'),
            array('count','核销订单数'),
            );
            // $tempData=M()->query("SELECT * FROM")
        $LogisticsCom=$tempData['username']?$tempData['username']:'全部';
        $file_title="  查询条件：核销员：".$LogisticsCom."  查询类型：".$Cname."  查询时间：".$tempData['strtime'].'——'.$tempData['endtime'];
        // var_dump($file_title);exit();
            // var_dump(M()->getlastsql());exit();
        exportExcel($xlsName,$xlsCell,$xlsData=$userlists,$file_title);
    }


    /**
     * 提现数据统计
     */
    public function redpaper(){
        $count=M()->table('RS_Drawmoneylist')->where("token='%s' and Status=%d and IsSuccess=%d",array($this->token,3,1))->count();
        $pagedata['allmoney']=$allmoney=M()->table('RS_Drawmoneylist')->where("token='%s' and Status=%d and IsSuccess=%d AND EndDate>'".date('Y-m-d H:i:s',strtotime('-7 days'))."'",array($this->token,3,1))->sum('Money');
        $page=new \Think\Page($count,20);
        $lists=M()->table('RS_Drawmoneylist')->where("token='%s' and Status=%d and IsSuccess=%d",array($this->token,3,1))->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($lists as &$list) {
            $ctime=$list['CreateDate'];
            foreach ($ctime as $ck => $cv) {
                if ($ck=='date') {
                    $list['CreateDate']=substr($cv, 0,19);
                }
            }
            $etime=$list['EndDate'];
            foreach ($etime as $ek => $ev) {
                if ($ek=='date') {
                    $list['EndDate']=substr($ev, 0,19);
                }
            }
        }
        $money="[";
        $money.=M()->query("SELECT COUNT('Money') as count FROM RS_Drawmoneylist WHERE EndDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-6 day'))."' AND '".date('Y-m-d 23:59:59',strtotime('-6 day'))."' AND Status=3 AND IsSuccess=1")[0]['count'].",";
        $money.=M()->query("SELECT COUNT('Money') as count FROM RS_Drawmoneylist WHERE EndDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-5 day'))."' AND '".date('Y-m-d 23:59:59',strtotime('-5 day'))."' AND Status=3 AND IsSuccess=1")[0]['count'].",";
        $money.=M()->query("SELECT COUNT('Money') as count FROM RS_Drawmoneylist WHERE EndDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-4 day'))."' AND '".date('Y-m-d 23:59:59',strtotime('-4 day'))."' AND Status=3 AND IsSuccess=1")[0]['count'].",";
        $money.=M()->query("SELECT COUNT('Money') as count FROM RS_Drawmoneylist WHERE EndDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-3 day'))."' AND '".date('Y-m-d 23:59:59',strtotime('-3 day'))."' AND Status=3 AND IsSuccess=1")[0]['count'].",";
        $money.=M()->query("SELECT COUNT('Money') as count FROM RS_Drawmoneylist WHERE EndDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-2 day'))."' AND '".date('Y-m-d 23:59:59',strtotime('-2 day'))."' AND Status=3 AND IsSuccess=1")[0]['count'].",";
        $money.=M()->query("SELECT COUNT('Money') as count FROM RS_Drawmoneylist WHERE EndDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-1 day'))."' AND '".date('Y-m-d 23:59:59',strtotime('-1 day'))."' AND Status=3 AND IsSuccess=1")[0]['count'].",";
        $money.=M()->query("SELECT COUNT('Money') as count FROM RS_Drawmoneylist WHERE EndDate BETWEEN '".date('Y-m-d 00:00:00',time())."' AND '".date('Y-m-d 23:59:59',time())."' AND Status=3 AND IsSuccess=1")[0]['count']."]";
        $WEEK="['".date('Y-m-d',strtotime('-6 day'))."','".date('Y-m-d',strtotime('-5 day'))."','".date('Y-m-d',strtotime('-4 day'))."','".date('Y-m-d',strtotime('-3 day'))."','".date('Y-m-d',strtotime('-2 day'))."','".date('Y-m-d',strtotime('-1 day'))."','".date('Y-m-d',time())."']";
        $pagedata['lists']=$lists;
        $pagedata['page']=$page->show();
        $pagedata['week']=$WEEK;
        $pagedata['money']=$money;
        $pagedata['etime']=date('Y-m-d H:i:s',time());
        $this->assign($pagedata);
        $this->display();
    }

    /**
     * 会员提现统计查询
     */
    public function searred(){
        $MemberId=$_GET['username'];
        $stime=$_GET['stime'];
        if ($stime) {
            $where['stime']=$stime;
            $whereStr="EndDate BETWEEN '".$stime." 00:00:00' AND '".$stime." 23:59:59' AND Status=3 AND IsSuccess=1 AND token='".$this->token."'";
        }else{
            $whereStr="Status=3 AND IsSuccess=1 AND token='".$this->token."'";

        }
        $where['Status']=3;
        $where['IsSuccess']=1;
        $where['token']=$this->token;
        if ($MemberId) {
            $where['username']=$MemberId;
            $whereStr.=" AND MemberId like '%".$MemberId."%'";
        };
        $count=M()->table('RS_Drawmoneylist')->where($whereStr)->count();
        $allmoney=M()->table('RS_Drawmoneylist')->where($whereStr)->sum('Money');
        $page=new \Think\Page($count,20,$where);
        $lists=M()->table('RS_Drawmoneylist')->where($whereStr)->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($lists as &$list) {
            $ctime=$list['CreateDate'];
            foreach ($ctime as $ck => $cv) {
                if ($ck=='date') {
                    $list['CreateDate']=substr($cv, 0,19);
                }
            }
            $etime=$list['EndDate'];
            foreach ($etime as $ek => $ev) {
                if ($ek=='date') {
                    $list['EndDate']=substr($ev, 0,19);
                }
            }
        }
        // var_dump($count);
        $pagedata['allmoney']=$allmoney;
        $pagedata['count']=$count;
        $pagedata['page']=$page->show();
        $pagedata['lists']=$lists;
        $this->assign($pagedata);
        $this->display();
    }
    /**
     * 提现明细查看
     */
    public function showred(){
        $id=$_GET['id'];
        $info=M()->table('RS_Drawmoneylist')->where("ID=%d",array($id))->find();
        $ctime=$info['CreateDate'];
        foreach ($ctime as $key => $value) {
            if ($key=='date') {
                $info['CreateDate']=substr($value, 0,19);
            }
        }
        $chtime=$info['CheckDate'];
        foreach ($chtime as $key => $value) {
            if ($key=='date') {
                $info['CheckDate']=substr($value, 0,19);
            }
        }
        $etime=$info['EndDate'];
        foreach ($etime as $key => $value) {
            if ($key=='date') {
                $info['EndDate']=substr($value, 0,19);
            }
        }
        $this->assign('info',$info);
        $this->display();
    }
    /**
     * 导出提现记录
     */
    public function expred(){
        $xlsName  = 'redpaper_record_list';
        $xlsModel = M()->table('RS_Drawmoneylist');

        $xlsData  = $xlsModel->where("token='%s' and Status=%d and IsSuccess=%d",array($this->token,3,1))->Field('ID,MemberId,GetName,Money,CreateDate,CheckDate,EndDate')->select();
        foreach ($xlsData as $key => &$value) {
            $ctime=$value['CreateDate'];
            foreach ($ctime as $ck => $cv) {
                if ($ck=='date') {
                    $value['ctime']=substr($cv, 0,19);
                }
            }
            $chtime=$value['CheckDate'];
            foreach ($chtime as $chk => $chv) {
                if ($chk=='date') {
                    $value['chtime']=substr($chv, 0,19);
                }
            }
            $etime=$value['EndDate'];
            foreach ($etime as $ek => $ev) {
                if ($ek=='date') {
                    $value['etime']=substr($ev, 0,19);
                }
            }
        }
        $xlsCell  = array(
            array('ID','#'),
            array('MemberId','提现人账号'),
            array('GetName','提现人姓名'),
            array('Money','提现金额'),
            array('ctime','申请时间'),
            array('chtime','审核时间'),
            array('etime','处理时间'),
            );
            // $tempData=M()->query("SELECT * FROM")
        $file_title='总额：'.M()->table('RS_Drawmoneylist')->where("token='%s' and Status=%d and IsSuccess=%d",array($this->token,3,1))->sum('Money');
            // var_dump(M()->getlastsql());exit();
        exportExcel($xlsName,$xlsCell,$xlsData,$file_title);
    }

    /**
     * 场景销售统计
     */
    public function SceneSale(){
        if (IS_POST) {
            $tempData=$_POST;
        }else{
            $tempData=$_GET;
        }
        $whereStr="token='".$this->token."'";
        if ($tempData['sid']) {
            $whereStr.=' AND SceneId='.$tempData['sid'];
            $Pram['sid']=$tempData['sid'];
        };
        if ($tempData['PayName']) {
            $whereStr.=" AND PayName='".$tempData['PayName']."'";
            $Pram['PayName']=$tempData['PayName'];
        }
        if ($tempData['strtime']) {
            $whereStr.=" AND PayDate BETWEEN '".$tempData['strtime']." 00:00:00' AND '".$tempData['endtime']." 00:00:00'";
            $Pram['strtime']=$tempData['strtime'];
            $Pram['endtime']=$tempData['endtime'];
        }
            // var_dump($whereStr);
        if ($tempData) {
            $pagedata['SceneName']=$this->model->table('RS_Scene')->where('ID=%d',$tempData['sid'])->getField('SceneName');
            $count=$this->model->table('RS_Order')->where($whereStr)->count();
            $page= new \Think\Page($count,20,$Pram);
            $lists=$this->model->table('RS_Order')->where($whereStr)->field("OrderId,MemberId,PayName,CONVERT(varchar(100), PayDate, 120) as PayDate,Price")->limit($page->firstRow.','.$page->listRows)->select();
            if (!$lists) {
                $pagedata['errmsg']='您查询的信息不存在';
            }
            $pagedata['lists']=$lists;
            $pagedata['page']=$page->show();
            $pagedata['allmoney']=$this->model->table('RS_Order')->where($whereStr)->sum('Price');
            $pagedata['count']=$count;
            $pagedata['data']=$tempData;
        }
        $pagedata['allScene']=$this->model->table('RS_Scene')->where("token='%s'",$this->token)->select();
        $this->assign($pagedata);
        $this->display();
    }

    /**
     * 付款方式统计
     */
    public function PayType(){
        if (IS_POST) {
            $tempData=$_POST;
        }else{
            $tempData=$_GET;
        }
        $whereStr="token='".$this->token."' and stoken='".$this->stoken."' and Status in (2,3,4,5,10)";
        if ($tempData['PayName']) {
            $whereStr.=" AND PayName='".$tempData['PayName']."'";
            $Pram['PayName']=$tempData['PayName'];
        }
        if ($tempData['strtime']) {
            $whereStr.=" AND PayDate BETWEEN '".$tempData['strtime']."' AND '".$tempData['endtime']."'";
            $Pram['strtime']=$tempData['strtime'];
            $Pram['endtime']=$tempData['endtime'];
        }
        if ($tempData) {
            if (!$tempData['PayName']) {  //如果没有选择付款类型
                $tempAry=$this->model->query("SELECT PayName,CONVERT(float(53),SUM(Price),120) as price,COUNT(*) as count FROM RS_Order WHERE ".$whereStr." GROUP BY PayName");
                if ($tempAry) {
                    $count="[";
                    $money="[";
                    $type="[";
                    $allmoney=0;
                    foreach ($tempAry as $temp) {
                        $allmoney+=$temp['price'];
                        $count.="'".$temp['count']."',";
                        $money.="'".$temp['price']."',";
                        switch ($temp['PayName']) {
                                               case 'T':
                                                   $type.="'微信支付',";
                                                   break;
                                               case 'XJ':
                                                   $type.="'现金支付',";
                                                   break;
                                               case 'POSXJ':
                                                   $type.="'POS端現金支付',";
                                                   break;
                                                case 'ALIPAY':
                                                    $type.="'支付宝付款',";
                                                    break;
                                               default:
                                                   $type.="'其他',";
                                                   break;
                                           }                   
                    }
                    $count=substr($count, 0,strlen($count)-1);
                    $count.="]";
                    $money=substr($money, 0,strlen($money)-1);
                    $money.="]";
                    $type=substr($type, 0,strlen($type)-1);
                    $type.="]";
                }else{
                    $money="['0','0','0','0']";
                    $count="['0','0','0','0']";
                    $type ="['微信支付','现金支付','支付宝付款','POS端現金支付']";
                    $allmoney=0;
                }
                // echo "<pre>";
                // var_dump($tempAry);exit();
                $lists=$this->model->table('RS_Order')->where($whereStr)->field("OrderId,CONVERT(float(53),Price,120) as Price,MemberId,PayName,CONVERT(varchar(100), PayDate, 120) as PayDate")->limit('0,19')->select();
                foreach ($tempAry as &$value) {
                    $value['sons']=$this->model->table('RS_Order')->where($whereStr." AND PayName='".$value['PayName']."'")->field("OrderId,Price,MemberId,PayName,CONVERT(varchar(100), PayDate, 120) as PayDate")->limit('0,19')->select();
                }
                // echo "<pre>";
                // var_dump($tempA);exit();
                $this->assign('tempAry',$tempAry);
            }else{
                $count=$this->model->table('RS_Order')->where($whereStr)->count();
                $page = new \Think\Page($count,20,$Pram);
                $lists=$this->model->table('RS_Order')->where($whereStr)->field("OrderId,CONVERT(float(53),Price,120) as Price,MemberId,PayName,CONVERT(varchar(100), PayDate, 120) as PayDate")->limit($page->firstRow.','.$page->listRows)->select();
                // file_put_contents('2xtxt', M()->getlastsql(),FILE_APPEND);
                $pagedata['lists']=$lists;
                $pagedata['count']=$count;
                $pagedata['allmoney']=$allmoney=$this->model->table('RS_Order')->where($whereStr)->sum('Price');
                $count="['".$count."']";
                $money="['".$allmoney."']";
                switch ($tempData['PayName']) {
                    case 'T':
                        $type="微信支付";
                        break;
                    case 'XJ':
                        $type="现金支付";
                        break;
                    case 'POSXJ':
                        $type="POS端現金支付";
                        break;
                    case 'ALIPAY':
                        $type="支付宝付款";
                        break;
                    default:    
                        $type="其他";
                        break;
                }
                $type="['".$type."']";
                $pagedata['page']=$page->show();
                // var_dump($whereStr);
            }
            $pagedata['allmoney']=$allmoney;
            $pagedata['data']=$tempData;
        }else{
            $today=date('Y-m-d',time());
            $lists=$this->model->table('RS_Order')->where("token='%s' and stoken='%s' and PayDate BETWEEN '%s' and '%s' and Status in %s",array($this->token,$this->stoken,$today.' 00:00:00',$today.' 23:59:59','(2,3,4,5,10)'))->field("OrderId,PayName,Price,MemberId,CONVERT(varchar(100), PayDate, 120) as PayDate")->limit('0,19')->select();
            // var_dump(M()->getlastsql());
            $tempAry=$this->model->query("SELECT PayName,CONVERT(float(53),SUM(Price),120) as price,COUNT(*) as count FROM RS_Order WHERE token='".$this->token."' and stoken='".$this->stoken."' and PayDate BETWEEN '".$today." 00:00:00' and '".$today." 23:59:59' and Status in (2,3,4,5,10) GROUP BY PayName");
            if ($tempAry) {
                $count="[";
                $money="[";
                $type="[";
                $allmoney=0;
                foreach ($tempAry as $temp) {
                    $allmoney+=$temp['price'];
                    $count.="'".$temp['count']."',";
                    $money.="'".$temp['price']."',";
                    switch ($temp['PayName']) {
                                           case 'T':
                                               $type.="'微信支付',";
                                               break;
                                           case 'XJ':
                                               $type.="'现金支付',";
                                               break;
                                           case 'POSXJ':
                                               $type.="'POS端現金支付',";
                                               break;
                                           case 'ALIPAY':
                                               $type.="'支付宝付款',";
                                               break;
                                           default:
                                               $type.="'其他',";
                                               break;
                                       }                   
                }
                $count=substr($count, 0,strlen($count)-1);
                $count.="]";
                $money=substr($money, 0,strlen($money)-1);
                $money.="]";
                $type=substr($type, 0,strlen($type)-1);
                $type.="]";
            }else{
                $money="['0','0','0','0']";
                $count="['0','0','0','0']";
                $type ="['微信支付','现金支付','支付宝付款','POS端現金支付']";
                $allmoney=0;
            }
            $pagedata['allmoney']=$allmoney;
            foreach ($tempAry as &$value) {
                $value['sons']=$this->model->table('RS_Order')->where("token='%s' and stoken='%s' and PayName='%s' and PayDate BETWEEN '%s' and '%s' and Status in %s",array($this->token,$this->stoken,$value['PayName'],$today.' 00:00:00',$today.' 23:59:59','(2,3,4,5,10)'))->field("OrderId,PayName,CONVERT(float(53),Price,120) as Price,MemberId,CONVERT(varchar(100), PayDate, 120) as PayDate")->limit('0,19')->select();
            }
            // echo "<pre>";
            // var_dump($lists,$tempAry);
            $this->assign('tempAry',$tempAry);
            $data['strtime']=$today.' 00:00:00';
            $data['endtime']=$today.' 23:59:59';
            $this->assign('data',$data);
        }
        $pagedata['counts']=$count;
        $pagedata['moneys']=$money;
        $pagedata['types']=$type;
        $this->assign($pagedata);
        $this->display();
    }

    /**
     * 付款方式统计ajax加载数据
     */
    public function getMore(){
        $tempData=$_POST;
        $whereStr="token='".$this->token."' and stoken='".$this->stoken."' and Status in (2,3,4,5,10)";
        if ($tempData['PayName']) {
            $whereStr.=" AND PayName='".$tempData['PayName']."'";
        }
        if ($tempData['stime']) {
            $whereStr.=" AND PayDate BETWEEN '".$tempData['stime']." 00:00:00' AND '".$tempData['etime']." 23:59:59'";
        }
        $page=intval($tempData['page']);
        $count=$this->model->query("SELECT COUNT(OrderId) as count FROM RS_Order WHERE OrderId NOT IN (SELECT TOP ".$page." OrderId FROM RS_Order WHERE ".$whereStr.") AND ".$whereStr)[0]['count'];
        // file_put_contents('1.txt', M()->getlastsql());
        // var_dump($count);exit();
        if ($count>0) {
            $data=$this->model->table('RS_Order')->where($whereStr)->limit($page.',20')->field("OrderId,PayName,CONVERT(float(53),Price,120) as Price,MemberId,CONVERT(varchar(100), PayDate, 120) as PayDate")->select();
            // file_put_contents('1xtx', M()->getlastsql());
            $json['statu']='success';
            $json['data']=$data;
        }else{
            $json['statu']='error';
            $json['info']='nomore';
        }
        echo json_encode($json);
    }

    //场景销售数据导出
    public function SceneOut(){
        $tempData=$_GET;
        // var_dump($tempData);
        $Pram='';
        $whereStr=" token='".$this->token."' and stoken='".$this->stoken."'";
        if ($tempData['sid']) {
            $whereStr.=" AND SceneId='".$tempData['sid']."'";
            $Pram.='场景名称：'.$this->model->table('RS_Scene')->where('ID=%d',$tempData['sid'])->getField('SceneName');

        }
        if ($tempData['PayName']) {
            $whereStr.=" AND PayName='".$tempData['PayName']."'";
            switch ($tempData['PayName']) {
                case 'XJ':
                $PayName='现金支付';
                break;
                case 'T':
                $PayName='微信支付';
                break;
                case 'POSXJ':
                $PayName='POS端現金支付';
                break;
                default:
                $PayName='其他';
                break;
            }
            $Pram.="  支付方式：".$PayName;
        }
        if ($tempData['stime']) {
            $whereStr.=" AND PayDate BETWEEN '".$tempData['stime']." 00:00:00' AND '".$tempData['etime']." 23:59:59'";
            $Pram.="  查询时间：".$tempData['stime']." 00:00:00——".$tempData['etime']." 23:59:59   ";
        }
        $xlsName  = date('Y-m-d').'——SceneSale_list';
        $xlsData  = $this->model->query("SELECT OrderId,MemberId,(CASE WHEN PayName='XJ' THEN '现金支付' ELSE (CASE WHEN PayName='T' THEN '微信支付' ELSE(CASE WHEN PayName='YE' THEN '余额支付' ELSE(CASE WHEN PayName='JL' THEN '奖励余额支付' ELSE '其他' END) END) END) END) as PayName,CONVERT(varchar(100), PayDate, 120) as PayDate,Price FROM RS_Order WHERE ".$whereStr);
        // file_put_contents('1xtx', M()->getlastsql());
        $xlsCell  = array(
            array('OrderId','订单号'),
            array('MemberId','会员账号'),
            array('PayName','支付方式'),
            array('Price','支付金额'),
            array('PayDate','付款时间'),
            );
        $count=$this->model->table('RS_Order')->where($whereStr)->count();
        $allmoney=$this->model->table('RS_Order')->where($whereStr)->sum('Price');
        $file_title='场景销售统计数据  总订单数：'.$count."   总订单额：".$allmoney."查询条件：".$Pram;
            // var_dump(M()->getlastsql());exit();
        exportExcel($xlsName,$xlsCell,$xlsData,$file_title);
    }

    /**
     * 付款方式数据导出
     */
    public function PayOut(){
        $tempData=$_GET;
        // var_dump($tempData);
        $Pram='';
        $whereStr=" token='".$this->token."' and stoken='".$this->stoken."' and Status in (2,3,4,5,10)";
        if ($tempData['PayName']) {
            $whereStr.=" AND PayName='".$tempData['PayName']."'";
            switch ($tempData['PayName']) {
                case 'XJ':
                $PayName='现金支付';
                break;
                case 'T':
                $PayName='微信支付';
                break;
                case 'POSXJ':
                $PayName='POS端現金支付';
                break;
                case 'ALIPAY':
                    $PayName='支付宝付款';
                    break;
                default:
                $PayName='其他';
                break;
            }
            $Pram.="  支付方式：".$PayName;
        }
        if ($tempData['stime']) {
            $whereStr.=" AND PayDate BETWEEN '".$tempData['stime']."' AND '".$tempData['etime']."'";
            $Pram.="  查询时间：".$tempData['stime']."——".$tempData['etime'];
        }
        $xlsName  = date('Y-m-d').'——PayType_list';
        $xlsData  = $this->model->query("SELECT OrderId,MemberId,(CASE WHEN PayName='XJ' THEN '现金支付' ELSE (CASE WHEN PayName='T' THEN '微信支付' ELSE(CASE WHEN PayName='ALIPAY' THEN '支付宝付款' ELSE(CASE WHEN PayName='POSXJ' THEN 'POS端現金支付' ELSE '其他' END) END) END) END) as PayName,CONVERT(varchar(100), PayDate, 120) as PayDate,Price FROM RS_Order WHERE ".$whereStr."ORDER BY PayName");
        // file_put_contents('1xtx', M()->getlastsql());
        $xlsCell  = array(
            array('OrderId','订单号'),
            array('MemberId','会员账号'),
            array('PayName','支付方式'),
            array('Price','支付金额'),
            array('PayDate','付款时间'),
            );
        $count=$this->model->table('RS_Order')->where($whereStr)->count();
        $allmoney=$this->model->table('RS_Order')->where($whereStr)->sum('Price');
        $file_title='支付方式统计数据 总订单数：'.$count."   总订单额：".$allmoney."查询条件：".$Pram;
            // var_dump(M()->getlastsql());exit();
        exportExcel($xlsName,$xlsCell,$xlsData,$file_title);
    }

    /**
     * pos收银统计
     */
    public function poscash(){
        $pagedata['storelist']=$this->model->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->select();
        if (IS_POST) {
            $tempData=$_POST;
        }else{
            $tempData=$_GET;
        }
        if ($tempData) {
            if ($tempData['PayName']) {
                $whereStr="token='".$this->token."' AND stoken='".$this->stoken."' AND SceneContent='STOREID' AND Status<>'11'";
                $whereStr.=" AND RecevingName='".$tempData['PayName']."'";
                if ($tempData['stime']) {
                    $whereStr.=" AND PayDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
                }
                $pagedata['data']=$tempData;
                $count=$this->model->table('RS_Order')->where($whereStr)->count();
                $page= new \Think\Page($count,20,$tempData);
                $pagedata['lists']=$this->model->table("RS_Order")->where($whereStr)->limit($page->firstRow.','.$page->listRows)->field("OrderId,Count,Price,CONVERT(varchar(100),PayDate,120) as PayDate")->select();
                $pagedata['count']=$count;
                $pagedata['allmoney']=$allmoney=$this->model->table('RS_Order')->where($whereStr)->SUM('Price');
                $pagedata['page']=$page->show();
                $pagedata['storename']=$this->model->table('RS_Store')->where("id=%d",$tempData['PayName'])->getField('storename');
                $pagedata['types']="['".$tempData['PayName']."']";
                $pagedata['counts']="['".$count."']";
                $pagedata['moneys']="['".$allmoney."']";
                $pagedata['allmoneys']=$allmoney;
            }else{
                $whereStr="o.token='".$this->token."' and o.stoken='".$this->stoken."' AND o.SceneContent='STOREID' AND o.RecevingName<>'' AND Status<>'11'";
                if ($tempData['stime']) {
                    $whereStr.=" AND o.PayDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
                }
                $tempData['store']="all";
                $pagedata['data']=$tempData;
                $tempAry=$this->model->query("SELECT COUNT(o.OrderId) as count,SUM(o.Price) as Price,o.RecevingName,s.storename FROM RS_Order o LEFT JOIN RS_Store s ON o.RecevingName=s.id WHERE ".$whereStr." GROUP BY o.RecevingName,s.storename");
                if ($tempAry) {
                    $tempCount="[";
                    $tempMoney="[";
                    $tempType="[";
                    $tempAll=0;
                    foreach ($tempAry as $temp) {
                        $tempCount.="'".$temp['count']."',";
                        $tempMoney.="'".$temp['Price']."',";
                        $tempType.="'".$temp['storename']."',";
                        $tempAll+=$temp['Price'];
                    }
                    $tempCount=substr($tempCount, 0,strlen($tempCount)-1);
                    $tempMoney=substr($tempMoney, 0,strlen($tempMoney)-1);
                    $tempType=substr($tempType, 0,strlen($tempType)-1);
                    $tempCount.="]";
                    $tempMoney.="]";
                    $tempType.="]";
                }else{
                    $tempCount="['0']";
                    $tempMoney="['0']";
                    $tempType="['0']";
                    $tempAll=0;
                }
                foreach ($tempAry as &$temp) {
                    $temp['sons']=$this->model->query("SELECT TOP 19 OrderId,Count,Price,CONVERT(varchar(100),PayDate,120) as PayDate FROM RS_Order WHERE token='".$this->token."' AND stoken='".$this->stoken."' AND SceneContent='STOREID' AND RecevingName='".$temp['RecevingName']."' AND PayDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'");
                }
                $pagedata['tempAry']=$tempAry;
                $pagedata['counts']=$tempCount;
                $pagedata['moneys']=$tempMoney;
                $pagedata['types']=$tempType;
                $pagedata['allmoneys']=$tempAll;
            }
        }else{
            $stime=date('Y-m-d 00:00:00',strtotime('-30 days'));
            $etime=date('Y-m-d 23:59:59',time());
            $pagedata['data']['stime']=$stime;
            $pagedata['data']['etime']=$etime;
            $pagedata['data']['store']="all";
            $tempAry=$this->model->query("SELECT COUNT(o.OrderId) as count,SUM(o.Price) as Price,o.RecevingName,s.storename FROM RS_Order o LEFT JOIN RS_Store s ON o.RecevingName=s.id WHERE o.token='".$this->token."' AND o.stoken='".$this->stoken."' AND o.SceneContent='STOREID' AND o.RecevingName<>'' AND o.PayDate BETWEEN '".$stime."' and '".$etime."' GROUP BY o.RecevingName,s.storename");
            // var_dump($tempAry);exit();
            if ($tempAry) {
                $tempCount="[";
                $tempMoney="[";
                $tempType="[";
                $tempAll=0;
                foreach ($tempAry as $temp) {
                    $tempCount.="'".$temp['count']."',";
                    $tempMoney.="'".$temp['Price']."',";
                    $tempType.="'".$temp['storename']."',";
                    $tempAll+=$temp['Price'];
                }
                $tempCount=substr($tempCount, 0,strlen($tempCount)-1);
                $tempMoney=substr($tempMoney, 0,strlen($tempMoney)-1);
                $tempType=substr($tempType, 0,strlen($tempType)-1);
                $tempCount.="]";
                $tempMoney.="]";
                $tempType.="]";
            }else{
                $tempCount="['0']";
                $tempMoney="['0']";
                $tempType="['0']";
                $tempAll=0;
            }
            foreach ($tempAry as &$temp) {
                $temp['sons']=$this->model->query("SELECT TOP 19 OrderId,Count,Price,CONVERT(varchar(100),PayDate,120) as PayDate FROM RS_Order WHERE token='".$this->token."' AND stoken='".$this->stoken."' AND SceneContent='STOREID' AND RecevingName='".$temp['RecevingName']."' AND PayDate BETWEEN '".$stime."' and '".$etime."' AND Status<>'11'");
            }
            $pagedata['tempAry']=$tempAry;
            $pagedata['counts']=$tempCount;
            $pagedata['moneys']=$tempMoney;
            $pagedata['types']=$tempType;
            $pagedata['allmoneys']=$tempAll;
            // var_dump($tempAry);exit();

        }
        $this->assign($pagedata);
        $this->display();
    }

    /**
     * ajax 加载更多
     */
    public function getpos(){
        $tempData=$_POST;
        $whereStr="token='".$this->token."' AND stoken='".$this->stoken."' AND  SceneContent='STOREID' AND Status<>'11'";
        if ($tempData['PayName']) {
            $whereStr.=" AND RecevingName='".$tempData['PayName']."'";
        }
        if ($tempData['stime']) {
            $whereStr.=" AND PayDate BETWEEN '".$tempData['stime']."' AND '".$tempData['etime']."'";
        }
        $page=intval($tempData['page']);
        $count=$this->model->query("SELECT COUNT(OrderId) as count FROM RS_Order WHERE OrderId NOT IN (SELECT TOP ".$page." OrderId FROM RS_Order WHERE ".$whereStr.") AND ".$whereStr)[0]['count'];
        // echo M()->getlastsql();exit();
        if ($count>0) {
            $data=$this->model->table('RS_Order')->where($whereStr)->limit($page.',20')->field("OrderId,Price,Count,CONVERT(varchar(100), PayDate, 120) as PayDate")->select();
            $json['statu']='success';
            $json['data']=$data;
        }else{
            $json['statu']='error';
            $json['info']='nomore';
        }
        echo json_encode($json);
    }

    /**
     * pos收银统计导出
     */
    public function posOut(){
        $tempData=$_GET;
        if ($tempData['PayName']) {
            $whereStr="token='".$this->token."' AND stoken='".$this->stoken."' AND SceneContent='STOREID' AND Status<>'11'";
            $whereStr.=" AND RecevingName='".$tempData['PayName']."'";
            if ($tempData['stime']) {
                $whereStr.=" AND PayDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
            }
            $pagedata=$this->model->table("RS_Order")->where($whereStr)->field("OrderId,Count,Price,CONVERT(varchar(100),PayDate,120) as PayDate")->select();
             $xlsCell  = array(
                array('OrderId','订单号'),
                array('Count','数量'),
                array('Price','支付金额'),
                array('PayDate','付款时间'),
                );
        }else{
            $whereStr="token='".$this->token."' AND stoken='".$this->stoken."' AND SceneContent='STOREID' AND RecevingName<>''";
            if ($tempData['stime']) {
                $whereStr.=" AND PayDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
            }
            $pagedata=$this->model->table("RS_Order")->where($whereStr)->field("OrderId,RecevingName,Count,Price,CONVERT(varchar(100),PayDate,120) as PayDate")->Order('RecevingName')->select();
            foreach ($pagedata as &$data) {
                $data['storename']=$this->model->table('RS_Store')->where("id=%d",$data['RecevingName'])->getField('storename');
            }
            $xlsCell  = array(
                array('OrderId','订单号'),
                array('Count','数量'),
                array('Price','支付金额'),
                array('PayDate','付款时间'),
                array('storename','门店名称'),
                );
        }
        $xlsName=date('Y-m-d H:i:s',time())."pos_data";
        exportExcel($xlsName,$xlsCell,$xlsData=$pagedata);
    }

    /**
     * 赠品统计
     */
    public function zpdata(){
        if (IS_POST) {
            $tempData=$_POST;
        }else{
            $tempData=$_GET;
        }
        $whereStr="o.token='".$this->token."' and ol.Iszp='1'";
        if ($tempData) {
            if ($tempData['strtime']) {
                $whereStr.=" AND ol.CreateDate BETWEEN '".$tempData['strtime']."' and '".$tempData['endtime']."'";
            }
            $pagedata['data']=$tempData;
            $count=$this->model->query("SELECT COUNT(ol.OrderListId) as count FROM RS_OrderList as ol LEFT JOIN RS_Order AS o ON ol.OrderId=o.OrderId WHERE ".$whereStr)[0]['count'];
            // echo M()->getlastsql();
            // $count=$this->model->table('RS_OrderList')->where($whereStr)->count();
            $pagedata['count']=$count;
            $page= new \Think\Page($count,20,$tempData);
            $lists=$this->model->query("SELECT TOP 20 ol.OrderId,ol.Count,p.ProName,p.Barcode,CONVERT(varchar(100),ol.CreateDate,120) as CreateDate FROM RS_OrderList ol LEFT JOIN RS_Order o ON ol.OrderId=o.OrderId LEFT JOIN RS_Product p ON ol.ProId=p.ProId WHERE ol.OrderId not in(SELECT TOP ".$page->firstRow." ol.OrderId FROM RS_OrderList as ol LEFT JOIN RS_Order as o ON ol.OrderId=o.OrderId where ".$whereStr." ) AND ".$whereStr);
            // M()->getlastsql();
            // var_dump($lists);exit();
            $pagedata['lists']=$lists;
        }else{
            $tempData['strtime']=date('Y-m-01 00:00:00',time());
            $tempData['endtime']=date('Y-m-d H:i:s',time());
            $whereStr="o.token='".$this->token."' and ol.Iszp='1' and ol.CreateDate BETWEEN '".$tempData['strtime']."' and '".$tempData['endtime']."'";
            $count=$this->model->query("SELECT COUNT(ol.OrderListId) as count FROM RS_OrderList as ol LEFT JOIN RS_Order AS o ON ol.OrderId=o.OrderId WHERE ".$whereStr)[0]['count'];
            $page=new \Think\Page($count,20,$tempData);
            $lists=$this->model->query("SELECT TOP 20 ol.OrderId,ol.Count,p.ProName,p.Barcode,CONVERT(varchar(100),ol.CreateDate,120) as CreateDate FROM RS_OrderList ol LEFT JOIN RS_Order o ON ol.OrderId=o.OrderId LEFT JOIN RS_Product p ON ol.ProId=p.ProId WHERE ol.OrderId not in(SELECT TOP ".$page->firstRow." ol.OrderId FROM RS_OrderList as ol LEFT JOIN RS_Order as o ON ol.OrderId=o.OrderId where ".$whereStr." ) AND ".$whereStr);
            // var_dump($count);
            // echo M()->getlastsql();
            // var_dump($lists);exit();
            $pagedata['lists']=$lists;
            $pagedata['page']=$page->show();
            $pagedata['count']=$count;
        }
        $pagedata['data']=$tempData;
        $this->assign($pagedata);
        $this->display();
    }

    /**
     * 赠品统计数据导出
     */
    public function zpdataOut(){
        $tempData=$_GET;
        $whereStr="o.token='".$this->token."' and ol.Iszp='1'";
        $Pram="";
        if ($tempData['strtime']) {
            $whereStr.=" AND ol.CreateDate BETWEEN '".$tempData['strtime']."' and '".$tempData['endtime']."'";
            $Pram.="查询时间：".$tempData['strtime']."--".$tempData['endtime'];
        }
        $lists=$this->model->query("SELECT ol.OrderId,ol.Count,p.ProName,p.Barcode,CONVERT(varchar(100),ol.CreateDate,120) as CreateDate FROM RS_OrderList ol LEFT JOIN RS_Order o ON ol.OrderId=o.OrderId LEFT JOIN RS_Product p ON ol.ProId=p.ProId WHERE ".$whereStr);
        $xlsName=date('Y-m-d H:i:s',time())."__zpdatalists";
        $xlsCell  = array(
            array('Barcode','商品条码'),
            array('ProName','商品名称'),
            array('Count','数量'),
            array('OrderId','订单号'),
            array('CreateDate','赠送时间'),
            );
        $file_title='赠品统计数据 '.$Pram;
        exportExcel($xlsName,$xlsCell,$xlsData=$lists,$file_title);
    }

    /**
     * 订单优惠信息统计 
     */
    public function discountorder(){
        if (IS_POST) {
            $tempData=$_POST;
        }else{
            $tempData=$_GET;
        }
        if ($tempData) {
            $whereStr="Coupon>0 and token='".$this->token."'";
            if ($tempData['strtime']) {
                $whereStr.=" and PayDate BETWEEN '".$tempData['strtime']."' and '".$tempData['endtime']."'";
            }
            $count=$this->model->table('RS_Order')->where($whereStr)->count();
            $page= new \Think\Page($count,20,$tempData);
            $lists=$this->model->table('RS_Order')->where($whereStr)->limit($page->firstRow.','.$page->listRows)->field("OrderId,Price,Count,Coupon,CouponListId,getcoupon,CONVERT(varchar(100),PayDate,120) as PayDate,MemberId")->limit($page->firstRow.','.$page->listRows)->select();
            foreach ($lists as &$list) {
                if ($list['getcoupon']) {
                    $list['getcouponinfo']=$this->model->table('RS_Coupon')->where("CouponId='%s' and token='%s'",array($list['getcoupon'],$this->token))->find();
                }
                if ($list['CouponListId']) {
                    $list['CouponListinfo']=$this->model->table('RS_Coupon')->where("CouponId='%s' and token='%s'",array($list['CouponListId'],$this->token))->find();
                }
            }
        }else{
            $tempData['strtime']=date('Y-m-01 00:00:00',time());
            $tempData['endtime']=date('Y-m-d H:i:s',time());
            $count=$this->model->table("RS_Order")->where("Coupon>%d and token='%s' and PayDate BETWEEN '%s' and '%s'",array(0,$this->token,$tempData['strtime'],$tempData['endtime']))->count();
            $page= new \Think\Page($count,20,$tempData);
            $lists=$this->model->table('RS_Order')->where("Coupon>%d and token='%s' and PayDate BETWEEN '%s' and '%s'",array(0,$this->token,$tempData['strtime'],$tempData['endtime']))->field("OrderId,Price,Count,Coupon,CouponListId,getcoupon,CONVERT(varchar(100),PayDate,120) as PayDate,MemberId")->limit($page->firstRow.','.$page->listRows)->select();
            foreach ($lists as &$list) {
                if ($list['getcoupon']) {
                    $list['getcouponinfo']=$this->model->table('RS_Coupon')->where("CouponId='%s' and token='%s'",array($list['getcoupon'],$this->token))->find();
                }
                if ($list['CouponListId']) {
                    $list['CouponListinfo']=$this->model->table('RS_Coupon')->where("CouponId='%s' and token='%s'",array($list['CouponListId'],$this->token))->find();
                }
            }
        }
        $pagedata['count']=$count;
        $pagedata['lists']=$lists;
        $pagedata['data']=$tempData;
        $pagedata['page']=$page->show();
        $this->assign($pagedata);
        $this->display();
    }
    /**
     * 优惠订单信息导出
     */
    public function discountorderOut(){
        $tempData=$_GET;
        $whereStr="Coupon>0 and token='".$this->token."'";
        $Pram="";
        if ($tempData['stime']) {
            $whereStr.=" and PayDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
            $Pram.="查询时间：".$tempData['stime'].'--'.$tempData['etime'];
        }
        $count=$this->model->table('RS_Order')->where($whereStr)->count();
        $lists=$this->model->table('RS_Order')->where($whereStr)->limit($page->firstRow.','.$page->listRows)->field("OrderId,Price,Count,Coupon,CouponListId,getcoupon,CONVERT(varchar(100),PayDate,120) as PayDate,MemberId")->select();
        foreach ($lists as &$list) {
            $info="优惠金额：".$list['Coupon'];
            if ($list['getcoupon']) {
                $tempC=$this->model->table('RS_Coupon')->where("CouponId='%s' and token='%s'",array($list['getcoupon'],$this->token))->find();
                $info.="---赠送优惠券：".$tempC['Rules']."元".$tempC['CouponName'];
            }
            if ($list['CouponListId']) {
                $tempC=$this->model->table('RS_Coupon')->where("CouponId='%s' and token='%s'",array($list['CouponListId'],$this->token))->find();
                if ($tempC['Type']=='0') {
                    $info.="---使用优惠券：".$tempC['Rules']."元".$tempC['CouponName'];
                }elseif ($tempC['Type']=='1') {
                    $r=$tempC['Rules']*10;
                    $info.="---使用优惠券：".$r."折".$tempC['CouponName'];
                }else{
                    $rule=explode('/', $tempC['Rules']);
                    $info.="---使用优惠券：满".$rule[0]."减".$rule[1].$tempC['CouponName'];
                }
            }
            $list['info']=$info;
        }
        $xlsName=date('ymdhis',time()).'_discountorder';
        $xlsCell  = array(
            array('OrderId','订单号'),
            array('MemberId','用户名'),
            array('Price','订单金额'),
            array('Count','数量'),
            array('info','优惠详情'),
            array('PayDate','付款时间'),
            );
        $count=$this->model->table('RS_Order')->where($whereStr)->count();
        $allmoney=$this->model->table('RS_Order')->where($whereStr)->sum('Price');
        $file_title='总订单数：'.$count."   ".$Pram;
        exportExcel($xlsName,$xlsCell,$xlsData=$lists,$file_title);
    }

    /**
     * 积分订单统计
     */
    public function scoreorder(){
        if (IS_POST) {
            $tempData=$_POST;
        }else{
            $tempData=$_GET;
        }
        if ($tempData) {
            
        }else{
            $tempData['strtime']=date('Y-m-01 00:00:00',time());
            $tempData['endtime']=date('Y-m-d H:i:s',time());
            $whereStr="token='".$this->token."' and PayDate BETWEEN '".$tempData['strtime']."' and '".$tempData['endtime']."'";
            $count=M()->table('RS_ScoreOrder')->where($whereStr)->count();
            $page = new \Think\Page($count,15,$tempData);
            $lists=M()->table('RS_ScoreOrder')->where($whereStr)->field("OrderId,MemberId,Price,Count,CONVERT(varchar(100),PayDate,120) as PayDate")->limit($page->firstRow.','.$page->listRows)->select();
        }
        $this->assign($pagedata);
        $this->display();
    }

    /**
     * POS收银员数据统计
     */
    public function posemp(){
        if (IS_POST) {
            $tempData=$_POST;
        }else{
            $tempData=$_GET;
        }
        if ($tempData) {
            $whereStr="token='".$this->token."' AND stoken='".$this->stoken."' AND Status<>'11'";
            if ($tempData['stime']) {
                $whereStr.=" and PayDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."'";
            }
            $count=$this->MSL('user')->where("token='%s'",$this->token)->count();
            $page = new \Think\Page($count,20);
            $lists=$this->MSL('user')->where("token='%s'",$this->token)->limit($page->firstRow.','.$page->listRows)->select();
            $count="[";
            $money="[";
            $types="[";
            $tempAll=0;
            foreach ($lists as &$list) {
                $oinfos=M()->table('RS_Order')->where($whereStr." and Posname='".$list['userName']."'")->limit('0,19')->field('OrderId,MemberId,Price,Count,CONVERT(varchar(100),PayDate,120) as PayDate')->select();
                $list['oinfos']=$oinfos;
                $list['count']=$co=M()->table('RS_Order')->where($whereStr." and Posname='".$list['userName']."'")->count();
                $list['money']=$su=M()->table('RS_Order')->where($whereStr." and Posname='".$list['userName']."'")->sum('Price');
                $su=$su?$su:0;
                $count.="'".$co."',";
                $money.="'".$su."',";
                $types.="'".$list['userName']."',";
                $tempAll+=$su;
            }
            $count=substr($count, 0,strlen($count)-1);
            $money=substr($money, 0,strlen($money)-1);
            $types=substr($types, 0,strlen($types)-1);
            $count.="]";
            $money.="]";
            $types.="]";
        }else{
            $tempData['stime']=date('Y-m-01 00:00:00',time());
            $tempData['etime']=date('Y-m-d H:i:s',time());
            $count=$this->MSL('user')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->count();
            $page = new \Think\Page($count,20);
            $lists=$this->MSL('user')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->limit($page->firstRow.','.$page->listRows)->select();
            $count="[";
            $money="[";
            $types="[";
            $tempAll=0;
            foreach ($lists as &$list) {
                $oinfos=M()->table('RS_Order')->where("Posname='%s' and token='%s' and stoken='%s' and PayDate BETWEEN '%s' and '%s' and Status<>'%s'",array($list['userName'],$this->token,$this->stoken,$tempData['stime'],$tempData['etime'],'11'))->limit('0,19')->field('OrderId,MemberId,Price,Count,CONVERT(varchar(100),PayDate,120) as PayDate')->select();
                $list['oinfos']=$oinfos;
                $list['count']=$co=M()->table('RS_Order')->where("Posname='%s' and token='%s' and stoken='%s' and PayDate BETWEEN '%s' and '%s' and Status<>'%s'",array($list['userName'],$this->token,$this->stoken,$tempData['stime'],$tempData['etime'],'11'))->count();
                $list['money']=$su=M()->table('RS_Order')->where("Posname='%s' and token='%s' and stoken='%s' and PayDate BETWEEN '%s' and '%s' and Status<>'%s'",array($list['userName'],$this->token,$this->stoken,$tempData['stime'],$tempData['etime'],'11'))->sum('Price');
                $su=$su?$su:0;
                $count.="'".$co."',";
                $money.="'".$su."',";
                $types.="'".$list['userName']."',";
                $tempAll+=$su;
            }
            $count=substr($count, 0,strlen($count)-1);
            $money=substr($money, 0,strlen($money)-1);
            $types=substr($types, 0,strlen($types)-1);
            $count.="]";
            $money.="]";
            $types.="]";
        }
        $pagedata['counts']=$count;
        $pagedata['moneys']=$money;
        $pagedata['types']=$types;
        $pagedata['allmoneys']=$tempAll;
        $pagedata['lists']=$lists;
        $pagedata['page']=$page->show();
        $pagedata['data']=$tempData;
        $this->assign($pagedata);
        $this->display();
    }
    /**
     * POS收银员统计数据导出
     */
    public function posempOut(){
        $tempData=$_GET;
        $whereStr="token='".$this->token."' and stoken='".$this->stoken."' and Posname<>''";
        $Pram=' ';
        if ($tempData['stime']) {
            $whereStr.=" and PayDate BETWEEN '".$tempData['stime']."' and '".$tempData['etime']."' and Status<>'11'";
            $Pram.=" 查询时间：".$tempData['stime']."--".$tempData['etime'];
        }
        $xlsData=M()->table('RS_Order')->where($whereStr)->field("OrderId,Price,Count,Posname,MemberId,CONVERT(varchar(100),PayDate,120) as PayDate")->order('Posname')->select();
        $xlsName=date('ymdhis',time())."__posdata";
        $xlsCell  = array(
            array('OrderId','订单号'),
            array('Price','订单金额'),
            array('Count','数量'),
            array('MemberId','会员账号'),
            array('Posname','收银员'),
            array('PayDate','收银时间'),
            );
        $file_title='POS收银员数据统计 '.$Pram;
        exportExcel($xlsName,$xlsCell,$xlsData,$file_title);
    }

    /**
     * POS收银员详情ajax加载
     */
    public function getMorepos(){
        $tempData=$_POST;
        $whereStr="token='".$this->token."' AND stoken='".$this->stoken."' AND Status<>'11'";
        if ($tempData['stime']) {
            $whereStr.=" and Posname='".$tempData['key']."' AND PayDate BETWEEN '".$tempData['stime']."' AND '".$tempData['etime']."'";
        }
        $page=intval($tempData['page']);
        $count=$this->model->query("SELECT COUNT(OrderId) as count FROM RS_Order WHERE OrderId NOT IN (SELECT TOP ".$page." OrderId FROM RS_Order WHERE ".$whereStr.") AND ".$whereStr)[0]['count'];
        // file_put_contents('1.txt', M()->getlastsql());
        // var_dump($count);exit();
        if ($count>0) {
            $data=$this->model->table('RS_Order')->where($whereStr)->limit($page.',20')->field("OrderId,Posname,Price,Count,MemberId,CONVERT(varchar(100), PayDate, 120) as PayDate")->select();
            // file_put_contents('1xtx', M()->getlastsql());
            $json['statu']='success';
            $json['data']=$data;
        }else{
            $json['statu']='error';
            $json['info']='nomore';
        }
        echo json_encode($json);
    }


  }

?>
