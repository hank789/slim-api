<?php
use Ylc\EnvConfig\Environment;

require('../vendor/autoload.php');
if (Environment::current()->notProduction()) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
date_default_timezone_set('Asia/Shanghai');