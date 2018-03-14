<?php
namespace SellermobileApp\Controller;
use Think\Controller;

class ProductsController extends BaseController{

  ///////平台商品列表//////
  public function Factorypro($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      if ($info['ltype']=='refresh') {
        $sqlStr="SELECT * FROM (SELECT ROW_NUMBER() OVER (ORDER BY ID) AS RowNumber, * FROM RS_Product WHERE IsShelves=1 AND token='".$shopinfo['Token']."' AND stoken='0' AND ProId NOT IN (SELECT ProId FROM RS_MerPros WHERE token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."')) factpro WHERE (RowNumber BETWEEN 1 AND 10) ";
      } elseif ($info['ltype']=='getmore') {
        $page=$info['page'];
        $sqlStr="SELECT * FROM (SELECT ROW_NUMBER() OVER (ORDER BY ID) AS RowNumber, * FROM RS_Product WHERE IsShelves=1 AND token='".$shopinfo['Token']."' AND stoken='0' AND ProId NOT IN (SELECT ProId FROM RS_MerPros WHERE token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."')) factpro WHERE (RowNumber BETWEEN ".($page*10+1)." AND ".(($page+1)*10).") ";
      } elseif ($info['ltype']=='searchfacpro') {
        $sqlStr="SELECT * FROM RS_Product WHERE IsShelves=1 AND token='".$shopinfo['Token']."' AND stoken='0' AND ProId NOT IN (SELECT ProId FROM RS_MerPros WHERE token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."') AND ProName LIKE '%".$info['stext']."%' ";
      }
      $prodata=$this->BM()->query($sqlStr);
      foreach ($prodata as $key => $value) {
        $sqlStr="SELECT * FROM RS_ProductList WHERE IsDelete=0 AND token='".$shopinfo['Token']."' AND stoken='0' AND ProId='".$value['ProId']."'";
        $proattr=$this->BM()->query($sqlStr);
        $prodata[$key]['attrlist']=$proattr;
      }
      if ($prodata) {
        $this->ajaxReturn(array('status' => 'true', 'info' => $prodata), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'NullError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ///////批量保存选择的平台商品/////////////////////////
  public function Factoryprosave($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $prodata=$info['prolist'];
      $prolist=json_decode($prodata,true);
      $nowDate=date('Y-m-d H:i:s',time());
      $res=true;
      $red=true;
      $wh= 'wh'.substr($shopinfo['Token'], -8,8);//////主仓库名称
      $uinfo=$this->UM('user')->where(array('stoken'=>$shopinfo['Stoken'],'token'=>$shopinfo['Token'],'id'=>$shopinfo['UserId']))->find();
      $sinfo=$this->BM('store')->where(array('token'=>$shopinfo['Token'],'stoken'=>$shopinfo['Stoken']))->find();
      ////////订货申请单主表信息//////
      $dataInWarehouse['InWarehouseId']='RK'.date('ymdHis',time()).time();
      $dataInWarehouse['InWarehouseNumber']='';
      $dataInWarehouse['Count']=0;
      $dataInWarehouse['Money']=0.00;
      $dataInWarehouse['Status']=0;
      $dataInWarehouse['Date']=$nowDate;
      $dataInWarehouse['InputId']=$uinfo['id'];
      $dataInWarehouse['InputName']=$uinfo['TrueName'];
      $dataInWarehouse['HandleId']='';
      $dataInWarehouse['HandleName']='';
      $dataInWarehouse['Type']='4';
      $dataInWarehouse['Remarks']='';
      $dataInWarehouse['InStorehouseId']=$wh.'_'.$sinfo['id'];
      $dataInWarehouse['InStorehouseName']=$sinfo['storename'];
      $dataInWarehouse['CreateDate']=$nowDate;
      $dataInWarehouse['LastUpdateDate']=$nowDate;
      $dataInWarehouse['token']=$shopinfo['Token'];
      $dataInWarehouse['stoken']=$shopinfo['Stoken'];
      $dataInWarehouse['IsPay']='0';
      $this->BM()->startTrans();
      foreach ($prolist as $proitem) {
        ///////添加选卖商品/////////
        $savedata['ProId']=$proitem['pid'];
        $savedata['ProIdCard']=$proitem['pcid'];
        $savedata['token']=$shopinfo['Token'];
        $savedata['stoken']=$shopinfo['Stoken'];
        $savedata['Price']=$proitem['price'];
        if(!$this->BM('merpros')->add($savedata)){
          $res=false;
          break;
        }
        ////////订货申请单字表////////
        if ($prolist['num']!='0') {
          $dataInWarehouseList['InWarehouseId']=$dataInWarehouse['InWarehouseId'];
          $dataInWarehouseList['ProId']=$proitem['pid'];
          $dataInWarehouseList['ProIdCard']=$proitem['pcid'];
          $dataInWarehouseList['ClassId']=$proitem['cid'];
          $dataInWarehouseList['Price']=$proitem['cprice'];
          $dataInWarehouseList['Count']=$proitem['num'];
          $dataInWarehouseList['Money']=$proitem['cprice']*$proitem['num'];
          $dataInWarehouseList['IsMark']=0;
          $dataInWarehouseList['Remarks']="";
          $dataInWarehouseList['Supplier']="";
          $dataInWarehouseList['CreateDate']=$nowDate;
          $dataInWarehouseList['LastUpdateDate']=$nowDate;
          $dataInWarehouseList['token']=$shopinfo['Token'];

          $dataInWarehouse['Count']+=$proitem['num'];
          $dataInWarehouse['Money']+= $dataInWarehouseList['Money'];
          if(!$this->BM('productinwarehouselist')->add($dataInWarehouseList)){
            $red=false;
            break;
          }
        }
      }
      if ($red) {
        if ($dataInWarehouse['Count']>0) {
          $red=$this->BM('productinwarehouse')->add($dataInWarehouse);
        }
      }

      if ($res && $red) {
        $this->BM()->commit();
        $this->ajaxReturn(array('status' => 'true', 'datainfo' => 'success'), 'JSON');
      } else {
        $this->BM()->rollback();
        $this->ajaxReturn(array('status' => 'false', 'datainfo' => 'SaveError'), 'JSON');
      }



      $this->ajaxReturn(array('status' => 'true', 'info' => 'true'), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  //////获得分类信息//////
  public function GetClassInfo(){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $sqlStr="SELECT ClassId,ClassName,ParentClassId, ClassGrade,ClassSort FROM RS_ProductClass WHERE IsVisible=1
      AND token='".$shopinfo['Token']."'";
      $classdata=$this->BM()->query($sqlStr);
      if ($classdata) {
        $this->ajaxReturn(array('status' => 'true', 'info' => $classdata), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'classError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ///////保存自营商品信息///////////
  public function Selfprosave($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $prodatainfos=json_decode($info['prodata'],true);
      $wh= 'tb_wh'.substr($shopinfo['Token'], -8,8);//主仓库表名
      $dwh= 'tb_wh'.substr($shopinfo['Token'], -8,8).'_'.$shopinfo['StoreId'];//当前仓库库表名
      $protype=$prodatainfos['Ptype'];
      $Pdata['ProLogoImg']=$prodatainfos['ProLogoImg'];
      $Pdata['ProName']=htmlspecialchars($prodatainfos['ProName']);
      $Pdata['ProShowImg']=json_decode($prodatainfos['ProShowImg'],true);
      $Pdata['ClassType']=$prodatainfos['ClassType'];
      $Pdata['ClassName']=$prodatainfos['ClassName'];
      $Pdata['ProAttrList']=json_decode($prodatainfos['ProAttrList'],true);
      $Pdata['ProTitle']=htmlspecialchars($prodatainfos['ProTitle']);
      $Pdata['ProSubtitle']=htmlspecialchars($prodatainfos['ProSubtitle']);
      $Pdata['PriceRange']=$prodatainfos['PriceRange'];
      $Pdata['ProNumber']=trim($prodatainfos['ProNumber']);
      $Pdata['Barcode']=trim($prodatainfos['Barcode']);
      $Pdata['Weight']=$prodatainfos['Weight'];
      $Pdata['Remarks']=htmlspecialchars($prodatainfos['Remarks']);
      $Pdata['KeyWord']=htmlspecialchars($prodatainfos['KeyWord']);
      $Pdata['EmpCut']=$prodatainfos['EmpCut'];
      $Pdata['Cut']=$prodatainfos['Cut'];
      $Pdata['ExtendCut']=$prodatainfos['ExtendCut'];
      $Pdata['IsUseConpon']=$prodatainfos['IsUseConpon'];
      $Pdata['Iszp']=$prodatainfos['Iszp'];
      $Pdata['IsUseScore']=$prodatainfos['IsUseScore'];
      $Pdata['Score']=$prodatainfos['Score'];
      $delpraoattr=json_decode($prodatainfos['Delproattr'],true);
      if($protype=='add'){
        $ProId="pro".substr(time(), 3).mt_rand(0,9).mt_rand(0,9).mt_rand(0,9);
        //////主表数据//////////
        $prodata['ProId']=$ProId;
        $prodata['ProNumber']=$Pdata['ProNumber'];
        $prodata['ProName']=$Pdata['ProName'];
        $prodata['ProTitle']=$Pdata['ProTitle'];
        $prodata['ProSubtitle']=$Pdata['ProSubtitle'];
        $prodata['PriceRange']=$Pdata['PriceRange'];
        $prodata['IsShelves']=1;
        $prodata['Remarks']=$Pdata['Remarks'];
        $prodata['KeyWord']=$Pdata['KeyWord'];
        $prodata['ClassType']=$Pdata['ClassType'];
        $prodata['ClassName']=$Pdata['ClassName'];
        $prodata['SalesCount']=0;
        $prodata['AttributeCount']=0;
        $prodata['BrowseCount']=0;
        $prodata['ProLogoImg']=$Pdata['ProLogoImg'];
        $prodata['Cut']=$Pdata['Cut'];
        $prodata['Cut2']=0;
        $prodata['Cut3']=0;
        $prodata['IsUseConpon']=$Pdata['IsUseConpon'];
        $prodata['Barcode']=$Pdata['Barcode'];
        $prodata['Weight']=$Pdata['Weight'];
        $prodata['EmpCut']=$Pdata['EmpCut'];
        $prodata['token']=$shopinfo['Token'];
        $prodata['Iszp']=$Pdata['Iszp'];
        $prodata['IsUseScore']=$Pdata['IsUseScore'];
        $prodata['Score']=$Pdata['Score'];
        $prodata['ExtendCut']=$Pdata['ExtendCut'];
        $prodata['stoken']=$shopinfo['Stoken'];

        ///////新品数据//////////
        $newprodata['ProId']=$ProId;
        $newprodata['ProLabel']='2';
        $newprodata['LabelType']=0;
        $newprodata['token']=$shopinfo['Token'];
        $newprodata['stoken']=$shopinfo['Stoken'];

        //////end主表数据///////////////
        //////子表数据 和 仓库数据/////////////////
        foreach ($Pdata['ProAttrList'] as $key => $value) {
          $proldata['ProId']=$ProId;
          $proldata['ProIdInputCard']=$value['code'];
          $proldata['ProIdCard']=$ProId.'_0'.($key+1);
          $proldata['ProSpec1']=$value['attr'];
          $proldata['Price']=$value['price'];
          $proldata['EmpCut']=$Pdata['EmpCut'];
          $proldata['token']=$shopinfo['Toekn'];
          $proldata['Iszp']=$Pdata['Iszp'];
          $proldata['IsUseScore']=$Pdata['IsUseScore'];
          $proldata['Score']=$Pdata['Score'];
          $proldata['stoken']=$shopinfo['Stoekn'];
          $proldata['Count']=$value['count'];
          $proldata['InputCode']=$value['inputcode'];
          $proldatas[$proldata['ProIdCard']]=$proldata;
          //////////////////////////////
          $ckdata['ProId']=$ProId;
          $ckdata['ProIdCard']=$ProId.'_0'.($key+1);
          $ckdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
          $ckdata['StockCount']=0;
          $ckdata['LimitCount']=0;
          $ckdata['VirtualCount']=0;
          $ckdata['InCount']=0;
          $ckdata['SalesCount']=0;
          $ckdata['OutCount']=0;
          $ckdata['ReturnCount']=0;
          $ckdata['IsDelete']=0;
          $ckdata['CreateDate']=date('Y-m-d H:i:s',time());
          $cdata[$key]=$ckdata;
          /////////当前仓库数据///////////
          $dckdata['ProId']=$ProId;
          $dckdata['ProIdCard']=$ProId.'_0'.($key+1);
          $dckdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
          $dckdata['StockCount']=$value['count'];
          $dckdata['LimitCount']=0;
          $dckdata['VirtualCount']=$value['count'];
          $dckdata['InCount']=$value['count'];
          $dckdata['SalesCount']=0;
          $dckdata['OutCount']=0;
          $dckdata['ReturnCount']=0;
          $dckdata['IsDelete']=0;
          $dckdata['CreateDate']=date('Y-m-d H:i:s',time());
          $dcdata[$key]=$dckdata;
        }

        //////end子表数据 和 仓库数据//////////////
        //////展示图片/////////////////
        foreach ($Pdata['ProShowImg'] as $key => $img) {
          $imgdata[$key]=$img;
        }
        //////end展示图片/////////////////
        ////////json数据//////////////////
        $json=array();
        $json=$prodata;
        $json['ProductList']=$proldatas;
        $json['imgs']=$imgdata;

        ////////endjson数据//////////////////
        ///////获得仓库表名/////////////////
        $whlist=$this->WM()->query("SELECT name FROM sysobjects where xtype='u' and name like '%".$wh."%'");

        ///////end获得仓库表名/////////////////
        $this->BM()->startTrans();
        $this->WNM()->startTrans();
        $prores=true;
        $prores=$this->BM('Product')->add($prodata);

        $prolres=true;
        $newpro=$this->BM('productlabellist')->add($newprodata);
        foreach ($proldatas as $value) {
          if (!$this->BM('Productlist')->add($value)) {
            $prolres=false;
            break;
          }
        }
        $whprores=true;
        foreach ($whlist as $whv) {
          if(!$whprores){
            break;
          }
          if ($whv['name']==$dwh) {
            foreach ($dcdata as $dcv) {
              if(!$this->WNM($dwh)->add($dcv)){
                $whprores=false;
                break;
              }
            }
          } else {
            foreach ($cdata as $cv) {
              if(!$this->WNM($whv['name'])->add($cv)){
                $whprores=false;
                break;
              }
            }
          }
        }

        if($prores && $prolres && $whprores && $newpro){
          file_put_contents($this->REALPATH.C('STATICPATH').$ProId.'.json', json_encode($json));//生成json数据文件
          $this->BM()->commit();
          $this->WNM()->commit();
          $this->ajaxReturn(array('status' => 'true', 'info' => 'success'), 'JSON');
        } else{
          $this->BM()->rollback();
          $this->WNM()->rollback();
          $this->ajaxReturn(array('status' => 'false', 'info' => 'SaveError'), 'JSON');
        }
      } else {
        $ProId=$protype;
        $proinfo=json_decode(file_get_contents($this->REALPATH.C('STATICPATH').$ProId.'.json'),true);
        //////主表数据//////////
        $prodata['ProId']=$ProId;
        $prodata['ProNumber']=$Pdata['ProNumber'];
        $prodata['ProName']=$Pdata['ProName'];
        $prodata['ProTitle']=$Pdata['ProTitle'];
        $prodata['ProSubtitle']=$Pdata['ProSubtitle'];
        $prodata['PriceRange']=$Pdata['PriceRange'];
        $prodata['Remarks']=$Pdata['Remarks'];
        $prodata['KeyWord']=$Pdata['KeyWord'];
        $prodata['ClassType']=$Pdata['ClassType'];
        $prodata['ClassName']=$Pdata['ClassName'];
        $prodata['ProLogoImg']=$Pdata['ProLogoImg'];
        $prodata['Cut']=$Pdata['Cut'];
        $prodata['IsUseConpon']=$Pdata['IsUseConpon'];
        $prodata['Barcode']=$Pdata['Barcode'];
        $prodata['Weight']=$Pdata['Weight'];
        $prodata['EmpCut']=$Pdata['EmpCut'];
        $prodata['Iszp']=$Pdata['Iszp'];
        $prodata['IsUseScore']=$Pdata['IsUseScore'];
        $prodata['Score']=$Pdata['Score'];
        $prodata['ExtendCut']=$Pdata['ExtendCut'];
        $prodata['LastUpdateDate']=date('Y-m-d H:i:s',time());
        $prodata['token']=$shopinfo['Token'];
        $prodata['stoken']=$shopinfo['Stoken'];
        //////end主表数据///////////////

        $proattcount=$this->BM('Productlist')->
        where("ProId='%s' and token='%s' and stoken='%s' ",array($ProId,$shopinfo['Token'],$shopinfo['Stoken']))->field('ProIdCard')->Count();
        //////子表修改和新增数据 和 仓库/////////////////
        foreach ($Pdata['ProAttrList'] as $key => $value) {
          if ($value['ptype']=='add') {
            $proattcount=$proattcount+1;
            $proldata['ProId']=$ProId;
            $proldata['ProIdInputCard']=$value['code'];
            $proldata['ProIdCard']=$ProId.'_0'.$proattcount;
            $proldata['ProSpec1']=$value['attr'];
            $proldata['Price']=$value['price'];
            $proldata['EmpCut']=$Pdata['EmpCut'];
            $proldata['token']=$shopinfo['Token'];
            $proldata['Iszp']=$Pdata['Iszp'];
            $proldata['IsUseScore']=$Pdata['IsUseScore'];
            $proldata['Score']=$Pdata['Score'];
            $proldata['stoken']=$shopinfo['Stoken'];
            $proldata['Count']=$value['count'];
            $proldata['InputCode']=$value['inputcode'];
            $proldatas[$proldata['ProIdCard']]=$proldata;///新增字表数据
            /////
            //////////////////////////////
            $ckdata['ProId']=$ProId;
            $ckdata['ProIdCard']=$ProId.'_0'.$proattcount;
            $ckdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
            $ckdata['StockCount']=0;
            $ckdata['LimitCount']=0;
            $ckdata['VirtualCount']=0;
            $ckdata['InCount']=0;
            $ckdata['SalesCount']=0;
            $ckdata['OutCount']=0;
            $ckdata['ReturnCount']=0;
            $ckdata['IsDelete']=0;
            $ckdata['CreateDate']=date('Y-m-d H:i:s',time());
            $cdata[]=$ckdata;
            /////////当前仓库数据///////////
            $dckdata['ProId']=$ProId;
            $dckdata['ProIdCard']=$ProId.'_0'.$proattcount;
            $dckdata['LastUpdateDate']=date('Y-m-d H:i:s',time());
            $dckdata['StockCount']=$value['count'];
            $dckdata['LimitCount']=0;
            $dckdata['VirtualCount']=$value['count'];
            $dckdata['InCount']=$value['count'];
            $dckdata['SalesCount']=0;
            $dckdata['OutCount']=0;
            $dckdata['ReturnCount']=0;
            $dckdata['IsDelete']=0;
            $dckdata['CreateDate']=date('Y-m-d H:i:s',time());
            $dcdata[]=$dckdata;
          } else {
            $proldatax['ProId']=$ProId;
            $proldatax['ProIdInputCard']=$value['code'];
            $proldatax['ProIdCard']=$value['ptype'];
            $proldatax['ProSpec1']=$value['attr'];
            $proldatax['Price']=$value['price'];
            $proldatax['EmpCut']=$Pdata['EmpCut'];
            $proldatax['token']=$shopinfo['Token'];
            $proldatax['Iszp']=$Pdata['Iszp'];
            $proldatax['IsUseScore']=$Pdata['IsUseScore'];
            $proldatax['Score']=$Pdata['Score'];
            $proldatax['stoken']=$shopinfo['Stoken'];
            $proldatax['Count']=$value['count'];
            $proldatax['InputCode']=$value['inputcode'];
            $proldatax['LastUpdateDate']=date('Y-m-d H:i:s',time());
            $proldatau[$proldatax['ProIdCard']]=$proldatax;///修改字表数据
          }
        }
        //////end子表数据 和 仓库//////////////
        //需要删除的子表和仓库信息///////////////
        foreach ($delpraoattr as $key => $value) {
          $proldatad[]=$value;///删除的子表信息
          $cdatad[]=$value;//需要删除的仓库信息
        }
        ///end需要删除的子表和仓库信息///////////////
        //////展示图片/////////////////
        foreach ($Pdata['ProShowImg'] as $key => $img) {
          $imgdata[]=$img;
        }
        //////end展示图片/////////////////
        ////////json数据//////////////////
        $json=array();
        $json=$prodata;
        if($proldatau==null){
          $json['ProductList']=$proldatas;
        } else if ($proldatas==null) {
          $json['ProductList']=$proldatau;
        } else{
          $json['ProductList']=array_merge($proldatau,$proldatas);

        }
        $json['ProContent']=$proinfo['ProContent'];
        $json['imgs']=$imgdata;
        ////////endjson数据//////////////////
        ///////获得仓库表名/////////////////
        $whlist=$this->WM()->query("SELECT name FROM sysobjects where xtype='u' and name like '%".$wh."%'");
        ///////end获得仓库表名/////////////////
        $this->BM()->startTrans();
        $this->WNM()->startTrans();
        $prores=$this->BM('Product')->where("ProId='%s'",$prodata['ProId'])->save($prodata);
        $prolres=true;
        foreach ($proldatas as $value) {

          if (!$this->BM('Productlist')->add($value)) {
            $prolres=false;
            break;
          }
        }
        $prolures=true;
        foreach ($proldatau as $value) {
          if (!$this->BM('Productlist')->where("ProIdCard='%s'",$value['ProIdCard'])->save($value)) {
            $prolures=false;
            break;
          }
        }
        $proldres=true;
        foreach ($proldatad as $value) {
          if (!$this->BM('Productlist')->where("ProIdCard='%s'",$value)->save(array('IsDelete'=>1))) {
            $proldres=false;
            break;
          }
        }
        $whprores=true;
        foreach ($whlist as $whv) {
          if(!$whprores){
            break;
          }
          if ($whv['name']==$dwh) {
            foreach ($dcdata as $dcv) {
              if(!$this->WNM($dwh)->add($dcv)){
                $whprores=false;
                break;
              }
            }
          } else {
            foreach ($cdata as $cv) {
              if(!$this->WNM($whv['name'])->add($cv)){
                $whprores=false;
                break;
              }
            }
          }
        }

        $whdprores=true;
        foreach ($whlist as $whv) {
          if(!$whdprores){
            break;
          }
          foreach ($cdatad as $cv) {
            $delete['IsDelete']=1;
            if(!$this->WNM($whv['name'])->where("ProIdCard='%s'",$cv)->save($delete)){
              $whdprores=false;
              break;
            }
          }
        }
        if($prolres && $prolures && $proldres && $whprores && $whdprores){
          file_put_contents($this->REALPATH.C('STATICPATH').$ProId.'.json', json_encode($json));//生成json数据文件
          $this->BM()->commit();
          $this->WNM()->commit();
          $this->ajaxReturn(array('status' => 'true', 'info' => 'success'), 'JSON');
        } else{
          $this->BM()->rollback();
          $this->WNM()->rollback();
          $this->ajaxReturn(array('status' => 'false', 'info' => 'xgError'), 'JSON');
        }
      }


    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  //////////////选买的工厂商品//////////////////////
  public function HadFactorypro($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      if ($info['ltype']=='refresh') {
        $sqlStr="SELECT * FROM (SELECT ROW_NUMBER() OVER (ORDER BY p.ProId) AS RowNumber,p.ProId,p.ProTitle,p.ProName,p.ClassType,CAST(p.ProLogoImg AS varchar(MAX)) AS ProLogoImg, CONVERT(VARCHAR(20),CONVERT(DECIMAL(18,2),MIN(mp.Price)))  AS Price FROM RS_MerPros mp LEFT JOIN RS_Product p ON mp.ProId = p.ProId WHERE mp.token='".$shopinfo['Token']."' AND mp.stoken='".$shopinfo['Stoken']."' GROUP BY p.ProId,p.ProTitle,p.ProName,p.ClassType,CAST(p.ProLogoImg AS varchar(MAX))) t WHERE (RowNumber BETWEEN 1 AND 10) ";
      } elseif ($info['ltype']=='getmore') {
        $page=$info['page'];
        $sqlStr="SELECT * FROM (SELECT ROW_NUMBER() OVER (ORDER BY p.ProId) AS RowNumber,p.ProId,p.ProTitle,p.ProName,p.ClassType,CAST(p.ProLogoImg AS varchar(MAX)) AS ProLogoImg, CONVERT(VARCHAR(20),CONVERT(DECIMAL(18,2),MIN(mp.Price)))  AS Price FROM RS_MerPros mp LEFT JOIN RS_Product p ON mp.ProId = p.ProId WHERE mp.token='".$shopinfo['Token']."' AND mp.stoken='".$shopinfo['Stoken']."' GROUP BY p.ProId,p.ProTitle,p.ProName,p.ClassType,CAST(p.ProLogoImg AS varchar(MAX))) t WHERE (RowNumber BETWEEN ".($page*10+1)." AND ".(($page+1)*10).") ";
      }
      $prodata=$this->BM()->query($sqlStr);
      foreach ($prodata as $key => $value) {
        // $sqlStr="SELECT * FROM RS_ProductList WHERE IsDelete=0 AND token='".$shopinfo['Token']."' AND stoken='0' AND ProId='".$value['ProId']."'";
        $sqlStr="SELECT pl.ProId,pl.ProSpec1,pl.ProIdCard,pl.CosPrice,mp.Price FROM RS_ProductList pl LEFT JOIN RS_MerPros mp ON pl.ProId=mp.ProId AND pl.ProIdCard = mp.ProIdCard WHERE pl.ProId='".$value['ProId']."' AND pl.IsDelete=0 AND mp.token='".$shopinfo['Token']."' AND mp.stoken='".$shopinfo['Stoken']."' ";
        $proattr=$this->BM()->query($sqlStr);
        $prodata[$key]['attrlist']=$proattr;
      }
      if ($prodata) {
        $this->ajaxReturn(array('status' => 'true', 'info' => $prodata), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'NullError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ////////////自营商品///////////
  public function GetSelfProinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $sqlStr="SELECT * FROM RS_Product  WHERE stoken='".$shopinfo['Stoken']."' AND token='".$shopinfo['Token']."' ORDER BY CreateDate;";
      $selfpro=$this->BM()->query($sqlStr);
      foreach ($selfpro as $key => $value) {
        $sqlStr="SELECT * FROM RS_ProductList WHERE IsDelete=0 AND token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' AND ProId='".$value['ProId']."'";
        $proattr=$this->BM()->query($sqlStr);
        $selfpro[$key]['attrlist']=$proattr;
      }
      $this->ajaxReturn(array('status' => 'true', 'info' => $selfpro), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ///////所有商品信息 /////////
  public function GetAllClassProinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $sqlStr="SELECT p.ProId,(CASE WHEN p.stoken='0' THEN mp.Price ELSE p.PriceRange END) AS PriceRange,(CASE WHEN p.stoken='0' THEN '1' ELSE '2' END) AS ptype,p.IsShelves,p.ProLogoImg,p.ClassName,p.ClassType,p.ProName,p.ProTitle FROM RS_Product p LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros WHERE token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' GROUP BY ProId,token,stoken) mp ON p.ProId = mp.ProId WHERE p.token='".$shopinfo['Token']."' AND (p.stoken='".$shopinfo['Stoken']."' OR mp.stoken='".$shopinfo['Stoken']."')";
      $allpro=$this->BM()->query($sqlStr);
      $this->ajaxReturn(array('status' => 'true', 'info' => $allpro), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  /////////搜索本店所有商品////////
  public function SearchallProinfo($info) {
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $searchtext = $info['stext'];
      $sqlStr="SELECT p.ProId,(CASE WHEN p.stoken='0' THEN mp.Price ELSE p.PriceRange END) AS PriceRange,(CASE WHEN p.stoken='0' THEN '1' ELSE '2' END) AS ptype,p.IsShelves,p.ProLogoImg,p.ClassName,p.ClassType,p.ProName,p.ProTitle FROM RS_Product p LEFT JOIN (SELECT ProId,MIN(Price) AS Price,token,stoken FROM RS_MerPros WHERE token='".$shopinfo['Token']."' AND stoken='".$shopinfo['Stoken']."' GROUP BY ProId,token,stoken) mp ON p.ProId = mp.ProId WHERE p.token='".$shopinfo['Token']."' AND (p.stoken='".$shopinfo['Stoken']."' OR mp.stoken='".$shopinfo['Stoken']."') AND p.ProName LIKE '%".$searchtext."%' ";

      $allpro=$this->BM()->query($sqlStr);

      if ($allpro) {
        $this->ajaxReturn(array('status' => 'true', 'info' => $allpro), 'JSON');
      } else {
        $this->ajaxReturn(array('status' => 'false', 'info' => 'NullError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  ////////////////删除单个商品信息//////////////////
  public function Deletepro($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $wh= 'tb_wh'.substr($shopinfo['Token'], -8,8);//主仓库表名
      $prolist=json_decode($info['prolist'],true);
      $type=$info['ltype'];
      if ($type=='facepro') {
        $dres=true;
        $this->BM()->startTrans();
        foreach ($prolist as $ProId) {
          if(!$this->BM('Merpros')->where(array('ProId'=>$ProId,'token'=>$shopinfo['Token'],'stoken'=>$shopinfo['Stoken']))->delete()){
            $dres=false;
            break;
          }
        }
        if ($dres) {
          $this->BM()->commit();
          $this->ajaxReturn(array('status' => 'true', 'info' => $prolist), 'JSON');
        } else {
          $this->BM()->rollback();
          $this->ajaxReturn(array('status' => 'false', 'info' => 'delproError'), 'JSON');
        }
      } else {
        $whlist=$this->WM()->query("SELECT name FROM sysobjects where xtype='u' and name like '%".$wh."%'");
        $delres=true;
        $this->BM()->startTrans();
        $this->WNM()->startTrans();
        foreach ($prolist as $ProId) {
          $pres=$this->BM('Product')->where("ProId='%s'",$ProId)->delete();
          $plres=$this->BM('Productlist')->where("ProId='%s'",$ProId)->delete();
          $whdprores=true;
          foreach ($whlist as $whv) {
            $delete['IsDelete']=1;
            if($this->WNM($whv['name'])->where("ProId='%s'",$ProId)->save($delete)===false){
              $whdprores=false;
              break;
            }
          }

          if($pres==false || $plres==false || $whdprores==false){
            $delres=false;
            break;
          }
        }
        if($delres){
          $this->BM()->commit();
          $this->WNM()->commit();
          $this->ajaxReturn(array('status' => 'true', 'info' => $prolist), 'JSON');
        } else{
          $this->BM()->rollback();
          $this->WNM()->rollback();
          $this->ajaxReturn(array('status' => 'false', 'info' => 'delproError'), 'JSON');
        }
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }

  ///////批量保存编辑修改的平台商品/////////////////////////
  public function Factoryproeditsave($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $prodata=$info['prolist'];
      $this->LOGS('---数据:'.$prodata);
      $prolist=json_decode($prodata,true);
      $nowDate=date('Y-m-d H:i:s',time());
      $res=true;
      $red=true;
      $wh= 'wh'.substr($shopinfo['Token'], -8,8);//////主仓库名称
      $uinfo=$this->UM('user')->where(array('stoken'=>$shopinfo['Stoken'],'token'=>$shopinfo['Token'],'id'=>$shopinfo['UserId']))->find();
      $sinfo=$this->BM('store')->where(array('token'=>$shopinfo['Token'],'stoken'=>$shopinfo['Stoken']))->find();

      ////////订货申请单主表信息//////
      $dataInWarehouse['InWarehouseId']='RK'.date('ymdHis',time()).time();
      $dataInWarehouse['InWarehouseNumber']='';
      $dataInWarehouse['Count']=0;
      $dataInWarehouse['Money']=0.00;
      $dataInWarehouse['Status']=0;
      $dataInWarehouse['Date']=$nowDate;
      $dataInWarehouse['InputId']=$uinfo['id'];
      $dataInWarehouse['InputName']=$uinfo['TrueName'];
      $dataInWarehouse['HandleId']='';
      $dataInWarehouse['HandleName']='';
      $dataInWarehouse['Type']='4';
      $dataInWarehouse['Remarks']='';
      $dataInWarehouse['InStorehouseId']=$wh.'_'.$sinfo['id'];
      $dataInWarehouse['InStorehouseName']=$sinfo['storename'];
      $dataInWarehouse['CreateDate']=$nowDate;
      $dataInWarehouse['LastUpdateDate']=$nowDate;
      $dataInWarehouse['token']=$shopinfo['Token'];
      $dataInWarehouse['stoken']=$shopinfo['Stoken'];
      $dataInWarehouse['IsPay']='0';
      $this->BM()->startTrans();
      foreach ($prolist as $proitem) {
        ////////修改选卖商品的销售价格////////
        $wherestr['ProId']=$proitem['pid'];;
        $wherestr['ProIdCard']=$proitem['pcid'];
        $wherestr['token']=$shopinfo['Token'];
        $wherestr['stoken']=$shopinfo['Stoken'];
        $savedata['Price']=$proitem['price'];
        if(!$this->BM('merpros')->where($wherestr)->save($savedata)){
          $res=false;
          break;
        }

        ////////订货申请单字表////////
        if ($prolist['num']!='0') {
          $dataInWarehouseList['InWarehouseId']=$dataInWarehouse['InWarehouseId'];
          $dataInWarehouseList['ProId']=$proitem['pid'];
          $dataInWarehouseList['ProIdCard']=$proitem['pcid'];
          $dataInWarehouseList['ClassId']=$proitem['cid'];
          $dataInWarehouseList['Price']=$proitem['cprice'];
          $dataInWarehouseList['Count']=$proitem['num'];
          $dataInWarehouseList['Money']=$proitem['cprice']*$proitem['num'];
          $dataInWarehouseList['IsMark']=0;
          $dataInWarehouseList['Remarks']="";
          $dataInWarehouseList['Supplier']="";
          $dataInWarehouseList['CreateDate']=$nowDate;
          $dataInWarehouseList['LastUpdateDate']=$nowDate;
          $dataInWarehouseList['token']=$shopinfo['Token'];

          $dataInWarehouse['Count']+=$proitem['num'];
          $dataInWarehouse['Money']+= $dataInWarehouseList['Money'];
          if(!$this->BM('productinwarehouselist')->add($dataInWarehouseList)){
            $red=false;
            break;
          }
        }
      }
      if ($red) {
        if ($dataInWarehouse['Count']>0) {
          $red=$this->BM('productinwarehouse')->add($dataInWarehouse);
        }
      }

      if ($res && $red) {
        $this->BM()->commit();
        $this->ajaxReturn(array('status' => 'true', 'info' => 'success'), 'JSON');
      } else {
        $this->BM()->rollback();
        $this->ajaxReturn(array('status' => 'false', 'info' => 'SaveError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  //////////批量修改自营商品/////
  public function Selfproeditsave($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $prodata=$info['prolist'];
      $prolist=json_decode($prodata,true);
      $nowDate=date('Y-m-d H:i:s',time());
      $res=true;
      $red=true;
      $this->BM()->startTrans();
      foreach ($prolist as $key => $value) {
        ///////获得商品最低价格////////
        if (empty($minprice[$value['pid']])) {
          $minprice[$value['pid']]=$value['price'];
        } else {
          if (floatval($minprice[$value['pid']])>floatval($value['price'])) {
            $minprice[$value['pid']]=$value['price'];
          }
        }
        ////////修改产品字表价格///////
        $savedata['Price']=$value['price'];
        $wheredata['ProId']=$value['pid'];
        $wheredata['ProIdCard']=$value['pcid'];
        $wheredata['token']=$shopinfo['Token'];
        $wheredata['stoken']=$shopinfo['Stoken'];
        if (!$this->BM('Productlist')->where($wheredata)->save($savedata)) {
          $red=false;
          break;
        }
      }
      //////修改产品主表信息////////
      foreach ($minprice as $k => $val) {
        $savesdata['PriceRange']=$val;
        $wheresdata['ProId']=$k;
        $wheresdata['token']=$shopinfo['Token'];
        $wheresdata['stoken']=$shopinfo['Stoken'];
        if (!$this->BM('Product')->where($wheresdata)->save($savesdata)) {
          $res=false;
          break;
        }
      }
      if ($res && $red) {
        $this->BM()->commit();
        $this->ajaxReturn(array('status' => 'true', 'info' => 'success'), 'JSON');
      } else {
        $this->BM()->rollback();
        $this->ajaxReturn(array('status' => 'false', 'info' => 'SaveError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }


  public function GetFactProinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $proid = $info['pid'];
      $sqlStr="SELECT * FROM RS_Product WHERE token='".$shopinfo['Token']."' AND stoken='0' AND ProId='".$proid."'";
      $prodata=$this->BM()->query($sqlStr);
      $sqlStr="SELECT mp.Price AS setprice,pl.* FROM RS_MerPros mp RIGHT JOIN RS_ProductList pl ON mp.ProId = pl.ProId AND mp.ProIdCard = pl.ProIdCard WHERE mp.ProId='".$proid."' AND mp.token='".$shopinfo['Token']."' AND mp.stoken='".$shopinfo['Stoken']."' ";
      $prolist=$this->BM()->query($sqlStr);
      $prodata[0]['prolist']=$prolist;
      $pagedata['proinfo'] = $prodata[0];
      $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }

  public function SaveFactProinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $proid=$info['proid'];
      $attrdata=$info['attrdata'];
      $res=true;
      $this->BM()->startTrans();
      foreach ($attrdata as $attritem) {
        $wherestr['ProId']=$proid;
        $wherestr['ProIdCard']=$attritem['proidcode'];
        $wherestr['token']=$shopinfo['Token'];
        $wherestr['stoken']=$shopinfo['Stoken'];
        $savedata['Price']=$attritem['setprice'];
        if(!$this->BM('merpros')->where($wherestr)->save($savedata)){
          $res=false;
          break;
        }
      }

      if ($res) {
        $this->BM()->commit();
        $this->ajaxReturn(array('status' => 'true', 'info' => 'success'), 'JSON');
      } else {
        $this->rollback();
        $this->ajaxReturn(array('status' => 'false', 'info' => 'SaveError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  //////获得单个自营商品数据/////
  public function GetSelfProOneinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $proid = $info['proid'];
      $prodata=$this->BM('Product')->where("ProId='%s'",array($proid))->find();
      $pagedata['Pdata'] = $prodata;
      $prolist=$this->BM('Productlist')->where("ProId='%s' and IsDelete=%d",array($proid,0))->select();
      $pagedata['prolist'] = $prolist;
      if (file_exists($this->REALPATH.C('STATICPATH').$proid.'.json')) {
        $proinfo=json_decode(file_get_contents($this->REALPATH.C('STATICPATH').$proid.'.json'),true);
        $pagedata['Simgdata'] = json_encode($proinfo['imgs']);
      } else {
        $pagedata['Simgdata']="{}";
      }
      $this->ajaxReturn(array('status' => 'true', 'info' => $pagedata), 'JSON');
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }
  //////商品山下架操作//////
  public function ShelveProinfo($info){
    $shopinfo=$this->UM('apptoken')->where("Apptoken='".$this->apptoken."' AND Stoken!='0'")->find();
    if ($shopinfo) {
      $ProIdlist=json_decode($info['prolist'],true);
      $sres=true;
      $this->BM()->startTrans();
      foreach ($ProIdlist as $Pro) {
        $proarray=explode('|',$Pro);
        if($proarray[1]=='0'){
          $pro['IsShelves']='1';
        } else{
          $pro['IsShelves']='0';
        }
        if (!$this->BM('Product')->where("ProId='%s'",$proarray[0])->save($pro)) {
          $sres=false;
          break;
        }
      }
      if ($sres) {
        $this->BM()->commit();
        $this->ajaxReturn(array('status' => 'true', 'info' => $ProIdlist), 'JSON');
      } else {
        $this->BM()->rollback();
        $this->ajaxReturn(array('status' => 'false', 'info' => 'shelveError'), 'JSON');
      }
    } else {
      $this->ajaxReturn(array('status' => 'false', 'info' => 'shopError'), 'JSON');
    }
  }

}
?>
