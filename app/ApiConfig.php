<?php namespace Ylc\AppBackendApi;

use Ylc\EnvConfig\Environment;
class ApiConfig{
    public static function AppSlimConfig(){
        return [
        'debug' => Environment::notProduction(),
        'cookies.path' => '/',
        'session.expire' => 36000,
        'salt' => 'd41d8cd98f00b304E9800998ecf8427e'
            ];
    }
}