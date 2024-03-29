<?php namespace Ylc\AppBackendApi\Controllers;
use Swagger\Annotations as SWG;
/**
 * 演示控制器
 *
 * - 简单输出
 * - 输入验证
 * - jsonp输出
 * @SWG\Resource(
 *     apiVersion="0.2",
 *     swaggerVersion="1.2",
 *     resourcePath="/pet",
 *     basePath="http://localhost/app-backend-api/public"
 *     )
 * 
 */
class HomeController extends Controller
{
    /**
     * demo 
     * @SWG\Api(
     *   path="/pet.{format}/{petId}",
     *   description="Operations about pets",
     *   @SWG\Operation(
     *      method="GET", summary="Find pet by ID", notes="Returns a pet based on ID",
     *      @SWG\ResponseMessage(code=404, message="Pet not found")
     *   )
     * )
     * @return Slim\Http\Response
     */
    public function index()
    {
        
        return $this->json(['app' => 'youjie app', 'message' => 'Hello world!']);
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