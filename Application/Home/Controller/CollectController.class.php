<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2017/11/25
 * Time: 10:53
 */

namespace Home\Controller;
use Think\Controller;

class CollectController extends BaseController
{
    public function __initialize() {
        parent::_initialize();
        $this->stoken = "rhbnja145862596121";
    }
    public function collect() {
        $memberId = '14950255928594';
        $query = M()->query("SELECT p.ProId,p.ProName,p.Price,p.ProLogoImg FROM  RS_Product p LEFT JOIN RS_MemberCollect m ON p.ProId=m.ProId WHERE MemberId={$memberId}");
        //接收ajax传递过来的数据
        $this->assign('collect',$query);//分配到模板
        $this->display();
    }
    public function cancel() {
        if(IS_POST) {
            $ProId = $_POST['ProId'];
            $del = M()->table("RS_MemberCollect")->where("ProId='".$ProId."'")->delete();
            if($del) {
                $msg['status'] = "success";
            }
        }
        $this->ajaxReturn($msg);

    }











}