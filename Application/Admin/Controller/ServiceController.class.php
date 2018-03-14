<?php
/*
客服服务
*/
namespace Admin\Controller;
use Think\Controller;
header('content-type:text/html;charset=utf-8');
class ServiceController extends CommonController{
  public function _initialize(){
    parent::_initialize();
  }
  public function index(){
    // var_dump(session());exit;
    $this->assign('serviceid',session('userinfo')['ID']);
    $this->assign('servicename',session('userinfo')['userName']);
    $this->assign('serviceimg',session('HeadImgUrl'));
    define('FPAGE','KEFU');
    $this->display();
  }
  ////////////////////聊天图片/////////////
  public function savesengimg(){
    //var_dump($_FILES['iptxtp']);
    if (IS_POST) {
      $upload=new \Think\Upload();
      $upload->maxSize=3145728;
      $upload->savePath='./Chats/';
      $upload->exts=array('jpg','png','jpeg');
      $info=$upload->uploadOne($_FILES['simg']);
      if (!$info) {
        $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'bcError'), 'JSON');
      }else{
        $ImgUrls="/Upload".substr($info['savepath'], 1).$info['savename'];
        $this->ajaxReturn(array('status' => 'true', 'datainfo' => $ImgUrls), 'JSON');
      }
    }
  }

  ///////////////客服发送消息///////////////
  public function sendmsg(){
    if (IS_POST) {
      $sendchat=$_POST['sendconter'];
      $kid=$_POST['kfid'];
      $mid=$_POST['mid'];
      $type=$_POST['type'];
      $file='./chats/'.$mid.'-'.$kid.'.xml';
      if(!file_exists($file)){
        $doc= new\DOMDocument("1.0","utf-8");
        $doc->formatOutput=true;
        ////创建根节点/////
        $chats=$doc->createElement("chats");
        $chats=$doc->appendChild($chats);
        ////聊天信息节点////
        $chat=$doc->createElement("chat");
        $chat=$chats->appendChild($chat);
        ////谁说的话///////
        $type=$doc->createElement("type");
        $type=$chat->appendChild($type);
        $type->appendChild($doc->createTextNode("from"));
        ////客服编号//////
        // $kfid=$doc->createElement("kfid");
        // $kfid=$chat->appendChild($kfid);
        // $kfid->appendChild($doc->createTextNode($kid));
        ////时间日期////
        $datetime=$doc->createElement("datetime");
        $datetime=$chat->appendChild($datetime);
        $datetime->appendChild($doc->createTextNode(date('Y-m-d H:i:s', time())));
        /////聊天内容/////
        $content=$doc->createElement("content");
        $content=$chat->appendChild($content);
        $content->appendChild($doc->createTextNode($sendchat));
        if($type==1){
          $attrt=$doc->createAttribute('type');
          $attrt->appendChild($doc->createTextNode('1'));
          $content->appendChild($attrt);
        }
        $doc->save($file);
      } else {
        $doc = new\DOMDocument();
        $doc->load($file);
        ////读取跟节点/////
        $chats=$doc->getElementsByTagName("chats")->item(0);
        ////聊天信息节点////
        $chat=$doc->createElement("chat");
        $chat=$chats->appendChild($chat);
        ////谁说的话///////
        $type=$doc->createElement("type");
        $type=$chat->appendChild($type);
        $type->appendChild($doc->createTextNode("from"));
        ////客服编号//////
        // $kfid=$doc->createElement("kfid");
        // $kfid=$chat->appendChild($kfid);
        // $kfid->appendChild($doc->createTextNode($kid));
        ////时间日期////
        $datetime=$doc->createElement("datetime");
        $datetime=$chat->appendChild($datetime);
        $datetime->appendChild($doc->createTextNode(date('Y-m-d H:i:s', time())));
        /////聊天内容/////
        $content=$doc->createElement("content");
        $content=$chat->appendChild($content);
        $content->appendChild($doc->createTextNode($sendchat));
        if($type==1){
          $attrt=$doc->createAttribute('type');
          $attrt->appendChild($doc->createTextNode('1'));
          $content->appendChild($attrt);
        }
        $doc->save($file);
      }
      $this->ajaxReturn(array('status' => 'true', 'datainfo' => $sendchat), 'JSON');
    } else{
      $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'postError'), 'JSON');
    }
  }





}
?>
