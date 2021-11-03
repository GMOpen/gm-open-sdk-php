<?php
/**
 * HttpClient
 */
namespace GM;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GM\traits\Singleton;

class HttpClient
{
    use Singleton;

    /**
     * client
     *
     * @var Client
     */
    private $client;

    public function __construct($baseUri)
    {
        $this->client= new Client([
            'base_uri' => $baseUri,
            'timeout' => 6.0,
        ]);
    }


    /**
     * 同步get 请求
     * @param string $url
     * @param array $options
     * @return \Psr\Http\Message\RequestInterface|string
     */
    public function get($url, $options=[])
    {
        try {
            $response = $this->client->get($url, $options);
        } catch (RequestException $e) {
            return $e->getMessage();
        }
        return (string)$response->getBody();
    }


    /**
     * 同步post 请求
     * @param string $url
     * @param mixed $data
     * @param array $options
     * @return \Psr\Http\Message\RequestInterface|string
     */
    public function post($url, $data, $options=[])
    {
        try {
            if (!is_array($data)) {
                $options['body'] = $data;
            } else {
                $options['form_params'] = $data;
            }
            $response = $this->client->post($url, $options);
        } catch (RequestException $e) {
            return $e->getMessage();
        }
        return (string)$response->getBody();
    }
}