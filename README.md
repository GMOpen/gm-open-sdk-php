# gm-open-sdk-php
## 安装
```php
composer require gmopen/gm-open-sdk-php
```
## 使用
```php
use GM\GMOpenClient;
use GM\config\OpenApiConfig;

#merchatId 商户id
#signId 签名对应id
#publicKey 公钥
#privateKey 私钥
#mod "dev-测试环境，pro-生产环境"

$config = new OpenApiConfig($merchId, $signId, $publicKey, $privatekey, OpenApiConfig::MOD_DEV);

#$uri 接口地址
#请求参数[业务参数，数组传参]
$res = GMOpenClient::instance($config)->call('/openapi/goods/get', $param);

```

