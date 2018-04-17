<?php
class Aliyunoss {
    public function __construct()
    {
        $this->accessKeyId='LTAIEPdzFR5O6emt';
        $this->accessKeySecret='7itSWP86FBRIhIOX8EyWz6bfB6VrXg';
        $this->endpoint='oss-cn-shanghai.aliyuncs.com';
    }
    public function checkOss(){
        require_once '/opt/app/erp-web/application/libraries/aliyunoss/autoload.php'; //引入这个阿里云文件
        require_once "/opt/app/erp-web/application/libraries/aliyunoss/src/OSS/OssClient.php"; // | OSS客户端类，用户通过OssClient的实例调用接口 |
        require_once "/opt/app/erp-web/application/libraries/aliyunoss/src/OSS/Core/OssException.php"; // | OSS异常类，用户在使用的过程中，只需要注意这个异常|
        //接着再任何一个方法中使用：
        $accessKeyId = $this->accessKeyId ;
        $accessKeySecret = $this->accessKeySecret;
        $endpoint =  $this->endpoint;

        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            return $ossClient;
        } catch (OssException $e) {
            print $e->getMessage();
            return false;
        }

    }
    
}
?>