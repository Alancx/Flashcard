<?php
namespace Seller\Controller;
use Think\Controller;
/**
 * 支付配置类--20160326--leaves
 */
class PaysetController extends CommonController
{

  function _initialize()
  {
    parent::_initialize();
  }

  public function index(){
    $config=$this->MSL('wxpayset')->where("token='%s'",$this->token)->find();
    $this->assign('config',$config);
    $this->display();
  }

  /**
   * 保存微信支付配置内容
   */

   public function wxpaysave(){
    //  echo "<pre>";
    //  var_dump($_POST);exit;
    if (IS_POST) {
      $data['appid']=$_POST['APPID'];
      $data['appsecret']=$_POST['APPSECRET'];
      $data['mchid']=$_POST['mchid'];
      $data['apikey']=$_POST['api'];
      $data['apiclient_cert']=$_POST['apiclient_cert'];
      $data['apiclient_key']=$_POST['apiclient_key'];
      $data['createtime']=time();
      $data['token']=$this->token;
      if ($_POST['ID']) {
        if ($this->MSL('wxpayset')->where("token='%s'",$this->token)->save($data)) {
          $this->success('设置成功');
        }else{
          $this->error('设置失败');
        }
      }else{
        if ($this->MSL('wxpayset')->add($data)) {
          $this->success('设置成功');
        }else{
          $this->error('设置失败');
        }
      }
    }else{
      $this->error('非法操作');
    }
   }

}














 ?>
