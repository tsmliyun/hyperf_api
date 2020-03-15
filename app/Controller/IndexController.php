<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Service\JuHeApiService;

class IndexController extends AbstractController
{

    public $juHeApiService;

    public function __construct(JuHeApiService $juHeApiService)
    {
        $this->juHeApiService = $juHeApiService;
    }

    public function index()
    {
        $user   = $this->request->input('user', 'Hyperf111');
        $method = $this->request->getMethod();

        return [
            'method'  => $method,
            'message' => "Hello {$user}.",
        ];
    }

    /**
     * 天气查询
     * @return array
     */
    public function weather()
    {
        $city = $this->request->input('city', '上海');

        $params = [
            'city' => $city
        ];

        $result = $this->juHeApiService->queryWeather($params);

        if ($result['error_code'] == 0) {
            $return = [];
//            var_dump($result);
//            exit;
            if (!empty($result['result']['future'])) {
                foreach ($result['result']['future'] as $key => $value) {
                    $tmpTem = $value['temperature'] ?? '';
                    $tmpWeather = $value['weather'] ?? '';
                    $tmpDirect = $value['direct'] ?? '';
                    $return[] = [
                        'date'   => $value['date'],
                        'detail' => $tmpTem . '-' . $tmpWeather . '-' . $tmpDirect
                    ];
                }
            }

            return $this->success($return);
        } else {
            $return = [
                'code' => ErrorCode::SERVER_ERROR,
                'msg' => $result['reason'] ?? 'error',
            ];
            return $this->error($return);
        }
    }

    public function success($data)
    {
        $return = [
            'status' => ErrorCode::SUCCESS_STATUS,
            'code'   => ErrorCode::SERVER_SUCCESS,
            'msg'    => 'success',
            'data'   => $data
        ];

        return $return;
    }

    public function error($data)
    {
        $return = [
            'status' => ErrorCode::ERROR_STATUS,
            'error' => [
                'code'   => $data['code'] ?? ErrorCode::SERVER_ERROR,
                'msg'    => $data['msg'] ?? 'error',
            ],
            'data'   => new \stdClass()
        ];

        return $return;
    }

}


