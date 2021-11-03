<?php
/**
 * guomian Http Client
 *
 * @DateTime 2021-11-03
 */
namespace GM;

use GM\config\OpenApiConfig;
use GM\traits\Singleton;
use GM\exceptions\RSAKeyException;
use GM\exceptions\RequestException;
use phpseclib3\Crypt\RSA;

class GMOpenClient
{
    use Singleton;

    /**
     * httpClient
     *
     * @var HttpClient
     */
    private $httpClient;

    /**
     * config
     *
     * @var OpenApiConfig
     */
    private $config;

    /**
     * 环境域名
     *
     * @var array
     */
    private $modDomain = [
        OpenApiConfig::MOD_DEV => 'http://open-apitest.idfgm.com/',
        OpenApiConfig::MOD_PRO => 'https://open-api.idfgm.com/',
    ];

    public function __construct(OpenApiConfig $config)
    {
        $this->config = $config;
        $baseUri = $this->modDomain[$this->config->getMod()];
        $this->httpClient = HttpClient::instance($baseUri);
    }

    /**
     * 调用
     *
     * @param $method
     * @param $args
     *
     * @return void
     */
    public function call($api, array $param = [])
    {
        $param = $this->buildRequestParam($param);
        $sign = $this->sign($param);
        //echo $sign;exit;
        $options = [
            'headers' => [
                'Content-Type'  => 'application/json;charset=utf-8',
                'Authorization' => $sign,
                'tags'          => 'gm-open-sdk-php',
            ],
        ];
        
        $response = $this->httpClient->post($api, $param, $options);
        $result = json_decode($response, true);
        if ($result === null) {
            throw new RequestException('请求错误:' . $response);
        }

        return $result;
    }

    /**
     * build request param
     *
     * @return void
     */
    protected function buildRequestParam(&$param): string
    {
        $commonParam = [
            'merchId' => $this->config->getMerchId(),
            'signId' => $this->config->getSignId(),
            'nonceStr' => time() . rand(1000, 99999),
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        return json_encode(array_merge($commonParam, $param));
    }
    
    /**
     * sign
     *
     * @param string $content
     *
     * @return string
     */
    private function sign(string $content)
    {
        $private = RSA::loadPrivateKey($this->config->getPrivateKey())->withPadding(RSA::SIGNATURE_RELAXED_PKCS1);
        $sign = base64_encode($private->sign($content));
        $sign = str_replace(PHP_EOL, "", $sign);

        if (!$this->config->getPublicKey()) {
            $public = RSA::loadPublicKey($this->config->getPublicKey())->withPadding(RSA::SIGNATURE_RELAXED_PKCS1);
            if (!$public->verify($content, base64_decode($sign))) {
                throw new RSAKeyException('sign 自检失败！');
            }
        }
        
        return $sign;
    }
}