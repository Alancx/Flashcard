<?php
require_once __DIR__ . '/autoload.php';
use OSS\OssClient;
use OSS\Core\OssException;
/**
* 
*/
class Oss
{
    public $accessKeyId = "LTAI6lidtljoUEVZ";
    public $accessKeySecret = "xdfoitJCLorbJ1S8Rfkh40uYSKCzOS";
    public $endpoint = "http://oss-cn-shanghai.aliyuncs.com";
    public $bucket='hmallresource';
    public $ossClient;
    public function __construct()
    {
        try {
            $this->ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        } catch (OssException $e) {
            print $e->getMessage();   
        }
    }

    public function uploadFile($filePath,$name,$fullname=true)
    {
        $object = $name;
        try {
            $info=$this->ossClient->uploadFile($this->bucket, $object, $filePath);
        } catch (OssException $e) {
            print $e->getMessage();   
            return $e->getMessage();     
        }
        // file_put_contents('1.txt', json_encode($info));
        if ($fullname==true) {
            return $info['info']['url'];
        }else{
            return str_replace('http://'.$info['oss-requestheaders']['Host'], '', $info['info']['url']);
        }

        // echo "<pre>";
        // if ($info['info']['http_code']==200) {
        //     echo "上传成功;图片地址：".$info['info']['url'];
        // }else{
        //     echo "上传失败";
        // }
        // var_dump($info);
        // try{
        // } catch(OssException $e) {
        //     printf(__FUNCTION__ . ": FAILED\n");
        //     printf($e->getMessage() . "\n");
        //     return;
        // }
        // print(__FUNCTION__ . ": OK" . "\n");
    }

    public function delObject($object){
        try{
            $result=$this->ossClient->deleteObject($this->bucket, $object);
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        file_put_contents('1', json_encode($result));
        if ($result['info']['http_code']=='204') {
            return true;
        }else{
            return false;
        }
    }
}
