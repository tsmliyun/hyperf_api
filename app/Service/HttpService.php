<?php
/**
 * Created by PhpStorm.
 * User: yunli
 * Date: 2019/10/12
 * Time: 下午2:29
 */

namespace App\Service;


use App\Util\Log;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Guzzle\CoroutineHandler;

class HttpService
{

    /**
     * @var $clientFactory \Hyperf\Guzzle\ClientFactory
     */
    private $clientFactory;

    public function __construct()
    {
        $this->clientFactory = make(ClientFactory::class);
    }

    /**
     * HTTP请求
     * @param $baseUrl
     * @param $url
     * @param $data
     * @param $header
     * @return null|string
     * @throws
     */
    public function httpPost($baseUrl, $url, $data, $header = [])
    {
        try {
            $options = [
                'base_uri' => $baseUrl,
                'handler'  => HandlerStack::create(new CoroutineHandler()),
                'timeout'  => 5,
            ];

            $headers = [
                'Content-Type' => 'application/json;charset=utf-8',
            ];

            if(!empty($headers)){
                $headers = array_merge($headers,$header);
            }

            // $client 为协程化的 GuzzleHttp\Client 对象
            $client = $this->clientFactory->create($options);

            $requestData = [
                'json'    => $data,
                'headers' => $headers,
            ];

            $result = $client->request('post', $url, $requestData);

            $return = $result->getBody()->getContents();

            return json_decode($return, true);

        } catch (\Exception $e) {
            $logData = compact('baseUrl', 'url', 'data', 'headers');
            Log::get()->info(__METHOD__ . '=== error ===' . $e->getMessage(), $logData);
            return null;
        }
    }


}