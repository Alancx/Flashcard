<?php
namespace Sellermobile\Controller;
use Think\Controller;
class StaffController extends CommonController {

  public function staffbak(){
    $table_data=$this->BM()->query("SELECT (CASE Status WHEN '0' THEN '待处理' WHEN '1' THEN '已处理' END) as Status,ISNULL(CONVERT(varchar(10),ExecDate,120),'-') as ExecDate,CONVERT(float(50),Money,50) as Money,(CASE CutType WHEN '1' THEN '销售结算' WHEN '2' THEN '引流结算' END) as Type,ExecId  FROM RS_MerCutDetail WHERE stoken='{$this->stoken}'");
    foreach ($table_data as &$tb) {
      if (array_key_exists($tb['ExecId'], $users)) {
        $tb['cutname']=$users[$tb['ExecId']];
      }else{
        $tb['cutname']='-';
      }
    }
    $pagedata['table_data']=$table_data;
    $users=$this->UM()->table('tb_user')->where("token='%s' and stoken='0'",$this->token)->getField('id,TrueName');

    $allcuted=$this->BM()->query("SELECT CONVERT(float(50),SUM(Money),50) as allmoney,CutType FROM RS_MerCutDetail WHERE stoken='{$this->stoken}' GROUP BY CutType");
    $pagedata['ylcuted']=0;
    $pagedata['xscuted']=0;
    foreach ($allcuted as $cut) {
      if ($cut['CutType']=='2') {
        $pagedata['ylcuted']=$cut['allmoney'];
      }else{
        $pagedata['xscuted']=$cut['allmoney'];
      }
    }
    $cut=$this->UM()->table('tb_merchant')->where("token='%s'",$this->token)->getField("Cut");
    $ylcuting=$this->BM()->table('RS_Order')->where("Status in(4,10) and PayName='T' and IsCuted='0' and SceneContent='{$this->stoken}'")->SUM('CutMoney');
    // var_dump($this->BM()->getlastsql());exit();
    // var_dump($ylcuting);exit();
    $pagedata['ylcuting']=$ylcuting;
    $xscuting=$this->BM()->table('RS_Order')->where("Status in (4,10) and PayName='T' and Pstoken='1' and stoken='{$this->stoken}'")->SUM('Price-Freight');
    $pagedata['xscuting']=$xscuting?$xscuting:0;
    $this->assign('Title','结算统计');
    $this->assign($pagedata);
    $this->display();
  }

  /**
  * 账户信息归总
  */
  public function staff(){
    $MoneyInfo=$this->BM()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->field("CONVERT(float(53),ISNULL(Money,0),120) as Money,CONVERT(float(53),ISNULL(TotalMoney,0),120) as TotalMoney")->find();
    $cutlist=$this->BM()->table('RS_StoreMoneyManager')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->field("ID,CONVERT(varchar(20),CreateDate,120) as CreateDate,(CASE Type WHEN 'add' THEN '+' WHEN 'less' THEN '-' END) as Type,CONVERT(float(53),Money,120) as Money,CONVERT(float(53),TmpMoney,120) as TmpMoney,(CASE Useage WHEN 'XS' THEN '销售结算' WHEN 'YL' THEN '引流结算' WHEN 'JS' THEN '账户提现' WHEN 'PS' THEN '配送提现' END) as Useage")->order("CreateDate desc")->select();
    $sqlist=$this->BM()->table('RS_MerCutDetail')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->field("CONVERT(varchar(20),CreateDate,120) as CreateDate,CONVERT(float(53),Money,120) as Money,(CASE Status WHEN '0' THEN '待处理' WHEN '1' THEN '已处理' END) AS Status")->order("CreateDate desc")->select();
    $pagedata['MoneyInfo']=$MoneyInfo;
    $pagedata['cutlist']=$cutlist;
    $pagedata['sqlist']=$sqlist;
    $pagedata['Title']='账户提现';
    $this->assign($pagedata);
    $this->display('newstaff');
  }

  /**
  * 简单筛选
  */
  public function getdata(){
    if (IS_POST) {
      $type=$_POST['type'];
      switch ($type) {
        case 'cutall':
        $dataType='cut';
        $status='all';
        break;
        case 'getmoney':
        $dataType='cut';
        $status='add';
        break;
        case 'outmoney':
        $dataType='cut';
        $status='less';
        break;
        case 'sqall':
        $dataType='sq';
        $status='all';
        break;
        case 'sqing':
        $dataType='sq';
        $status='0';
        break;
        case 'sqend':
        $dataType='sq';
        $status='1';
        break;
      }
      if ($dataType=='cut') {
        if ($status=='all') {
          $whereStr="token='{$this->token}' and stoken='{$this->stoken}'";
        }else{
          $whereStr="token='{$this->token}' and stoken='{$this->stoken}' and Type='{$status}'";
        }
        $data=$this->BM()->table('RS_StoreMoneyManager')->where($whereStr)->field("ID,CONVERT(varchar(20),CreateDate,120) as CreateDate,(CASE Type WHEN 'add' THEN '+' WHEN 'less' THEN '-' END) as Type,CONVERT(float(53),Money,120) as Money,CONVERT(float(53),TmpMoney,120) as TmpMoney,(CASE Useage WHEN 'XS' THEN '销售结算' WHEN 'YL' THEN '引流结算' WHEN 'JS' THEN '账户提现' WHEN 'PS' THEN '配送提现' END) as Useage")->order("CreateDate desc")->select();
        if ($data && count($data)>0) {
          foreach ($data as &$da) {
            $da['CreateDate']=date('Y.m.d H:i',strtotime($da['CreateDate']));
          }
          $msg['status']='success';
          $msg['data']=$data;
        }else{
          $msg['status']='error';
        }
      }elseif ($dataType=='sq') {
        if ($status=='all') {
          $whereStr="token='{$this->token}' and stoken='{$this->stoken}'";
        }else{
          $whereStr="token='{$this->token}' and stoken='{$this->stoken}' and Status='{$status}'";
        }
        $data=$this->BM()->table('RS_MerCutDetail')->where($whereStr)->field("CONVERT(varchar(20),CreateDate,120) as CreateDate,CONVERT(float(53),Money,120) as Money,(CASE Status WHEN '0' THEN '待处理' WHEN '1' THEN '已处理' END) AS Status")->order("CreateDate desc")->select();
        if ($data && count($data)>0) {
          foreach ($data as &$da) {
            $da['CreateDate']=date('Y.m.d H:i',strtotime($da['CreateDate']));
          }
          $msg['status']='success';
          $msg['data']=$data;
        }else{
          $msg['status']='error';
        }
      }
      echo json_encode($msg);
    }
  }

  /**
  * 提现申请
  */
  public function getmoney(){
    $money=$_POST['money'];
    $IdInfo=$this->BM()->table("RS_MerchantBank")->where("token='%s' and stoken='%s' and IsCheck='1'",array($this->token,$this->stoken))->find();
    if ($IdInfo) {
      $DB=array();
      $DB['Money']=$money;
      $DB['IdCard']=$IdInfo['IdCard'];
      $DB['IdName']=$IdInfo['BankName'];
      $DB['IdType']=$IdInfo['IsOpen'];
      $DB['GetName']=$IdInfo['IdName'];
      $DB['tel']=$IdInfo['tel'];
      $DB['CutType']='GETMONEY';
      $DB['token']=$this->token;
      $DB['stoken']=$this->stoken;

      //
      $this->BM()->startTrans();
      $res=$this->BM()->table('RS_MerCutDetail')->add($DB);
      $sres=$this->BM()->table('RS_Store')->where("token='%s' and stoken='%s'",array($this->token,$this->stoken))->setDec('Money',$money);
      if ($res && $sres) {
        $this->BM()->commit();

        $filename = './Public/message.json';
        $res = json_decode(file_get_contents($filename),true);
        $messageData['mobiles'] = $res['withdraw'];
        $messageData['content'] = "您有新的商户提现申请,请及时处理";
        $this->SendMessage($messageData);



        $msg['status']='success';
        $msg['data']=array("CreateDate"=>date('Y.m.d H:i',time()),'Money'=>$money,'Status'=>"待处理");
      }else{
        $this->BM()->rollback();
        $msg['status']='error';
        $msg['info']='提交失败';
        // $this->LOGS("商户提现申请提交失败--->>>res=$res...sres=$sres".M()->getlastsql());
      }
    }else{
      $msg['status']='error';
      $msg['info']='缺少银行账户信息';
    }
    echo json_encode($msg);
  }

  public function ordercut(){
    $sinfo=$this->BM()->table('RS_Store')->where("stoken='%s'",$this->stoken)->field("CONVERT(varchar(20),FreeStime,120) as FreeStime,CONVERT(varchar(20),FreeEtime,120) as FreeEtime,CutNum,IsFreeCut,MercutDate")->find();
    if (IS_POST) {
      $whereStr="o.Status in (4,10) and o.Pstoken=1 and o.PayName in ('T','ALIPAY')";
      $stime='1970-01-01 00:00:01';
      $whereStr.=" and o.stoken='{$this->stoken}'";
      if ($_POST['endtime']) {
        $whereStr.=" and o.CreateDate BETWEEN '{$stime}' and '{$_POST['endtime']}'";
      }
      $spmoney=0;
      if ($sinfo['IsFreeCut']==1) {
        $money1=$this->BM()->query("SELECT SUM(o.Price) as cutmoney FROM RS_Order o WHERE {$whereStr} and o.DisMoney<>0 and o.CreateDate NOT BETWEEN '{$sinfo['FreeStime']}' and '{$sinfo['FreeEtime']}'")[0]['cutmoney'];
        if ($sinfo['FreeStime']<$_POST['endtime'] && $sinfo['FreeEtime']<$_POST['endtime']) {
          $spmoney=$this->BM()->query("SELECT SUM(o.Price) as spmoney FROM RS_Order o WHERE o.Status in (4,10) and o.CreateDate BETWEEN '{$sinfo['FreeStime']}' and '{$sinfo['FreeEtime']}' and o.Pstoken=1 and o.PayName in ('T','ALIPAY') and o.DisMoney<>0 and o.stoken='{$this->stoken}'")[0]['spmoney'];
        }elseif ($sinfo['FreeStime']<$_POST['endtime'] && $sinfo['FreeEtime']>=$_POST['endtime']) {
          $spmoney=$this->BM()->query("SELECT SUM(o.Price) as spmoney FROM RS_Order o WHERE o.Status in (4,10) and o.CreateDate BETWEEN '{$sinfo['FreeStime']}' and '{$_POST['endtime']}' and o.Pstoken=1 and o.PayName in ('T','ALIPAY') and o.DisMoney<>0 and o.stoken='{$this->stoken}'")[0]['spmoney'];
        }
      }else{
        $money1=$this->BM()->query("SELECT SUM(o.Price) as cutmoney FROM RS_Order o WHERE {$whereStr} and o.DisMoney<>0")[0]['cutmoney'];
      }
      $money2=$this->BM()->query("SELECT SUM(o.Price) as money FROM RS_Order o WHERE {$whereStr} and o.DisMoney=0")[0]['money'];
      $Allmoney=0;
      $cut=0;
      if ($sinfo['CutNum'] && $sinfo['CutNum']>0) {
        $tmpmoney=floatval($money1)*(100-intval($sinfo['CutNum']))/100;
        $cut=floatval($money1)-floatval($tmpmoney);
        $Allmoney=floatval($money2)+floatval($tmpmoney);
      }else{
        $tmpmoney=floatval($money1);
        $Allmoney=floatval($money2)+floatval($tmpmoney);
      }
      $Allmoney=$Allmoney+floatval($spmoney);
      $Allmoney=round($Allmoney,2);
      $cut=round($cut,2);
      $info['Allmoney']=$Allmoney;
      $info['Lastcut']=$sinfo['MercutDate'];
      $info['cut']=$cut;
      $info['CutNum']=$sinfo['CutNum'];
      $returndata['info']=$info;
      $data=array('strtime'=>$stime,'endtime'=>$_POST['endtime']);
      $returndata['data']=$data;
      $bkinfo=$this->BM()->table('RS_MerchantBank')->where("stoken='%s' and IsCheck='1'",$this->stoken)->find();
      $returndata['bkinfo']=$bkinfo;
      $returndata['sinfo']=$sinfo;
      $this->ajaxReturn(array('status' => 'true', 'datainfo' => $returndata), 'JSON');
      // var_dump($returndata);exit();



    } else {
      $pagedata['sinfo']=$sinfo;
      $pagedata['Title']='订单结算';
      $this->assign($pagedata);
      $this->display();
    }
  }


  public function cuted(){
    // var_dump($_POST);exit();
    if (IS_POST) {
      $sinfo=$this->BM()->table('RS_Store')->where("stoken='%s'",$this->stoken)->field("CONVERT(varchar(20),FreeStime,120) as FreeStime,CONVERT(varchar(20),FreeEtime,120) as FreeEtime,CutNum,IsFreeCut,MercutDate,Invcode")->find();
      $stime='1970-01-01 00:00:01';
      $etime=$_POST['etime'];
      $whereStr="o.stoken='{$this->stoken}' and o.Status in (4,10) and o.Pstoken=1 and o.CreateDate BETWEEN '{$stime}' and '{$etime}'";
      $spwhere="stoken='{$this->stoken}' and Status in (4,10) and Pstoken=1 and CreateDate BETWEEN '{$stime}' and '{$etime}'";
      $spmoney=0;
      if ($sinfo['IsFreeCut']==1) {
        $cutmoney=$this->BM()->table('RS_Order o')->where($whereStr." and o.DisMoney<>0 and o.CreateDate NOT BETWEEN '{$sinfo['FreeStime']}' and '{$sinfo['FreeEtime']}'")->sum('o.Price');
        if ($sinfo['FreeStime']<$etime && $sinfo['FreeEtime']<$etime) {
          $spmoney=$this->BM()->query("SELECT SUM(o.Price) as spmoney FROM RS_Order o WHERE o.Status in (4,10) and o.CreateDate BETWEEN '{$sinfo['FreeStime']}' and '{$sinfo['FreeEtime']}' and o.Pstoken=1 and o.PayName in ('T','ALIPAY') and o.DisMoney<>0 and o.stoken='{$this->stoken}'")[0]['spmoney'];
        }elseif ($sinfo['FreeStime']<$etime && $sinfo['FreeEtime']>=$etime) {
          $spmoney=$this->BM()->query("SELECT SUM(o.Price) as spmoney FROM RS_Order o WHERE o.Status in (4,10) and o.CreateDate BETWEEN '{$sinfo['FreeStime']}' and '{$etime}' and o.Pstoken=1 and o.PayName in ('T','ALIPAY') and o.DisMoney<>0 and o.stoken='{$this->stoken}'")[0]['spmoney'];
        }
      }else{
        $cutmoney=$this->BM()->table('RS_Order o')->where($whereStr.' and o.DisMoney<>0')->sum('o.Price');
      }
      $money=$this->BM()->table('RS_Order o')->where($whereStr.' and o.DisMoney=0')->sum('o.Price');
      $Allmoney=0;
      $cut=0;
      if ($sinfo['CutNum'] && $sinfo['CutNum']>0) {
        $tmpmoney=floatval($cutmoney*(100-intval($sinfo['CutNum']))/100);
        $Allmoney=floatval($money)+$tmpmoney;
        $cut=floatval($cutmoney*intval($sinfo['CutNum'])/100);
      }else{
        $Allmoney=floatval($money)+floatval($cutmoney);
      }
      $Allmoney=$Allmoney+floatval($spmoney);
      $tuier=false;
      if ($sinfo['Invcode']) {
        $tuier=$this->BM()->table('RS_Tuier')->where("Invcode='%s'",$sinfo['Invcode'])->find();
      }
      $cut=round($cut,2);
      $Allmoney=round($Allmoney,2);
      $tcut=floatval($cut/2);
      $alloids=$this->BM()->table('RS_Order o')->where($whereStr)->getField("OrderId",true);
      $os=$ms=$mms=$ts=$tms=true;
      $this->BM()->startTrans();
      $os=$this->BM()->table('RS_Order')->where($spwhere)->setField("Pstoken",'2');
      $now=strtotime($etime);
      $ms=$this->BM()->execute("UPDATE RS_Store SET Money=Money+{$Allmoney},TotalMoney=TotalMoney+{$Allmoney},MercutDate={$now} WHERE stoken='{$this->stoken}'");
      $mmdb=array();
      $mmdb['Money']=$Allmoney;
      $mmdb['Type']='add';
      $mmdb['Useage']='XS';
      $mmdb['token']=$this->token;
      $mmdb['stoken']=$this->stoken;
      $mmdb['Ext']=serialize($alloids);
      $mms=$this->BM()->table('RS_StoreMoneyManager')->add($mmdb);
      // if ($tuier && count($tuier)>0 && $tcut>0) {
      //   $ts=$this->BM()->execute("UPDATE RS_Tuier SET TotalMoney=TotalMoney+{$tcut},Money=Money+{$tcut} WHERE ID={$tuier['ID']}");
      //   $tmsdb=array();
      //   $tmsdb['TuierId']=$tuier['ID'];
      //   $tmsdb['TuierAccount']=$tuier['userName'];
      //   $tmsdb['Type']='add';
      //   $tmsdb['Money']=$tcut;
      //   $tmsdb['stoken']=$this->stoken;
      //   $tms=$this->BM()->table('RS_TuiMoneyManager')->add($tmsdb);
      // }


      if ($tuier && count($tuier)>0 && $cut>0) {
        $tuiercut=$this->BM()->table('RS_LevelInfo')->where("LevelType='TUIER' and LevelLabel='{$tuier['Level']}'")->getField("LevelCut");
        if ($tuiercut && $tuiercut>0) {
          $tcut=floatval($cut*floatval($tuiercut)/100);
          //有推广人
          $ts=$this->BM()->execute("UPDATE RS_Tuier SET TotalMoney=TotalMoney+{$tcut},Money=Money+{$tcut} WHERE ID={$tuier['ID']}");
          $tmsdb=array();
          $tmsdb['TuierId']=$tuier['ID'];
          $tmsdb['TuierAccount']=$tuier['userName'];
          $tmsdb['Type']='add';
          $tmsdb['Money']=$tcut;
          $tmsdb['stoken']=$this->stoken;
          $tms=$this->BM()->table('RS_TuiMoneyManager')->add($tmsdb);
        }
      }




      //更新金额
      //更新金额变动记录
      //更新推广人金额
      //更新推荐人金额变动记录
      if ($os && $ms && $mms && $ts && $tms) {
        $this->BM()->commit();
        $msg['status']='success';
      }else{
        $this->BM()->rollback();
        $msg['status']='error';
        $msg['info']='处理失败';
        $this->LOGS("os=$os && ms=$ms && mms=$mms && ts=$ts && tms=$tms");
      }
    }else{
      $msg['status']='error';
      $msg['info']='非法操作';
    }
    $this->ajaxReturn($msg);
  }


  public function getdetail(){
    $whereStr="Status in (4,10) and Pstoken=1 and PayName IN ('T','ALIPAY') and stoken='{$this->stoken}'";
    $stime='1970-01-01 08:00:00';
    if ($_POST['etime']) {
      $whereStr.=" and CreateDate BETWEEN '{$stime}' and '{$_POST['etime']}'";
    }
    $infolist=$this->BM()->table('RS_Order')->where($whereStr)->field("(CASE PayName WHEN 'T' THEN '微信支付' WHEN 'ALIPAY' THEN '支付宝' WHEN 'XJ' THEN '现金支付' END) as PayName,OrderId,CONVERT(float(50),Price,120) as Price,CONVERT(varchar(20),CreateDate,120) as CreateDate,(CASE DisMoney WHEN 0 THEN '0' ELSE '1' END) as IsDis")->select();
    if ($infolist) {
      $msg['status']='success';
      $msg['info']=$infolist;
    } else {
      $msg['status']='error';
      $msg['info']='处理失败';
    }
    $this->ajaxReturn($msg);
  }







}?>
