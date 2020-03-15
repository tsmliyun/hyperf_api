<?php
/**
 * Created by PhpStorm.
 * User: yunli
 * Date: 2019/11/5
 * Time: 下午2:19
 */

namespace App\Util;


use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;

class Log
{

    public static function get(string $name = 'app')
    {
        return ApplicationContext::getContainer()->get(LoggerFactory::class)->get($name);
    }

}