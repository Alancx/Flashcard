<?php
namespace Adminmobile\Controller;
use Think\Controller;
class OrdersController extends CommonController {
  public function orders(){
    $this->assign('Title','订单管理');
    $this->display();
  }

public function getmoreorder_1(){
  $page=$_POST['page'];
  $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber, a.OrderId,
       CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,
       case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Freight,b.MemberId,b.MemberName,a.SceneMember from RS_Order a
      LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='1' AND  a.Count>0 AND
      a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
  $data=$this->BM()->query($sqlju);
  foreach ($data as $key => $value) {
    $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Count,b.ProLogoImg,b.ProName,b.ProTitle,a.Spec
    FROM RS_OrderList a LEFT JOIN RS_Product b ON a.ProId=b.ProId
    where a.OrderId='".$value['OrderId']."'";
    $oderpro=$this->BM()->query($sqlju);
    $value['prolist']=$oderpro;
    $dataorder[$key]=$value;
  }
  if($dataorder){
    $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
  } else{
    $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
  }
}
public function getmoreorder_2(){
  $page=$_POST['page'];
  $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber, a.OrderId,
       CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,
       case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Freight,b.MemberId,b.MemberName,a.SceneMember from RS_Order a
      LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='2' AND  a.Count>0 AND
      a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
  $data=$this->BM()->query($sqlju);
  foreach ($data as $key => $value) {
    $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Count,b.ProLogoImg,b.ProName,b.ProTitle,a.Spec
    FROM RS_OrderList a LEFT JOIN RS_Product b ON a.ProId=b.ProId
    where a.OrderId='".$value['OrderId']."'";
    $oderpro=$this->BM()->query($sqlju);
    $value['prolist']=$oderpro;
    $dataorder[$key]=$value;
  }
  if($dataorder){
    $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
  } else{
    $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
  }
}
public function getmoreorder_3(){
  $page=$_POST['page'];
  $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber, a.OrderId,
       CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,
       case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Freight,b.MemberId,b.MemberName,a.SceneMember from RS_Order a
      LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='3' AND  a.Count>0 AND
      a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
  $data=$this->BM()->query($sqlju);
  foreach ($data as $key => $value) {
    $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Count,b.ProLogoImg,b.ProName,b.ProTitle,a.Spec
    FROM RS_OrderList a LEFT JOIN RS_Product b ON a.ProId=b.ProId
    where a.OrderId='".$value['OrderId']."'";
    $oderpro=$this->BM()->query($sqlju);
    $value['prolist']=$oderpro;
    $dataorder[$key]=$value;
  }
  if($dataorder){
    $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
  } else{
    $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
  }
}
public function getmoreorder_4(){
  $page=$_POST['page'];
  $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber, a.OrderId,
       CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,
       case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Freight,b.MemberId,b.MemberName,a.SceneMember from RS_Order a
      LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='2' AND a.RecevingPost='ZT' AND  a.Count>0 AND
      a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
  $data=$this->BM()->query($sqlju);
  foreach ($data as $key => $value) {
    $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Count,b.ProLogoImg,b.ProName,b.ProTitle,a.Spec
    FROM RS_OrderList a LEFT JOIN RS_Product b ON a.ProId=b.ProId
    where a.OrderId='".$value['OrderId']."'";
    $oderpro=$this->BM()->query($sqlju);
    $value['prolist']=$oderpro;
    $dataorder[$key]=$value;
  }
  if($dataorder){
    $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
  } else{
    $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
  }
}
public function getmoreorder_5(){
  $page=$_POST['page'];
  $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber, a.OrderId,
       CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,
       case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Freight,b.MemberId,b.MemberName,a.SceneMember from RS_Order a
      LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='5' AND  a.Count>0 AND
      a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
  $data=$this->BM()->query($sqlju);
  foreach ($data as $key => $value) {
    $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Count,b.ProLogoImg,b.ProName,b.ProTitle,a.Spec
    FROM RS_OrderList a LEFT JOIN RS_Product b ON a.ProId=b.ProId
    where a.OrderId='".$value['OrderId']."'";
    $oderpro=$this->BM()->query($sqlju);
    $value['prolist']=$oderpro;
    $dataorder[$key]=$value;
  }
  if($dataorder){
    $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
  } else{
    $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
  }
}
public function getmoreorder_6(){
  $page=$_POST['page'];
  $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber, a.OrderId,
       CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,
       case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Freight,b.MemberId,b.MemberName,a.SceneMember from RS_Order a
      LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='4' AND  a.Count>0 AND
      a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
  $data=$this->BM()->query($sqlju);
  foreach ($data as $key => $value) {
    $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Count,b.ProLogoImg,b.ProName,b.ProTitle,a.Spec
    FROM RS_OrderList a LEFT JOIN RS_Product b ON a.ProId=b.ProId
    where a.OrderId='".$value['OrderId']."'";
    $oderpro=$this->BM()->query($sqlju);
    $value['prolist']=$oderpro;
    $dataorder[$key]=$value;
  }
  if($dataorder){
    $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
  } else{
    $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
  }
}
public function getmoreorder_7(){
  $page=$_POST['page'];
  $sqlju=" SELECT top 20 * FROM (SELECT ROW_NUMBER() OVER (ORDER BY a.CreateDate DESC) AS RowNumber, a.OrderId,
       CONVERT(varchar(100), a.CreateDate, 120) AS oDate,a.stoken,a.Count,
       case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
      a.Freight,b.MemberId,b.MemberName,a.SceneMember from RS_Order a
      LEFT JOIN RS_Member b ON a.MemberId=b.MemberId  WHERE a.status='10' AND  a.Count>0 AND
      a.token='".$this->token."' AND a.stoken='".$this->stoken."') c WHERE RowNumber>(10*".$page.")";
  $data=$this->BM()->query($sqlju);
  foreach ($data as $key => $value) {
    $sqlju="SELECT case when left(a.Price,1)='.' then '0'+cast(a.Price as varchar) else cast(a.Price as varchar) end as Price,
    a.Count,b.ProLogoImg,b.ProName,b.ProTitle,a.Spec
    FROM RS_OrderList a LEFT JOIN RS_Product b ON a.ProId=b.ProId
    where a.OrderId='".$value['OrderId']."'";
    $oderpro=$this->BM()->query($sqlju);
    $value['prolist']=$oderpro;
    $dataorder[$key]=$value;
  }
  if($dataorder){
    $this->ajaxReturn(array('status' => 'true', 'info' => $dataorder), 'JSON');
  } else{
    $this->ajaxReturn(array('status' => 'flase', 'info' => 'error'), 'JSON');
  }
}





}?>
