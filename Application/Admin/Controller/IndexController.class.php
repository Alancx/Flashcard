<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
        $pagename='indexv'.session('GroupId');
        // var_dump($pagename);
        $this->display($pagename);
    }
    public function index2(){
        $model=M();
        $today=date("Y-m-d 23:59:59",time('-1 day'));

        $OrderCount_month=$model->table('RS_Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-30 day'))."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->count();
        $OrderPrices_month=$model->table('RS_Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-30 day'))."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
        $OrderCount_week=$model->table('RS_Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-7 day'))."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->count();
        $OrderPrices_week=$model->table('RS_Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-7 day'))."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
        $OrderCount_day=$model->table('RS_Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',time())."' and '".date('Y-m-d 23:59:59',time())."' and token='".$this->token."' and stoken='".$this->stoken."'")->count();
        $OrderPrices_day=$model->table('RS_Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',time())."' and '".date('Y-m-d 23:59:59',time())."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
        $data=$model->query("SELECT SUM(Price-Freight) AS Money,convert(varchar(7),CreateDate,120) AS mon FROM dbo.RS_Order WHERE Status IN (2,3,4,10,5)
AND (CreateDate BETWEEN DATEADD(MONTH,DATEDIFF(MONTH,0,DATEADD(month,-6, DATEADD(DAY,-1,CONVERT(varchar(100), GETDATE(), 23)) )),0) AND DATEADD(SECOND,-1,CONVERT(varchar(100), GETDATE(), 23))) and token='".$this->token."' and stoken='".$this->stoken."' group BY convert(varchar(7),CreateDate,120)");
        $time="";
        $money="";
        foreach ($data as $kt) {
            $time.="'".$kt['mon']."',";
            $money.=$kt['Money'].",";
        }
        $time.="]";
        $money.="]";
        $Dnums=count($data);
        $Lnums=6-$Dnums;
        switch ($Lnums) {
            case '6':
                $truetime="['".date('Y-m',strtotime('-5 month'))."','".date('Y-m',strtotime('-4 month'))."','".date('Y-m',strtotime('-3 month'))."','".date('Y-m',strtotime('-2 month'))."','".date('Y-m',strtotime('-1 month'))."','".date('Y-m',time())."']";
                $truemoney="[0,0,0,0,0,0]";
                break;

            case '5':
                $truetime="['".date('Y-m',strtotime('-5 month'))."','".date('Y-m',strtotime('-4 month'))."','".date('Y-m',strtotime('-3 month'))."','".date('Y-m',strtotime('-2 month'))."','".date('Y-m',strtotime('-1 month'))."',".$time;
                $truemoney="[0,0,0,0,0,".$money;
                break;

            case '4':
                $truetime="['".date('Y-m',strtotime('-5 month'))."','".date('Y-m',strtotime('-4 month'))."','".date('Y-m',strtotime('-3 month'))."','".date('Y-m',strtotime('-2 month'))."',".$time;
                $truemoney="[0,0,0,0,".$money;
                break;

            case '3':
                $truetime="['".date('Y-m',strtotime('-5 month'))."','".date('Y-m',strtotime('-4 month'))."','".date('Y-m',strtotime('-3 month'))."',".$time;
                $truemoney="[0,0,0,".$money;
                break;

            case '2':
                $truetime="['".date('Y-m',strtotime('-5 month'))."','".date('Y-m',strtotime('-4 month'))."',".$time;
                $truemoney="[0,0,".$money;
                break;

            case '1':
                $truetime="['".date('Y-m',strtotime('-5 month'))."',".$time;
                $truemoney="[0,".$money;
                break;

            case '0':
                $truetime="[".$time;
                $truemoney="[".$money;
                break;

        }
        $sql="SELECT ISNULL(SUM(Price-Freight),0) AS Money  FROM dbo.RS_Order WHERE Status IN (2,3,4,10,5)
AND (CreateDate BETWEEN DATEADD(MONTH,DATEDIFF(MONTH,0,DATEADD(month,-6, DATEADD(DAY,-1,CONVERT(varchar(100), GETDATE(), 23)) )),0) AND DATEADD(SECOND,-1,CONVERT(varchar(100), GETDATE(), 23))) and token='".$this->token."' and stoken='".$this->stoken."'";
        //查询6个月销售额 包括 已付款、已发货、已收货、退款中、订单完成会员主动删除



        $etime=date('Y-m-d',strtotime('-1 day'))." 23:59";
        $half=$model->query($sql);
        $this->assign(array('OrderCount'=>$OrderCount_month,'OrderPrices'=>$OrderPrices_month,'Money'=>$truemoney,'Mon'=>$truetime,'half'=>$half[0]['Money'],'etime'=>$etime,'OrderCount_week'=>$OrderCount_week,'OrderCount_day'=>$OrderCount_day,'OrderPrices_week'=>$OrderPrices_week,'OrderPrices_day'=>$OrderPrices_day));
        $tpdata=M()->query("SELECT SUM(o.Price) as money,SUM(o.Count) as count,s.province FROM RS_Store s LEFT JOIN RS_Order o ON s.stoken=o.stoken WHERE o.Status in(2,3,4,5,10) GROUP BY s.province");
        $stdata=M()->query("SELECT COUNT(ID) as count,province FROM RS_Store GROUP BY province");
        $moneydata="[";
        $countdata="[";
        $maxStr=array();
        foreach ($tpdata as $tps) {
            if ($tps['province']) {
                $money=$tps['money']?$tps['money']:0;
                $count=$tps['count']?$tps['count']:0;
                $moneydata.="{name:'{$tps['province']}',value:{$money}},";
                $countdata.="{name:'{$tps['province']}',value:{$count}},";
                $maxStr[]=$tps['money'];
            }
        }
        $storedata="[";
        foreach ($stdata as $st) {
            if ($st['province']) {
                $storedata.="{name:'{$st['province']}',value:{$st['count']}},";
            }
        }
        $storedata=substr($storedata, 0,strlen($storedata)-1);
        $storedata.="]";
        $moneydata=substr($moneydata, 0,strlen($moneydata)-1);
        $countdata=substr($countdata, 0,strlen($countdata)-1);
        $moneydata.="]";
        $countdata.="]";
        $pagedata['moneydata']=$moneydata;
        $pagedata['countdata']=$countdata;
        $pagedata['storedata']=$storedata;
        $maxmoney=max($maxStr);
        $pagedata['maxnum']=$maxmoney;
        $this->assign($pagedata);
        $this->display();
    }

    public function get_pv(){
        $stime=$_POST['stime'];
        $etime=$_POST['etime'];
        $this->cookie_abcd9_com=tempnam("", "cookie");  // 设置cookie临时文件
        $this->baidu_post(C('BAIDU_TONGJI_URL'),C('BAIDU_TONGJI_PWD'));
        // $this->baidu_post('http://tongji.baidu.com/web/11258907/ajax/post','siteId=7493934&clientDevice=all&st='.$stime.'&et='.$etime.'&st2=0&et2=0&indicators=pv_count%2Cvisitor_count%2Cip_count%2Cbounce_ratio%2Cavg_visit_time&order=simple_date_title%2Cdesc&offset=0&gran=5&flag=month&reportId=3&method=trend%2Ftime%2Fa&queryId=');
        $this->baidu_post('http://tongji.baidu.com/web/11258907/ajax/post','siteId=7493934&clientDevice=all&st='.$stime.'&et='.$etime.'&st2=0&et2=0&indicators=pv_count&gran=6&flag=today&reportId=3&method=trend%2Ftime%2Ff&queryId=');
        $data=json_decode($this->baidu_content,true); //输出，或进行其他操作
        if ($data['status']===0) {
            if (!$data['data']['sum'][0][0]) {
             echo "0";
            }else{
                echo $data['data']['sum'][0][0];
            }
        }else{
            echo "error";
        }
    }
    public function get_pv_month(){
        $stime=$_POST['stime'];
        $etime=$_POST['etime'];
        $this->cookie_abcd9_com=tempnam("", "cookie");  // 设置cookie临时文件
        $this->baidu_post(C('BAIDU_TONGJI_URL'),C('BAIDU_TONGJI_PWD'));
        $this->baidu_post('http://tongji.baidu.com/web/11258907/ajax/post','siteId=7493934&clientDevice=all&st='.$stime.'&et='.$etime.'&st2=0&et2=0&indicators=pv_count%2Cvisitor_count%2Cip_count%2Cbounce_ratio%2Cavg_visit_time&order=simple_date_title%2Cdesc&offset=0&gran=5&flag=month&reportId=3&method=trend%2Ftime%2Fa&queryId=');
        // $this->baidu_post('http://tongji.baidu.com/web/11258907/ajax/post','siteId=7493934&clientDevice=all&st='.$stime.'&et='.$etime.'&st2=0&et2=0&indicators=pv_count&gran=6&flag=today&reportId=3&method=trend%2Ftime%2Ff&queryId=');
        $data=json_decode($this->baidu_content,true); //输出，或进行其他操作
        if ($data['status']===0) {
            if (!$data['data']['sum'][0][0]) {
             echo "0";
            }else{
                echo $data['data']['sum'][0][0];
            }
        }else{
            echo "error";
        }
    }
    public function get_pv_week(){
        $stime=$_POST['stime'];
        $etime=$_POST['etime'];
        $this->cookie_abcd9_com=tempnam("", "cookie");  // 设置cookie临时文件
        $this->baidu_post(C('BAIDU_TONGJI_URL'),C('BAIDU_TONGJI_PWD'));
        $this->baidu_post('http://tongji.baidu.com/web/11258907/ajax/post','siteId=7493934&clientDevice=all&st='.$stime.'&et='.$etime.'&indicators=pv_count%2Cvisitor_count%2Cip_count%2Cbounce_ratio%2Cavg_visit_time&order=simple_date_title%2Cdesc&offset=0&gran=6&flag=yesterday&pageSize=24&reportId=3&method=trend%2Ftime%2Fa&queryId=');

        // $this->baidu_post('http://tongji.baidu.com/web/11258907/ajax/post','siteId=7493934&clientDevice=all&st='.$stime.'&et='.$etime.'&st2=0&et2=0&indicators=pv_count&gran=6&flag=today&reportId=3&method=trend%2Ftime%2Ff&queryId=');
        $data=json_decode($this->baidu_content,true); //输出，或进行其他操作
        // var_dump($data);
        if ($data['status']===0) {
            if (!$data['data']['sum'][0][0]) {
             echo "0";
            }else{
                echo $data['data']['sum'][0][0];
            }
        }else{
            echo "error";
        }
    }
    /**
     * 地图数据
     */
    public function mapdata(){
        if (IS_POST) {
            
        }else{
            $tpdata=M()->query("SELECT SUM(o.Price) as money,SUM(o.Count) as count,s.province FROM RS_Store s LEFT JOIN RS_Order o ON s.stoken=o.stoken WHERE o.Status in(2,3,4,5,10) GROUP BY s.province");
            $stdata=M()->query("SELECT COUNT(ID) as count,province FROM RS_Store GROUP BY province");
            $moneydata="[";
            $countdata="[";
            $maxStr=array();
            foreach ($tpdata as $tps) {
                if ($tps['province']) {
                    $money=$tps['money']?$tps['money']:0;
                    $count=$tps['count']?$tps['count']:0;
                    $moneydata.="{name:'{$tps['province']}',value:{$money}},";
                    $countdata.="{name:'{$tps['province']}',value:{$count}},";
                    $maxStr[]=$tps['money'];
                }
            }
            $storedata="[";
            foreach ($stdata as $st) {
                if ($st['province']) {
                    $storedata.="{name:'{$st['province']}',value:{$st['count']}},";
                }
            }
            $storedata=substr($storedata, 0,strlen($storedata)-1);
            $storedata.="]";
            $moneydata=substr($moneydata, 0,strlen($moneydata)-1);
            $countdata=substr($countdata, 0,strlen($countdata)-1);
            $moneydata.="]";
            $countdata.="]";
            $pagedata['moneydata']=$moneydata;
            $pagedata['countdata']=$countdata;
            $pagedata['storedata']=$storedata;
            $maxmoney=max($maxStr);
            $pagedata['maxnum']=$maxmoney;
            $this->assign($pagedata);
            $this->display();
        }
    }
}
