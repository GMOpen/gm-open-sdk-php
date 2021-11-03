<?php
/**
 * open api config
 *
 * @DateTime 2021-11-03
 */
namespace GM\config;

class OpenApiConfig
{
    const MOD_DEV = 'dev';    //沙箱环境
    const MOD_PRO = 'pro';    //生产环境
    
    /**
     * 环境
     *
     * @var string
     */
    private $mod = '';

    /**
     * 商户id
     *
     * @var string
     */
    private $merchId = '';

    /**
     * 签名密钥对应的id,⽅便⽆缝切换密钥
     *
     * @var string
     */
    private $signId = '';

    /**
     * 私钥
     *
     * @var string
     */
    private $privateKey = '';

    /**
     * 公钥
     *
     * @var string
     */
    private $publicKey = '';

    public function __construct($merchId, $signId, $publicKey, $privateKey, $mod = self::MOD_DEV)
    {
        $this->merchId = $merchId;
        $this->signId = $signId;
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->mod = $mod;
    }
    
    public function getMod()
    {
        return $this->mod;
    }

    public function getMerchId()
    {
        return $this->merchId;
    }

    public function getSignId()
    {
        return $this->signId;
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }
}