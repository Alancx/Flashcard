<?php
require_once __DIR__ . '/autoload.php';
use OSS\OssClient;
use OSS\Core\OssException;
$accessKeyId = "SCTnMmtqnI4uv5Fv";
$accessKeySecret = "Iij6WRbMm0C0XYfKbbuYSAFrqgmm0y";
$endpoint = "http://oss-cn-shenzhen.aliyuncs.com";
$bucket='lleaves';
$ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
function uploadFile($file,$name)
{
    $object = time().mt_rand(10000,99999).'.'.$name;
    $filePath = $file;
    $info=$ossClient->uploadFile($bucket, $object, $filePath);
    // echo "<pre>";
    // if ($info['info']['http_code']==200) {
    //     echo "上传成功;图片地址：".$info['info']['url'];
    // }else{
    //     echo "上传失败";
    // }
    var_dump($info);
    // try{
    // } catch(OssException $e) {
    //     printf(__FUNCTION__ . ": FAILED\n");
    //     printf($e->getMessage() . "\n");
    //     return;
    // }
    // print(__FUNCTION__ . ": OK" . "\n");
}