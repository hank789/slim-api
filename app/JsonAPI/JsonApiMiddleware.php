<?php namespace Ylc\AppBackendApi\JsonAPI;
/**
 * jsonAPI - Slim extension to implement fast JSON API's
 *
 * @package Slim
 * @subpackage Middleware
 * @author Jonathan Tavares <the.entomb@gmail.com>
 * @license GNU General Public License, version 3
 * @filesource
 *
 *
*/

/**
 * JsonApiMiddleware - Middleware that sets a bunch of static routes for easy bootstrapping of json API's
 *
 * @package Slim
 * @subpackage View
 * @author Jonathan Tavares <the.entomb@gmail.com>
 * @license GNU General Public License, version 3
 * @filesource
 */
class JsonApiMiddleware extends \Slim\Middleware {


    /**
     * Sets a buch of static API calls
     *
     */
    function __construct(){

        $app = \Slim\Slim::getInstance();
        $app->config('debug', false);

        // Mirrors the API request
        $app->get('/return', function() use ($app) {

            $this->setJsonResponse(200,array(
                'method'    => $app->request()->getMethod(),
                'name'      => $app->request()->get('name'),
                'headers'   => $app->request()->headers(),
                'params'    => $app->request()->params(),
            ));
        });

        // Generic error handler
        $app->error(function (\Exception $e) use ($app) {


            $this->setJsonResponse(500,array(
                'error' => true,
                'msg'   => self::_errorType($e->getCode()) .": ". $e->getMessage(),
            ));
        });

        // Not found handler (invalid routes, invalid method types)
        $app->notFound(function() use ($app) {
            $this->setJsonResponse(404,array(
                'error' => TRUE,
                'msg'   => 'Invalid route',
            ));
        });

        // Handle Empty response body
        $app->hook('slim.after.router', function () use ($app) {
            //Fix sugested by: https://github.com/bdpsoft
            //Will allow download request to flow
            if($app->response()->header('Content-Type')==='application/octet-stream'){
                return;
            }

            if (strlen($app->response()->body()) == 0) {
                $this->setJsonResponse(500,array(
                    'error' => TRUE,
                    'msg'   => 'Empty response',
                ));
            }
        });

    }
    
    function setJsonResponse($status = 200,$body){
        $app = \Slim\Slim::getInstance();
        $debug = $app->config['debug'];
        $encodingOptions = 0;
        if ($debug) {
            $app->response->headers->set('X-Time-Usage',round(microtime(true) - APP_START, 6));
            $encodingOptions = JSON_PRETTY_PRINT;
        }
        $status = intval($status);
        //append status code
        $response = [];
        $response['status'] = $status;
        $response['body'] = $body;
        
        $app->response->setStatus($status);
        $app->response->headers->set('content-type', 'application/json');
        
        return $app->response->setBody(json_encode($response, $encodingOptions));
    }

    /**
     * Call next
     */
    function call(){
        return $this->next->call();
    }

    static function _errorType($type=1){
        switch($type)
        {
            default:
            case E_ERROR: // 1 //
                return 'ERROR';
            case E_WARNING: // 2 //
                return 'WARNING';
            case E_PARSE: // 4 //
                return 'PARSE';
            case E_NOTICE: // 8 //
                return 'NOTICE';
            case E_CORE_ERROR: // 16 //
                return 'CORE_ERROR';
            case E_CORE_WARNING: // 32 //
                return 'CORE_WARNING';
            case E_CORE_ERROR: // 64 //
                return 'COMPILE_ERROR';
            case E_CORE_WARNING: // 128 //
                return 'COMPILE_WARNING';
            case E_USER_ERROR: // 256 //
                return 'USER_ERROR';
            case E_USER_WARNING: // 512 //
                return 'USER_WARNING';
            case E_USER_NOTICE: // 1024 //
                return 'USER_NOTICE';
            case E_STRICT: // 2048 //
                return 'STRICT';
            case E_RECOVERABLE_ERROR: // 4096 //
                return 'RECOVERABLE_ERROR';
            case E_DEPRECATED: // 8192 //
                return 'DEPRECATED';
            case E_USER_DEPRECATED: // 16384 //
                return 'USER_DEPRECATED';
        }
    }

}
