<?php namespace Ylc\AppBackendApi\Controllers;

/**
 * 演示控制器
 *
 * - 简单输出
 * - 输入验证
 * - jsonp输出
 * 
 */
class HomeController extends Controller
{
    /**
     * demo 
     *
     * @return Slim\Http\Response
     */
    public function index()
    {
        
        return $this->json(['app' => 'Rester', 'message' => 'Hello world!']);
    }

    /**
     * 验证演示
     *
     * @return \Slim\Http\Response
     */
    public function validateDemo()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required|confirmed',
            'sex'      => 'integer|in:1,0',
        ];
        
        $this->validate($this->request->post(), $rules);

        //以下是验证通过的情况下
        return $this->json(['status' => 'validation passes.']);
    }

    /**
     * jsonp 演示
     *
     * @return \Slim\Http\Response
     */
    public function jsonpDemo()
    {
        $rules = [
            'callback' => 'required',
        ];

        $this->validate($this->request->get(), $rules);

        $return = [
            'username'     => 'hank',
            'age'          => 27
        ];

        $callback = $this->request->get('callback');

        return $this->jsonp($return, $callback);//callbak是可选的，如果不传，默认从GET取callback
    }
}