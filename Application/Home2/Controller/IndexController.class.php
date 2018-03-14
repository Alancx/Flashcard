<?php
namespace Home2\Controller;
use Think\Controller;
class IndexController extends AllowController {
    private  $appid="wx3dd28eb27ded279b";
    private  $appsecret="9b7c0e147107c71f367ebb92573f18d5";
    public function index(){
        $openid=session('openid');
        $week=date('W');
        $year=date('Y');
        // var_dump($openid);
        $weeklist=array(
            array('year'=>date('Y'),'week'=>date('W')),
            array('year'=>date('Y'),'week'=>date('W')-1),
            
            ); 
        $list=M()->query("SELECT us.openid,us.name,ts.title,sp.content,sp.uid,sp.id,us.pic,sp.state FROM speed sp LEFT JOIN taskk ts ON ts.id = sp.tid LEFT JOIN users us ON us.openid=sp.uid WHERE ts.week='{$week}' AND ts.year='{$year}' AND ts.status='2' AND sp.uid='{$openid}'");
        // var_dump($list);exit;
        $result=array();
        foreach($list as $key => $value){
            if(empty($result[$value['title']]) || $result[$value['title']] == ''){
                $result[$value['title']]= $value;
                $result[$value['title']]['list']=array();
                array_push($result[$value['title']]['list'],array('content'=>$value['content'],'id'=>$value['id'],'state'=>$value['state']));
            }else{
                array_push($result[$value['title']]['list'],array('content'=>$value['content'],'id'=>$value['id'],'state'=>$value['state'])); 
            }
        }
        $this->assign('weeklist',$weeklist); 
        // var_dump($result);exit;
        $this->assign('result',$result);
        $this->display('Index/index');      
    }
    public function updata(){
        // var_dump($_POST);exit;
        $id=$_POST;
        $name=session('name');
        $data['addtime']=date('Y:m:d H:i:s',time());
        $string=implode(',',$id);
        // var_dump($id);
        $mod=M()->execute("UPDATE speed SET state='2' WHERE id IN ({$string})");
            
        // var_dump($row);exit;
        // var_dump($list);
        if($mod){
            $content='';
            $list=M('users')->where('isadmin=2')->select();
            $row=M()->query("SELECT ts.title,sp.content FROM taskk ts LEFT JOIN speed sp  ON sp.tid=ts.id WHERE sp.id in({$string})");
            foreach ($row as $key => $value) {
                $contents=$value['content'];
                $title=$value['title'];
                $content=$content.';'.$contents;
                // var_dump($title);
                // var_dump($content);exit;
            }
            foreach ($list as $key => $value) {
                $openid=$value['openid'];
                $xml=array("touser"=>"{$openid}",
                "template_id"=>"8FNt1bkkmKFaUXHAKREdaQOARevhORID208qdyNOYvY",
                'data'=>array('first'=>array('value'=>"{$name}向你汇报了任务进度",'color'=>'#000000'),'keyword1'=>array('value'=>"{$title}",'color'=>'#000000'),'keyword2'=>array('value'=>"{$name}",'color'=>'#000000'),'keyword3'=>array('value'=>"{$data['addtime']}",'color'=>'#000000'),'keyword4'=>array('value'=>"{$content}",'color'=>'#000000'),'remark'=>array('value'=>'任务已完成请尽快查看','color'=>'#000000'))); 
                $this->postXmlCurl(json_encode($xml)); 
            } 

           $this->ajaxReturn(array('status' => 'true', 'info' =>'汇报成功'), 'JSON');
        }else{
           $this->ajaxReturn(array('status' => 'false', 'info' =>'汇报失败'), 'JSON');
        }
    }
    public function note(){
        $this->display('Index/biji');
    }
    public function donote(){
        // var_dump($_POST);
        $data['title']=$_POST['title'];
        $data['content']=$_POST['content'];
        $data['addtime']=date('Y:m:d H-i-s',time());
        $data['openid']=session('openid');
        $mod=M('note')->add($data);
        if($mod){
            $this->success('保存成功','Index/index');
        }else{
            $this->error('保存失败','Index/index');
        }
    }
    public function select(){
        $openid=session('openid');
        // var_dump($openid);
        $mod=M()->query("SELECT * FROM note WHERE openid='{$openid}'");
        // var_dump($mod);exit;
        $this->assign('mod',$mod);
        $this->display('Index/wode');
    }
    public function delete(){
        // var_dump($_GET['id']);
        $mod=M('note')->delete($_GET['id']);
        if($mod){
           $this->success('删除成功','Select'); 
        }else{
           $this->error('删除失败','Select'); 
        }
    }
    public function update(){
        $mod=M('note')->find($_GET['id']);
        // var_dump($mod);exit;
        $this->assign('mod',$mod);
        $this->display('Index/up');
    }
    public function doupdate(){
        // var_dump($_POST);exit;
        $data['title']=$_POST['title'];
        $data['content']=$_POST['content'];
        $data['addtime']=date('Y:m:d H-i-s',time());
        $mod=M('note')->where('id='.$_POST['id'])->save($data);
        // var_dump(M()->getlastsql());exit;
        if($mod){
            $this->success('修改成功','Select'); 
        }else{
            $this->error('修改失败','Select'); 
        }
    }
    public function addtime(){
        $openid=session('openid');
        $week=$_POST['weeks'];
        $year=$_POST['year'];
        if($year==date('Y') && $week==date('W')){
            $sta='1';
        }else{
            $sta='2';
        }
        $sqlStr="SELECT us.openid,us.name,ts.title,sp.content,sp.uid,us.pic,sp.state,sp.id FROM speed sp LEFT JOIN taskk ts ON ts.id = sp.tid LEFT JOIN users us ON us.openid=sp.uid WHERE ts.week='{$week}' AND ts.year='{$year}' AND sp.uid='{$openid}' ORDER BY ts.addtime DESC";          
        $list=M()->query($sqlStr);
        // var_dump($sqlStr);exit;
        $result=array();
        foreach($list as $key => $value){
            if(empty($result[$value['title']]) || $result[$value['title']] == ''){
                $result[$value['title']]= $value;
                $result[$value['title']]['list']=array();
                array_push($result[$value['title']]['list'],array('content'=>$value['content'],'id'=>$value['id'],'state'=>$value['state']));
            }else{
                array_push($result[$value['title']]['list'],array('content'=>$value['content'],'id'=>$value['id'],'state'=>$value['state'])); 
            }
        }
        if($result){
            $this->ajaxReturn(array('status' => 'true', 'info' =>$result,'sta'=>$sta), 'JSON');
        }else{
             $this->ajaxReturn(array('status' => 'false', 'info' =>'获取失败'), 'JSON'); 
        }
        
    }
    public function postXmlCurl($xml, $useCert = false, $second = 30)
    {   
        // $time=date('Y:m:d H:i:s',time());
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appsecret}";
        $url=file_get_contents($url);
        $url=json_decode($url,true);
        $urll="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$url['access_token']}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $urll);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/x-www-form-urlencoded"));
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        // var_dump($url);
        //返回结果
        // var_dump($data);
        if($data){
            curl_close($ch);
            $res['status']=true;
            $res['info']=$data;
            // $this->LOGS($data);
            return $res;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            $res['status']=false;
            $res['info']="curl出错，错误码:$error";
            $this->LOGS(json_encode($res));
            return $res;
        }
        // var_dump($xml);
    }
}
?>