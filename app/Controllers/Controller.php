<?php namespace Ylc\AppBackendApi\Controllers;
/**
 * Controller.php
 *
 */

/**
 * 基础控制器
 */
abstract class Controller
{
    /**
     * 输出对象
     *
     * @var \Slim\Http\Request
     */
    protected $request;

    /**
     * 输出对象
     *
     * @var \Slim\Http\Response
     */
    protected $reponse;
    
    /**
     * HTTP status codes
     *
     * @var array
     */
    public static $codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        429 => 'Too Many Requests', //still in draft but used for rate limiting
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
            );

    /**
     * Slim容器对象
     *
     * @var \Slim\Slim
     */
    public static $app;


    
    /**
     * Bitmask consisting of <b>JSON_HEX_QUOT</b>,
     * <b>JSON_HEX_TAG</b>,
     * <b>JSON_HEX_AMP</b>,
     * <b>JSON_HEX_APOS</b>,
     * <b>JSON_NUMERIC_CHECK</b>,
     * <b>JSON_PRETTY_PRINT</b>,
     * <b>JSON_UNESCAPED_SLASHES</b>,
     * <b>JSON_FORCE_OBJECT</b>,
     * <b>JSON_UNESCAPED_UNICODE</b>.
     * The behaviour of these constants is described on
     * the JSON constants page.
     * @var int
     */
    public $encodingOptions = 0;
    
    /**
     * constructor
     */
    final function __construct()
    {
        if (is_null(self::$app)) {
            throw new \Exception("Error Processing Request", 1);
        }

        $this->request   = self::$app->request();
        $this->response  = self::$app->response();
        $this->config    = self::$app->config;
        $this->validator = self::$app->validator;
        $this->init();
    }

    /**
     * 初始调用方法
     *
     * @return
     */
    public function init(){
    	
    }
    
    
    public function decodeData(){
        
    }
    
    public function incodeData(){
        
    }

    /**
     * 生成json输出
     *
     * @param arrat $data 输出数据 *
     *
     * @return \Slim\Http\Response
     */
    protected function json($data, $status = 200)
    {
        return $this->setJsonResponse($data, $status);
    }

    /**
     * jsonp格式输出
     *
     * @param array  $data     输出数据 *
     * @param string $callback 回调函数 (optional)
     *
     * @return \Slim\Http\Response
     */
    protected function jsonp($data, $callback = '')
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        $callback = $callback ?: $this->request->get('callback');

        $body = $callback ? "{$callback}($json)" : $json;

        return $this->setJsonResponse($body, 200);
    }

    /**
     * 错误输出
     *
     * @param string $message 错误消息 *
     * @param int    $code    错误码 *
     * @param array  $errors  错误明细 (optional)
     *
     * @return \Slim\Http\Response
     */
    protected function error($message, $status, $errors = [])
    {

        if (!isset($status, self::$codes)) {
            throw new \Exception("The error code '{$status}' not a valid error code.");
        }

        $data = [
            'message' => $message,
        ];

        empty($errors) || $data['errors'] = $errors;

        $this->setJsonResponse($data, $status);

        self::$app->stop();
    }

    /**
     * 验证输入
     *
     * @param array   $input  需要验证的数据 *
     * @param arrat   $rules  验证规则 *
     * @param boolean $return 是否返回验证结果 (optional, 默认: false)
     *
     * @return array If $return is true and validation failed.
     */
    protected function validate($input, $rules, $return = false)
    {
        $validator = $this->validator->make($input, $rules);

        if ($validator->fails()) {
            if ($return) {
                return $validator->messages->all();
            }

            $this->error('Validation Failed.', 422, $validator->messages());
        }

        return true;
    }

    /**
     * 输出json
     *
     * @param mixed   $body   内容
     * @param integer $status 状态码 (optional, 默认:200)
     *
     * @return \Slim\Http\Response
     */
    protected function setJsonResponse($body, $status = 200)
    {
        if ($this->config['debug']){
            $this->response->headers->set('X-Time-Usage',round(microtime(true) - APP_START, 6));
            $this->encodingOptions = JSON_PRETTY_PRINT;
        }
        $status = intval($status);
        //append status code
        $response = [];
        $response['status'] = $status;
        $response['body'] = $body;
        
        $this->response->setStatus($status);

        $this->response->headers->set('content-type', 'application/json');

        is_string($body) || $body = json_encode($response, $this->encodingOptions);

        return $this->response->setBody($body);
    }
}