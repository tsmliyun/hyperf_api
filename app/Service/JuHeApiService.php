<?php


namespace App\Service;


class JuHeApiService extends HttpService
{
    public $appUrl;

    public $appKey;

    public function __construct()
    {
        $this->appKey = config('ju_he_api.weather.app_key');
        $this->appUrl = config('ju_he_api.weather.app_url');
    }

    /**
     * 查询天气
     * @param array $params
     * @return mixed|null
     */
    public function queryWeather($params = [])
    {
        $data = [
            'city' => urlencode($params['city']),
            'key'  => $this->appKey
        ];

        $header = [
            'Content-Type' => 'application/json;charset=utf-8',
        ];

        $url = 'simpleWeather/query';
        $url .= '?city=' . $data['city'] . '&key=' . $data['key'];

        $result = $this->httpGet($this->appUrl, $url, $data, $header);

        return $result;
    }
}