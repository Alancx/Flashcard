<?php
namespace Seller\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
        $gname=session('GroupId')=='超级管理组'?'default':session('GroupId');
        $pagename='indexv'.$gname;
        $this->display($pagename);
    }
    public function index2(){
        $model=M();
        $today=date("Y-m-d 23:59:59",time('-1 day'));

        $OrderCount_month=$model->table('RS_Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-30 day'))."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->count();
        $OrderPrices_month=$model->table('RS_Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-30 day'))."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
        // var_dump(M()->getlastsql());exit;
        $OrderCount_week=$model->table('RS_Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-7 day'))."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->count();
        $OrderPrices_week=$model->table('RS_Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',strtotime('-7 day'))."' and '".$today."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
        $OrderCount_day=$model->table('RS_Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',time())."' and '".date('Y-m-d 23:59:59',time())."' and token='".$this->token."' and stoken='".$this->stoken."'")->count();
        $OrderPrices_day=$model->table('RS_Order')->where("Status in(2,3,4,5,10) and PayDate BETWEEN '".date('Y-m-d 00:00:00',time())."' and '".date('Y-m-d 23:59:59',time())."' and token='".$this->token."' and stoken='".$this->stoken."'")->sum('Price-Freight');
        $data=$model->query("SELECT SUM(Price-Freight) AS Money,convert(varchar(7),CreateDate,120) AS mon FROM dbo.RS_Order WHERE Status IN (2,3,4,10,5)
AND (CreateDate BETWEEN DATEADD(MONTH,DATEDIFF(MONTH,0,DATEADD(month,-6, DATEADD(DAY,-1,CONVERT(varchar(100), GETDATE(), 23)) )),0) AND DATEADD(SECOND,-1,CONVERT(varchar(100), GETDATE(), 23))) and token='".$this->token."' and stoken='".$this->stoken."' group BY convert(varchar(7),CreateDate,120)");
        $timeline=array();
        for ($i=1; $i < 7; $i++) {
            $timeline[]=date('Y-m',strtotime('-'.$i.' months'));
        }
        $timeline=array_reverse($timeline);
        $newdata=array();
        foreach ($data as $kt) {
            $newdata[$kt['mon']]=$kt['Money'];
        }
        $moneyline=array();
        foreach ($timeline as $key => $value) {
            if (array_key_exists($value, $newdata)) {
                $moneyline[]=$newdata[$value];
            }else{
                $moneyline[]='0';
            }
        }
        $sql="SELECT ISNULL(SUM(Price-Freight),0) AS Money  FROM dbo.RS_Order WHERE Status IN (2,3,4,10,5)
AND (CreateDate BETWEEN DATEADD(MONTH,DATEDIFF(MONTH,0,DATEADD(month,-6, DATEADD(DAY,-1,CONVERT(varchar(100), GETDATE(), 23)) )),0) AND DATEADD(SECOND,-1,CONVERT(varchar(100), GETDATE(), 23))) and token='".$this->token."' and stoken='".$this->stoken."'";
        //查询6个月销售额 包括 已付款、已发货、已收货、退款中、订单完成会员主动删除

        // var_dump($truetime,$truemoney);exit();

        $etime=date('Y-m-d',strtotime('-1 day'))." 23:59";
        $half=$model->query($sql);
        $this->assign(array('OrderCount'=>$OrderCount_month,'OrderPrices'=>$OrderPrices_month,'Money'=>json_encode($moneyline),'Mon'=>json_encode($timeline),'half'=>$half[0]['Money'],'etime'=>$etime,'OrderCount_week'=>$OrderCount_week,'OrderCount_day'=>$OrderCount_day,'OrderPrices_week'=>$OrderPrices_week,'OrderPrices_day'=>$OrderPrices_day));
        $pagedata['stoken']=$this->stoken;
        $pagedata['token']=$this->token;
        $pagedata['storeid']=M()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->getField('id');
        $url="https://".$_SERVER['HTTP_HOST'].U('Seller/OfflineCashier/index')."?token=".$this->token."&stoken=".$this->stoken."&sid=".$pagedata['storeid'];
        $pagedata['url']=$url;
        $this->assign($pagedata);
        $this->display('indexs');
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

    public function showqr(){
        ob_clean();
        vendor('PHPQR.phpqrcode');
        $url='http://'.$_SERVER['HTTP_HOST'].'/index.php'.U('Home/Index/Index',array('stoken'=>$this->stoken,'once'=>'1','inred'=>'true'));
        $level="L";
        $size=4;
        \QRcode::png($url,$filename,$level,$size,'2');
    }

    public function getqr(){
        ob_clean();
        vendor('PHPQR.phpqrcode');
        $sid=M()->table('RS_Store')->where("stoken='%s' and token='%s'",array($this->stoken,$this->token))->getField("id");
        $url='https://'.$_SERVER['HTTP_HOST'].U('Seller/OfflineCashier/index')."?token=".$this->token."&stoken=".$this->stoken."&sid=".$sid;
        $level="L";
        $size=4;
        \QRcode::png($url,$filename,$level,$size,'2');
    }




}
