<?php
namespace Home\Controller;
use Think\Controller;
use Org\WeChar\Wx_Api;
use Org\WeChar\Wx_JSSDK;

use Org\WeChar\WxPay\WxPay_Api;
use Org\WeChar\WxPay\WxPayData\WxPay_Data;
header('content-type:text/html;charset=utf-8');
class GroupBuysController extends Controller {
  public $app_id = 'wx3dd28eb27ded279b';
  public $app_secret = '9b7c0e147107c71f367ebb92573f18d5';
  public function _initialize(){
    $this->theme('default');
  }


  public function Index(){
    if ($_GET['once'] == '1') {
      $userinfo = $this->getUserData();
      if ($userinfo != 'ERROR') {
        session('userinfo',$userinfo);
      } else {
        $_GET['code'] = null;
        $userinfo = $this->getUserData();
        session('userinfo',$userinfo);
      }
      $_GET['once']=null;
      $urlinfo = U('GroupBuys/Index');
      $urlinfoget='';
      foreach ($_GET as $key => $value) {
        if (!empty($value)) {
          if ($urlinfoget =='') {
            $urlinfoget=$key.'='.$value;
          } else {
            $urlinfoget=$urlinfoget.'&'.$key.'='.$value;
          }
        }
      }
      $urlinfo = $urlinfo.'?'.$urlinfoget;
      redirect($urlinfo);
    }
    $uinfo = session('userinfo');
    if (empty($uinfo)) {
      $userinfo = $this->getUserData();
      if ($userinfo != 'ERROR') {
        session('userinfo',$userinfo);
      } else {
        $_GET['code'] = null;
        $userinfo = $this->getUserData();
        session('userinfo',$userinfo);
      }
      $_GET['once']=null;
      $urlinfo = U('GroupBuys/Index');
      $urlinfoget='';
      foreach ($_GET as $key => $value) {
        if (!empty($value)) {
          if ($urlinfoget =='') {
            $urlinfoget=$key.'='.$value;
          } else {
            $urlinfoget=$urlinfoget.'&'.$key.'='.$value;
          }
        }
      }
      $urlinfo = $urlinfo.'?'.$urlinfoget;
      redirect($urlinfo);
    }

    // var_dump($uinfo);exit();

    $pagedata['subscribe'] = $uinfo['subscribe'];
    $pagedata['uname'] = $uinfo['nickname'];
    $sahreuid = $_GET['uid'];
    $sahregid = $_GET['gid'];
    $wherearray['IsStart']  = array('eq','1');
    $wherearray['EndDate']  = array('gt',date('Y-m-d H:i:s',time()));
    if (!empty($sahregid)) {
      $wherearray['GroupId']  = array('eq',$sahregid);
    }
    $groupinfo = $this->GM('Tgroup')->where($wherearray)->find();
    if($groupinfo){
      $pagedata['hasgroup'] = 'true';
      $viewinfo['OpenId'] = $uinfo['openid'];
      $viewinfo['GroupId'] = $groupinfo['GroupId'];
      $viewinfo['Headimg'] = $uinfo['headimgurl'];
      $viewinfo['Nickname'] = $uinfo['nickname'];
      $this->saveuserview($viewinfo);
      if (!empty($sahreuid)) {
        if (floatval($groupinfo['Redpaper']>0)) {
          if ($sahreuid != $uinfo['openid']) {
            $reddata['price'] = $groupinfo['Redpaper'];
            $reddata['gid'] = $groupinfo['GroupId'];
            $reddata['shareuid'] = $sahreuid;
            $reddata['openid'] = $uinfo['openid'];
            $this->saveuserred($reddata);
          }
        }
      }

      $endtime =get_object_vars($groupinfo['EndDate'])['date'];
      $pagedata['time']=intval(strtotime($endtime))-intval(time());
      $groupinfo['Imgs'] = stripcslashes($groupinfo['Imgs']);
      $pagedata['groupinfo'] =$groupinfo;
      $uvinfo = $this->GM('tgroupviewer')->where(array('GroupId'=>$groupinfo['GroupId']))->select();
      $pagedata['uvinfo'] =$uvinfo;
      $pagedata['uvcount'] =count($uvinfo);
      $ubinfo = $this->GM('tgroupbuyer')->where(array('GroupId'=>$groupinfo['GroupId'],'IsPay'=>'1'))->select();
      $pagedata['ubcount'] =count($ubinfo);
      $pagedata['ubinfo'] =$ubinfo;
      $pagedata['Title'] =$groupinfo['Grouptitle'];
    } else {
      $pagedata['time']=0;
      $pagedata['hasgroup'] = 'false';
      $pagedata['Title'] ='团购信息';
    }
    // 点亮图标数目
    $wheresarray['OpenId']  = array('eq',$uinfo['openid']);
    $wheresarray['GroupId']  = array('eq',$groupinfo['GroupId']);
    $shareinfo = $this->GM('tgroupviewer')->where($wheresarray)->find();
    if($shareinfo){
      $pagedata['sharenums'] = $shareinfo['Sharenums'];
    } else {
      $pagedata['sharenums'] = 0;
    }
    // 门店信息
    $sinfo = $this->GM('storeinfo')->find();
    $pagedata['sinfo']=$sinfo;
    // 获取留言信息
    $sqlStr = "SELECT *,CONVERT(varchar(19),CreateDate,120)AS Cdata FROM RS_Tgrouprmk WHERE GroupId = '".$groupinfo['GroupId']."' ORDER BY CreateDate DESC";
    $remarkinfo = $this->GM()->query($sqlStr);
    $pagedata['remarkinfo'] = $remarkinfo;
    $pagedata['remarkcount'] = count($remarkinfo);
    // 右侧滚动信息
    $scrollinfo = $this->GM('tgrouprecord')->where(array('GroupId'=>$groupinfo['GroupId']))->order('Createdate desc')->select();
    $pagedata['scrollinfo'] = $scrollinfo;
    // 微信分享
    $jssdkObj=new \Org\WeChar\Wx_JSSDK($this->app_id,$this->app_secret,explode('/Home/Application/',str_replace('\\','/',realpath(dirname(__FILE__).'/')))[0].'/Home/Web');
    $wxJSSDKParam=$jssdkObj->getSignPackage();
    $wxJSSDKConfigArray=array(
      'debug'=>false,
      'appId'=>$wxJSSDKParam['appId'],
      'timestamp'=>$wxJSSDKParam['timestamp'],
      'nonceStr'=>$wxJSSDKParam['nonceStr'],
      'signature'=>$wxJSSDKParam['signature'],
      'jsApiList'=>array('hideAllNonBaseMenuItem','onMenuShareTimeline','onMenuShareAppMessage','showMenuItems')
    );
    $this->assign('wxJSSDKConfigStr',json_encode($wxJSSDKConfigArray));

    // 分享的信息
    $shareinfo['title']=$groupinfo['Grouptitle'];
    $shareinfo['desc']=$groupinfo['Grouptitle'];
    $shareinfo['link']='http://'.$_SERVER['HTTP_HOST'].U('Home/GroupBuys/Index',array('uid'=>$uinfo['openid'],'gid'=>$groupinfo['GroupId'],'once'=>'1'));
    $shareinfo['img']=$groupinfo['Logoimg'];
    $pagedata['shareinfo']=$shareinfo;

    $this->assign($pagedata);
    $this->display();
  }

  //生成订单信息
  public function createorder(){
    $gid = $_POST['gid'];
    $bname = $_POST['bname'];
    $bhone = $_POST['bphone'];
    $uinfo = session('userinfo');
    $wherearray['GroupId']  = array('eq',$gid);
    $wherearray['IsStart']  = array('eq','1');
    $wherearray['EndDate']  = array('gt',date('Y-m-d H:i:s',time()));
    $groupinfo = $this->GM('Tgroup')->where($wherearray)->find();
    if ($groupinfo) {
      $orderid = "T".date("YmdHis",time()).rand(1000,9999);
      $savedata=array(
        'GroupId'=>$gid,
        'OpenId'=>$uinfo['openid'],
        'OrderId'=>$orderid,
        'CreateDate'=>date("Y-m-d H:i:s",time()),
        'Headimg'=>$uinfo['headimgurl'],
        'Nickname'=>$uinfo['nickname'],
        'Phone'=>$bhone,
        'Name'=>$bname,
        'IsPay'=>'0',
      );
      $res= $this->GM('tgroupbuyer')->add($savedata);
      if ($res) {
        $payinfo = array(
          'oid'=>$orderid,
          'price' =>$groupinfo['Price'],
          'payopenid'=>$uinfo['openid'],
        );
        $wxpaydata = $this->getpayinfo($payinfo);
        if ($wxpaydata == false) {
          $this->ajaxReturn(array('status' => 'false','info'=>'GETPAYERROR'), 'JSON');
        } else {
          $this->ajaxReturn(array('status' => 'true','info'=>$wxpaydata), 'JSON');
        }
      } else {
        $this->ajaxReturn(array('status' => 'false','info'=>'SAVAERROR'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false','info'=>'NOTGINFO'), 'JSON');
    }
  }

  // 获取支付信息
  public function getpayinfo($info){
    $extStr='FJ'.rand(1000,9999);
    $oid=$info['oid'];
    $price=$info['price'];
    $payopenid=$info['payopenid'];

    // var_dump($price * 100);exit();

    $appid=C('WX_OPEN_APPID');
    $mchid=C('WX_PAY_MCHID');
    $apikey=C('WX_PAY_KEY');

    $sendData=array(
      'appid'=>$appid,
      'mch_id'=>$mchid,
      'out_trade_no'=>$oid.$extStr,
      'body'=>'团购-订单付款',
      // 'total_fee'=>1,
      'total_fee'=>$price*100,
      'trade_type'=>'JSAPI',
      'notify_url'=>'http://'.$_SERVER['HTTP_HOST'].U('GroupBuys/WXPayNotify'),
        'nonce_str'=>$this->createNonceStr('16'),
        'spbill_create_ip'=>$_SERVER['REMOTE_ADDR'],
        'openid'=>$payopenid,
      );

      $wxpayData=new WxPay_Data();
      $wxpayData->SetAllValues($sendData);
      $wxpayData->SetSign($apikey);
      $wxPayAPI= new WxPay_Api();
      $red=$wxPayAPI->unifiedOrder($wxpayData);
      if($red['res']){
        $response = $wxpayData->FromXml($red['data']);
        if ($response['return_code']=='SUCCESS'){
          $jsapipay['appId']=$response['appid'];
          $jsapipay['package']='prepay_id='.$response['prepay_id'];
          $jsapipay['timeStamp']= strval(time());
          $jsapipay['nonceStr']=$response['nonce_str'];
          $jsapipay['signType']='MD5';
          $wxpayData->SetAllValues($jsapipay);
          $jsapipay['paySign']=$wxpayData->SetSign($apikey);
          return $jsapipay;
        }else{
          return false;
        }
      } else {
        return false;
      }
    }

    public function WXPayNotify(){
      $msg=$GLOBALS["HTTP_RAW_POST_DATA"];
      $getdata=json_decode(json_encode(simplexml_load_string($msg, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
      $this->LOGS('商城微信支付反馈信息--->>>'.json_encode($getdata));
      $preArt=$getdata;
      unset($preArt['sign']);
      $returndate=json_decode($getdata['attach'],true);
      $key=C('WX_PAY_KEY');
      $sign=$this->lvs_sign($preArt,$key);
      if ($sign==$getdata['sign']) {
        $this->LOGS('微信支付验签成功');
        if ($getdata['result_code']=='SUCCESS') {
          $orderTrueId=explode('FJ',$getdata['out_trade_no'])[0];
          $res= $this->GM('tgroupbuyer')->where(array('OrderId'=>$orderTrueId))->save(array('IsPay'=>'1','PayDate'=>date("Y-m-d H:i:s",time())));
          if ($res){
            $orderinfo = $this->GM('tgroupbuyer')->where(array('OrderId'=>$orderTrueId))->find();
            if ($orderinfo) {
              $savedata['Content'] = '参与了活动';
              $savedata['Nickname'] = $orderinfo['Nickname'];
              $savedata['Headimg'] = $orderinfo['Headimg'];
              $savedata['Type'] = '0';
              $savedata['GroupId'] = $orderinfo['GroupId'];
              $this->GM('tgrouprecord')->add($savedata);
            }
            echo "<xml> <return_code><![CDATA[SUCCESS]]></return_code> <return_msg><![CDATA[OK]]></return_msg> </xml>";
          } else {
            $this->LOGS('商品状态已变更(支付冲突1)--->>>'.json_encode($res));
          }
        }else{
          $this->LOGS('商品状态已变更(支付冲突2)--->>>');
        }
      } else {
        $this->LOGS('微信支付验签失败>>>>>'.$sign.'>>>>>'.$getdata['sign']);
      }
    }

    // 保存浏览记录
    public function saveuserview($info){
      $uvinfo = $this->GM('tgroupviewer')->where(array('OpenId'=>$info['OpenId'],'GroupId'=>$info['GroupId']))->find();
      if (!$uvinfo) {
        $res = $this->GM('tgroupviewer')->add($info);
      }
    }

    // 保存红包信息
    public function saveuserred($info){
      $urinfo = $this->GM('tgroupredinfo')->where(array('OpenId'=>$info['openid'],'ShareUid'=>$info['shareuid'],'GroupId'=>$info['gid']))->find();
      if (!$urinfo) {
        $redprice = rand(1,$info['price']);
        $sinfo['GroupId']=$info['gid'];
        $sinfo['Price']=$redprice;
        $sinfo['OpenId']=$info['openid'];
        $sinfo['ShareUid']=$info['shareuid'];
        $sinfo['GetRedCode']=rand(100000,999999);
        $sinfo['Status']='0';
        $res = $this->GM('tgroupredinfo')->add($sinfo);
      }
    }
    //  分享次数处理
    public function sharesuccess(){
      if (IS_POST) {
        $gid = $_POST['gid'];
        $uinfo = session('userinfo');
        $openid = $uinfo['openid'];
        // 是否可以领取分享红包
        $urinfo = $this->GM('tgroupredinfo')->where(array('OpenId'=>$openid,'ShareUid'=>$openid,'GroupId'=>$gid))->find();
        if ($urinfo) {
          $pagedata['hasred'] = 'false';
        } else {
          $groupinfo = $this->GM('Tgroup')->where(array('GroupId'=>$gid))->find();
          $redprice = rand(1,$groupinfo['Redpaper']);
          $sinfo['GroupId']=$groupinfo['GroupId'];
          $sinfo['Price']=$redprice;
          $sinfo['OpenId']=$openid;
          $sinfo['ShareUid']=$openid;
          $sinfo['GetRedCode']=rand(100000,999999);
          $sinfo['Status']='0';
          $res = $this->GM('tgroupredinfo')->add($sinfo);
          if($res){
            $pagedata['hasred'] = $redprice;
          } else {
            $pagedata['hasred'] = 'false';
          }
        }
        // 是否点亮分享次数
        $wherearray['OpenId']  = array('eq',$openid);
        $wherearray['GroupId']  = array('eq',$gid);
        $shareinfo = $this->GM('tgroupviewer')->where($wherearray)->find();
        if ($shareinfo) {
          $sahrenums = $shareinfo['Sharenums'];
          if ($shareinfo['Sharedate'] != date('Y-m-d',time())) {
            $wherearray['OpenId']  = array('eq',$openid);
            $wherearray['GroupId']  = array('eq',$gid);
            $savedata['Sharenums'] = $sahrenums+1;
            $savedata['Sharedate'] = date('Y-m-d',time());
            $res = $this->GM('tgroupviewer')->where($wherearray)->save($savedata);
            if ($res) {
              if($sahrenums<4){
                $savesdata['Content'] = '点亮了图标';
                $savesdata['Nickname'] = $uinfo['nickname'];
                $savesdata['Headimg'] = $uinfo['headimgurl'];
                $savesdata['Type'] = '1';
                $savesdata['GroupId'] = $gid;
                $this->GM('tgrouprecord')->add($savesdata);
              }
              $pagedata['sharenums'] = $sahrenums + 1;
            } else {
              $pagedata['sharenums'] = $sahrenums;
            }
          } else {
            $pagedata['sharenums'] = $sahrenums;
          }
        } else {
          $pagedata['sharenums'] = 'false';
        }

        $this->ajaxReturn(array('status' => 'true','info'=>$pagedata), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false','info'=>'NOTPOST'), 'JSON');
      }
    }
    //保存留言信息
    public function saveremark(){
      if (IS_POST) {
        $gid = $_POST['gid'];
        $remark = $_POST['remark'];
        $uinfo = session('userinfo');
        $savedata['GroupId']=$gid;
        $savedata['Content']=$remark;
        $savedata['Headimg']=$uinfo['headimgurl'];
        $savedata['Nickname']=$uinfo['nickname'];
        $res = $this->GM('tgrouprmk')->add($savedata);
        if ($res) {
          $savesdata['Content'] = '参与了留言';
          $savesdata['Nickname'] = $uinfo['nickname'];
          $savesdata['Headimg'] = $uinfo['headimgurl'];
          $savesdata['Type'] = '2';
          $savesdata['GroupId'] = $gid;
          $this->GM('tgrouprecord')->add($savesdata);
          $this->ajaxReturn(array('status' => 'true','info'=>'NOTPOST'), 'JSON');
        } else {
          $this->ajaxReturn(array('status' => 'false','info'=>'SAVEERROR'), 'JSON');
        }
      } else {
        $this->ajaxReturn(array('status' => 'false','info'=>'NOTPOST'), 'JSON');
      }
    }
    public function mycenter(){
      $uinfo = session('userinfo');
      if (empty($uinfo)) {
        $userinfo = $this->getUserData();
        if ($userinfo != 'ERROR') {
          session('userinfo',$userinfo);
        } else {
          $_GET['code'] = null;
          $userinfo = $this->getUserData();
          session('userinfo',$userinfo);
        }
      }

      $sinfo = $this->GM('storeinfo')->find();
      $pagedata['sinfo']=$sinfo;
      // 已经参团信息
      $sqlStr="SELECT g.*,gb.Name,gb.Phone,CONVERT(varchar(10),gb.CreateDate,120)AS Cdata FROM RS_Tgroupbuyer gb LEFT JOIN RS_Tgroup g ON gb.GroupId = g.GroupId WHERE gb.IsPay ='1' AND gb.OpenId='".$uinfo['openid']."'  ORDER BY gb.CreateDate desc";
      $ginfo = $this->GM()->query($sqlStr);
      $pagedata['ginfo']=$ginfo;
      // 红包信息
      $sqlStr="SELECT Price,CONVERT(varchar(10),CreateDate,120)AS Cdata,Status,GetRedCode  FROM RS_Tgroupredinfo WHERE ShareUid ='".$uinfo['openid']."' ORDER BY CreateDate DESC";
      $rinfo = $this->GM()->query($sqlStr);
      $pagedata['rinfo']=$rinfo;

      $pagedata['Title']='个人中心';
      $this->assign($pagedata);
      $this->display();
    }



    //获取用户微信数据
    public function getUserData() {
      $userinfo=Wx_Api::getOpenId(array('wx_appid'=>trim($this->app_id),'wx_appsecret'=>trim($this->app_secret),'site_url'=>'http://'.$_SERVER['HTTP_HOST'].U($Think.MODULE_NAME.'/'.$Think.CONTROLLER_NAME.'/'.$Think.ACTION_NAME,$_GET)));
      return $userinfo;
    }
    //用户数据库读取
    public function GM($tableName)
    {
      return M($tableName,C('DB_GROUP')['DB_PREFIX'],'DB_GROUP');
    }

    //GET请求
    public function httpGet($url)
    {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_TIMEOUT, 500);

      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
      curl_setopt($curl, CURLOPT_URL, $url);

      $res = curl_exec($curl);
      curl_close($curl);

      return $res;
    }

    public function createNonceStr($length = 16){
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $str = "";
      for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
      }
      return $str;
    }

    private function lvs_sign($data,$key){
      ksort($data);
      $str='';
      foreach ($data as $dk => $value) {
        $str.=$dk."=".$value."&";
      }
      $str=$str.'key='.$key;
      $this->LOGS($str);
      $sign=strtoupper(md5($str));
      $this->LOGS($sign);
      return $sign;
    }

    /**
    * 用户操作记录log
    */
    public function LOGS($desc='',$controller=CONTROLLER_NAME,$function=ACTION_NAME){
      $filename=date('Y-m-d',time());
      $logfile=str_replace('\\','/',strrev(substr(strrev(dirname(__FILE__)),10))).'logs/';
      if (!is_dir($logfile)) {
        mkdir($logfile,777);
      }
      $logfile=$logfile.$filename.'.txt';
      // var_dump($logfile);exit;
      $content='操作定位:'.$controller.'/'.$function.'::::'.$desc.'::::::操作日期:::::::'.$this->nowTimeParam['datetime'].PHP_EOL;
      file_put_contents($logfile,$content,FILE_APPEND);
    }

  }?>
