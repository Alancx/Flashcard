<?php
namespace Home\Controller;
use Think\Controller;
use Org\WeChar\Wx_Api;
use Org\WeChar\Wx_JSSDK;
header("Content-type: text/html; charset=utf-8");
class BaseController extends Controller
{
  public $stoken;
  public $nowmca;
  public $app_id = 'wx3dd28eb27ded279b';
  public $app_secret = '9b7c0e147107c71f367ebb92573f18d5';
  public $token = 'rhbnja145862596121';

  public function _initialize() {

    $this->stoken = $_GET['stoken'];//判断店铺的唯一标识是否存在
    $this->nowmca=$Think.MODULE_NAME.'/'.$Think.CONTROLLER_NAME.'/'.$Think.ACTION_NAME;
    if(!in_array($this->nowmca,C('NOT_GET_WXMCA'))){
      $openid = session('openid');
      if (empty($openid) || $openid == '') {//如果没有openid代表用户没有登陆，将数据写入到数据库将openid存入session
        $userData = $this->getUserData();   //获取到的微信的用户信
        session('subscribe',$userData['subscribe']);
        $res = M()->table("RS_Member")->where("openid='".$userData['openid']."'")->find();//只查询出一条语句
        if ($res) {
          session('openid',$res['OpenId']);
          session('img',$res['HeadImgUrl']);//将用户头像保存在session中
          session('username',$res['MemberName']);//将用户名保存在session中
          session('memberId',$res['MemberId']);
        } else {
          $data = array();
          $data['openid'] = $userData['openid'];  //获取微信的openID
          $data['MemberName'] = $userData['nickname'];  //获取微信的昵称
          $data['sex'] = $userData['sex'];    //获取微信用户的性别
          $data['city'] = $userData['city'];  //获取微信用户的城市
          $data['province'] = $userData['province'];  //获取微信用户的省份
          $data['headimgurl'] = $userData['headimgurl'];  //获取微信用户的头像
          $data['MemberId'] = time().rand(1000,9999); //设置用户ID  时间戳+4位随机数
          $data['MemberPwd'] = md5('123456');  //1位随机字母+时间戳
          $data['token'] = $this->token;  //获取token值
          $data['RegisterDate'] = date("Y-m-d H:i:s",time()); //注册时间
          $re = M()->table("RS_Member")->add($data); //将用户的信息插入到数据库
          if($re) {
            session('openid',$userData['openid']);
            session('img',$userData['headimgurl']);
            session('username',$userData['nickname']);
            session('memberId',$data['MemberId']);
          }
        }
      }
      // var_dump($_GET);exit();
      if ($_GET['once']=='1') {

        $userData = $this->getUserData();
        session('subscribe',$userData['subscribe']);
        session('getonce','1');
        if (empty($_GET['stoken']) || $_GET['stoken'] == '')  {
          $shopinfo = M()->table('RS_MemberCollect')->where(array('MemberId'=>session('memberId'),'IsType'=>'SHOP','IsDefault'=>'1'))->find();
          if (!$shopinfo) {
            $shopinfo = M()->table('RS_MemberCollect')->where(array('MemberId'=>session('memberId'),'IsType'=>'SHOP'))->find();
          }
          if ($shopinfo) {
            session('stoken',$shopinfo['stoken']);
            $this->stoken=session('stoken');
          } else {
            $this->redirect('Common/errorpage');
          }
        } else {
          session('stoken',$_GET['stoken']);
          $this->stoken=session('stoken');
          $shopinfo = M()->table('RS_MemberCollect')->where(array('MemberId'=>session('memberId'),'IsType'=>'SHOP','stoken'=>$_GET['stoken']))->find();
          if(!$shopinfo){
            $this->shopcollect('1');
          } else {
            $this->shopcollect('2');
          }
        }
        if ($_GET['SID'] != null) {
          $sharerID=M()->table('RS_Shares')->where(array('ID'=>$_GET['SID']))->getField('Sharer');
          if ($sharerID != null || $shareeID !='') {
            cookie('sharerID',$sharerID);
          } else {
            cookie('sharerID',null);
          }
        } else {
          cookie('sharerID',null);
        }

        if ($_GET['inred'] != null) {
          $_GET['inred'] = null;
          cookie('inredinfo','true');
          cookie('inredinfofirst','true');
        } else {
          cookie('inredinfo',null);
        }

        if($this->nowmca !='Home/Table/Singlepoint'){
          cookie('tableID',null);
        } else {
          $_GET['once']='2';
          $this->redirect('Table/Singlepoint',$_GET);
        }
        if ($this->nowmca=='Home/Index/Index') {
          $_GET['once']='2';
          $this->redirect('Index/Index',$_GET);
        }
        if ($this->nowmca=='Home/Goods/goods') {
          $_GET['once']='2';
          $this->redirect('Goods/goods',$_GET);
        }
      }elseif ($_GET['once']=='2') {
        $this->stoken=session('stoken');
        session('getonce','2');
      }else {
        $this->stoken=session('stoken');
        session('getonce','3');
      }

      $jssdkObj=new \Org\WeChar\Wx_JSSDK($this->app_id,$this->app_secret,explode('/Home/Application/',str_replace('\\','/',realpath(dirname(__FILE__).'/')))[0].'/Home/Web');

      $this->wxJSSDKParam=$jssdkObj->getSignPackage();

      $this->wxJSSDKConfigArray=array(
        'debug'=>false,
        'appId'=>$this->wxJSSDKParam['appId'],
        'timestamp'=>$this->wxJSSDKParam['timestamp'],
        'nonceStr'=>$this->wxJSSDKParam['nonceStr'],
        'signature'=>$this->wxJSSDKParam['signature'],
        'jsApiList'=>array('hideAllNonBaseMenuItem','onMenuShareTimeline','onMenuShareAppMessage','showMenuItems')
      );
      $subscribe = session('subscribe');
      $getonce = session('getonce');
      $this->assign('wxJSSDKConfigStr',json_encode($this->wxJSSDKConfigArray));
      $this->assign('up_url',$_SERVER['HTTP_REFERER']);
      $this->assign('nowmca_url',$this->nowmca);
      $this->assign('shopstoken',$this->stoken);
      $this->assign('Subscribe',$subscribe);
      $this->assign('getonce',$getonce);
      $this->assign('coninfo','1');
      $this->nowBootBarImg();
    }
  }
  /*
  获取用户信息
  */
  public function getUserData() {
    $userinfo=Wx_Api::getOpenId(array('wx_appid'=>trim($this->app_id),'wx_appsecret'=>trim($this->app_secret),'site_url'=>'http://'.$_SERVER['HTTP_HOST'].U($Think.MODULE_NAME.'/'.$Think.CONTROLLER_NAME.'/'.$Think.ACTION_NAME,$_GET)));
    return $userinfo;
  }

  public function nowBootBarImg()
  {
    $bootBaractive=array(
      '','','',''
    );
    $bootBarIcon=array(
      'home_icon','huod_icon','order_icon','mine_icon'
    );
    switch ($this->nowmca) {
      case 'Home/Index/Index':
      $bootBaractive[0]= 'showactive';
      $bootBarIcon[0]= 'home_iconactive';
      $this->assign('footerSign','1');
      $this->assign('nowBootBarStatus',$bootBaractive);
      $this->assign('nowBootBarIcon',$bootBarIcon);
      break;
      case 'Home/Index/activity':
      $bootBaractive[1]= 'showactive';
      $bootBarIcon[1]= 'activity_iconactive';
      $this->assign('footerSign','1');
      $this->assign('nowBootBarStatus',$bootBaractive);
      $this->assign('nowBootBarIcon',$bootBarIcon);
      break;
      case 'Home/User/Userorders':
      $bootBaractive[2]= 'showactive';
      $bootBarIcon[2]= 'order_iconactive';
      $this->assign('footerSign','1');
      $this->assign('nowBootBarStatus',$bootBaractive);
      $this->assign('nowBootBarIcon',$bootBarIcon);
      break;
      case 'Home/User/Index':
      $bootBaractive[3]= 'showactive';
      $bootBarIcon[3]= 'mine_iconactive';
      $this->assign('footerSign','1');
      $this->assign('nowBootBarStatus',$bootBaractive);
      $this->assign('nowBootBarIcon',$bootBarIcon);
      break;

      default:
      # code...
      break;
    }
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

  public function createNonceStr($length = 16){
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
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
    //$res=curl_errno($curl);
    curl_close($curl);

    return $res;
  }

  // 关注门店
  public function shopcollect($type){
    $res = M()->table('RS_MemberCollect')->where(array('MemberId'=>session('memberId'),'IsType'=>'SHOP'))->save(array('IsDefault'=>'0'));
    if ($type=='1') {
      $savedata['MemberId'] = session('memberId');
      $savedata['ProId'] = '';
      $savedata['IsType'] = 'SHOP';
      $savedata['token'] = $this->token;
      $savedata['stoken'] = $this->stoken;
      $savedata['IsDefault'] = '1';
      $res = M()->table('RS_MemberCollect')->add($savedata);
    } elseif ($type=='2') {
      // var_dump('s');exit();
      $res = M()->table('RS_MemberCollect')->where(array('MemberId'=>session('memberId'),'IsType'=>'SHOP','stoken'=>$this->stoken))->save(array('IsDefault'=>'1'));
    }
  }
// 分享红包的处理
  public function shareinfo(){
    $data=$_POST;
    $Share=new ShareController();
    if ($data['s']=='preshare') {
      $res=$Share->preshare($data,true,true);
      $this->ajaxReturn($res);
    } elseif ($data['s']=='shareout') {
      $res=$Share->shareout($data['ID']);
      $this->ajaxReturn($res);
    }
  }

  /////////发送微信模板消息/////////
  	public function sendWxMessage($info)
  	{
  		import("Vendor.Wechat.WXTemplate");
  		$wxchatinfo['appid']=$this->app_id;
  		$wxchatinfo['appsecert']=$this->app_secret;
  		$sendwxchat=new \WXTemplate($wxchatinfo);
  		$res=$sendwxchat->sendTemplate($info);
      $this->LOGS('微信消息---结果'.$res);
  		return $res;

  	}



}?>
