<?php
/**
 * Created by PhpStorm.
 * User: yunli
 * Date: 2019/10/12
 * Time: 下午2:29
 */

namespace App\Service;


use App\Util\Log;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\CoroutineHandler;

class HttpService
{
    /**
     * HTTP请求
     * @param $baseUrl
     * @param $url
     * @param $data
     * @param $headers
     * @return null|string
     * @throws
     */
    public function httpPost($baseUrl, $url, $data, $headers)
    {
        try {
            $client = new Client([
                'base_uri' => $baseUrl,
                'handler'  => HandlerStack::create(new CoroutineHandler()),
                'timeout'  => 5,
            ]);

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

    /**
     * GET请求
     * @param $baseUrl
     * @param $url
     * @param $data
     * @param $headers
     * @return mixed|null
     * @throws
     */
    public function httpGet($baseUrl, $url, $data, $headers)
    {
        try {
            $client = new Client([
                'base_uri' => $baseUrl,
                'handler'  => HandlerStack::create(new CoroutineHandler()),
                'timeout'  => 5,
            ]);

            $requestData = [
                'headers' => $headers,
                'json'    => $data,
            ];

            $result = $client->request('get', $url, $requestData);

            $return = $result->getBody()->getContents();

            return json_decode($return, true);

        } catch (\Exception $e) {
            $logData = compact('baseUrl', 'url', 'data', 'headers');
            Log::get()->info(__METHOD__ . '=== error ===' . $e->getMessage(), $logData);
            return null;
        }
    }

}