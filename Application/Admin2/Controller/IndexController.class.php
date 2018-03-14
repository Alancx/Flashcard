<?php
namespace Admin2\Controller;
use Think\Controller;
class IndexController extends AllowController {
    private  $appid="wx3dd28eb27ded279b";
    private  $appsecret="9b7c0e147107c71f367ebb92573f18d5";
    public function index(){
        $weeklist=array(
            array('year'=>date('Y'),'week'=>date('W')),
            array('year'=>date('Y'),'week'=>date('W')-1),
            
            ); 
        $row=M()->query("SELECT * FROM taskk WHERE year='".date('Y')."' AND week= '".date('W')."' ORDER BY addtime DESC");
        // var_dump($row);exit(); 
        $this->assign('row',$row);
        $this->assign('weeklist',$weeklist);         
        $this->display('Index/index');
    }
    public function add(){
        $tid=$_GET['tid'];
        if($tid!='add'){
            $list=M('taskk')->where('id='.$tid)->find();
            $result=M()->query("SELECT us.pic,us.name,us.openid,sp.uid,sp.content,sp.tid,sp.state FROM speed sp LEFT JOIN users us ON sp.uid=us.openid WHERE sp.tid='{$tid}'");
            // var_dump($list);
            // var_dump($result);exit;
            $resultt=array();
            foreach ($result as $key => $value) {
                if(empty($resultt[$value['uid']]) || $resultt[$value['uid']]==''){
                    $resultt[$value['uid']]=$value;
                    $resultt[$value['uid']]['list']=array();
                    array_push($resultt[$value['uid']]['list'],array('content'=>$value['content'],'tid'=>$value['tid'],'state'=>$value['state']));
                }else{
                    array_push($resultt[$value['uid']]['list'],array('content'=>$value['content'],'tid'=>$value['tid'],'state'=>$value['state'])); 
                }
            }
            // var_dump($resultt);exit;
            $this->assign('list',$list);
            $this->assign('resultt',$resultt);    
        }
        $rows=M()->query("SELECT * FROM users");
        $this->assign('tid',$tid);
        $this->assign('rows',$rows);
        $this->display('Index/add');
    }
    public function doadd(){
        // var_dump($_POST);exit;
        M()->startTrans();
        $tid=$_POST['tid'];
        if($_POST['title']==''){
            $title=date('W').'周任务安排';
        }else{
            $title=$_POST['title']; 
        }
        $rlist=$_POST['rlist'];
        $type=$_POST['type'];
        // $date=array('id'=>'','content'=>'');
        $data['title']=$title;
        $data['addtime']=date('Y:m:d H:i:s',time());
        $data['status']=$type;
        $data['openid']=session('openid');
        $data['week']=date('W');
        $data['year']=date('Y');
        if($tid!='add'){
            $res=M()->table('taskk')->where('id='.$tid)->save(array('title'=>$title));
        }else{
            $res=M()->table('taskk')->add($data);  
        }
        // var_dump(M()->getlastsql());exit;
        if($res!==false){
            $reg=true;
            $red=true;
            if($tid!='add'){
                $reg=M()->table('speed')->where('tid='.$tid)->delete();
            }
            foreach ($rlist as $key => $value) {
                $uid=$value['id'];
                $contens='';
                foreach($value['contentlist'] as $k =>$v){
                    $content=$v;
                    $sdata['uid']=$uid;
                    if($tid!='add'){
                       $sdata['tid']=$tid; 
                    }else{
                      $sdata['tid']=$res;  
                    }
                    $sdata['content']=$content;

                    $sdata['state']='1';
                    $contens=$contens.';'.$content;
                   
                    $ref=M()->table('speed')->add($sdata);
                    if(!$ref){
                        $red=false;
                        break 2; 
                    }
                } 
                if($type==2){
                    $xml=array("touser"=>"{$sdata['uid']}",
                    "template_id"=>"8FNt1bkkmKFaUXHAKREdaQOARevhORID208qdyNOYvY",
                    'data'=>array('first'=>array('value'=>'管理员给你发布了一条任务','color'=>'#000000'),'keyword1'=>array('value'=>"{$title}",'color'=>'#000000'),'keyword2'=>array('value'=>"管理员",'color'=>'#000000'),'keyword3'=>array('value'=>"{$data['addtime']}",'color'=>'#000000'),'keyword4'=>array('value'=>"{$contens}",'color'=>'#000000'),'remark'=>array('value'=>'请尽快完成','color'=>'#000000'))); 
                     $this->postXmlCurl(json_encode($xml));   
                }
            }
            
            // $xml=json_encode($date);
            if($red && $reg){
                M()->commit();
                $this->ajaxReturn(array('status' => 'true', 'info' =>'添加成功'), 'JSON');
            }else{
                M()->rollback();
                $this->ajaxReturn(array('status' => 'false', 'info' =>'添加失败'), 'JSON');   
            }
        }else{
            M()->rollback();
            $this->ajaxReturn(array('status' => 'false', 'info' =>'添加失败'), 'JSON'); 
        }   
    }
   
    public function jindu(){
        // var_dump($_GET);
        $id=$_GET['tid'];
        $weeklist=array(
            array('year'=>date('Y'),'week'=>date('W')),
            array('year'=>date('Y'),'week'=>date('W')-1), 
            ); 
        $week=date('W');
        $year=date('Y');
        // $row=M()->query("SELECT * FROM taskk WHERE year='".date('Y')."' AND week= '".date('W')."' ORDER BY addtime DESC");
        $list=M()->query("SELECT us.openid,us.name,ts.title,sp.content,sp.uid,sp.tid,us.pic,sp.state FROM speed sp LEFT JOIN taskk ts ON ts.id = sp.tid LEFT JOIN users us ON us.openid=sp.uid WHERE  ts.status='2' AND sp.tid='{$id}'");
        // $row=M()->query();
        // var_dump(M()->getlastsql());exit;
        // var_dump($list);exit;
        $result=array();
        foreach($list as $key => $value){
            if(empty($result[$value['openid']]) || $result[$value['openid']] == ''){
                $result[$value['openid']]= $value;
                $result[$value['openid']]['list']=array();
                array_push($result[$value['openid']]['list'],array('content'=>$value['content'],'state'=>$value['state']));
            }else{
                array_push($result[$value['openid']]['list'],array('content'=>$value['content'],'state'=>$value['state'])); 
            }
        }
        // var_dump($result);exit;
        foreach ($list as $key => $value) {
            $title=$value['title'];
        }
        // var_dump($title);
        $this->assign('weeklist',$weeklist); 
        $this->assign('result',$result);
        $this->assign('title',$title);
        $this->display('Index/jindu');
    }
    public function addtime(){
        // $value=$_POST['addtime'];
        $week=$_POST['weeks'];
        $year=$_POST['year'];
        $sqlStr="SELECT us.openid,us.name,ts.title,sp.content,sp.uid,sp.tid,us.pic,sp.state FROM speed sp LEFT JOIN taskk ts ON ts.id = sp.tid LEFT JOIN users us ON us.openid=sp.uid WHERE ts.week='{$week}' AND ts.year='{$year}' AND ts.status='2' ORDER BY ts.addtime DESC" ;         
        $list=M()->query($sqlStr);
        // var_dump($sqlStr);exit;
        $result=array();
        foreach($list as $key => $value){
            if(empty($result[$value['openid']]) || $result[$value['openid']] == ''){
                $result[$value['openid']]= $value;
                $result[$value['openid']]['list']=array();
                array_push($result[$value['openid']]['list'],array('content'=>$value['content'],'tid'=>$value['tid'],'state'=>$value['state']));
            }else{
                array_push($result[$value['openid']]['list'],array('content'=>$value['content'],'tid'=>$value['tid'],'state'=>$value['state'])); 
            }
        }
        if($result){
            $this->ajaxReturn(array('status' => 'true', 'info' =>$result), 'JSON');
        }else{
            $this->ajaxReturn(array('status' => 'false', 'info' =>''), 'JSON'); 
        }
        // var_dump($result);exit;
        // $this->ajaxReturn($result);
    }
    public function dotime(){
        $year=$_POST['year'];
        $weeks=$_POST['weeks'];
        if($year==date('Y') && $weeks==date('W')){
            $sta='1';
        }else{
            $sta='2';
        }
        // var_dump($year);
        // var_dump($weeks);exit;
        $row=M()->query("SELECT * FROM taskk WHERE year='{$year}' AND week= '{$weeks}' ORDER BY addtime DESC");
        // var_dump(M()->getlastsql());exit;
        // var_dump($row);exit;
        if($row){
            $this->ajaxReturn(array('status' => 'true', 'info' =>$row,'sta'=>$sta), 'JSON');
        }else{
             $this->ajaxReturn(array('status' => 'false', 'info' =>'获取失败'), 'JSON'); 
        }
        
    }
    public function updata(){
        $tid=$_POST['tid'];
        $result=M('taskk')->where('id='.$tid)->save(array('status'=>'2'));
        // var_dump(M()->getlastsql());exit;
        if($result){
            $this->ajaxReturn(array('status' => 'true', 'info' =>'发布成功'), 'JSON');
        }else{
            $this->ajaxReturn(array('status' => 'false', 'info' =>'发布失败'), 'JSON'); 
        }
    }
    public function postXmlCurl($xml, $useCert = false, $second = 30)
    {   
        // $time=date('Y:m:d H:i:s',time());
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appsecret}";
        $url=file_get_contents($url);
        $url=json_decode($url,true);
        $urll="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$url['access_token']}";
        // var_dump($url);
        // $date=array("touser"=>"",
        //     "template_id"=>'aVXDUPri1RrPm-NhTi7vjCUFerSZmV0i4LKIzIN79p8',
        //     'data'=>array('first'=>array('value'=>'管理员给你发布了一条任务','color'=>'#000000'),'keyword1'=>array('value'=>'哈哈','color'=>'#000000'),'keynote2'=>array('value'=>'哈哈','color'=>'#000000'),'keynote3'=>array('value'=>'{$time}','color'=>'#000000'),'keynote4'=>array('value'=>'{$time}','color'=>'#000000'),'remark'=>array('value'=>'哈哈','color'=>'#000000')));
        // $xml=json_encode($date);
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
